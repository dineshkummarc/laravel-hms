<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\AmbulanceRequest;
use App\Models\Appointment;
use App\Models\Bed;
use App\Models\Department;
use App\Models\PatientProfile;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReceptionistController extends Controller
{
    public function index(): View
    {
        return view('receptionist.dashboard', [
            'todayAppointments' => Appointment::with(['patient', 'doctor', 'department'])
                ->whereDate('appointment_date', today())
                ->orderBy('appointment_date')
                ->get(),
            'pendingGuests' => Appointment::whereNull('patient_id')->where('status', 'pending')->latest()->take(8)->get(),
            'patients' => User::role('patient')->orderBy('name')->get(),
            'doctors' => User::role('doctor')->with('doctorProfile.department')->orderBy('name')->get(),
            'departments' => Department::orderBy('name')->get(),
            'availableBeds' => Bed::with('room')->where('status', 'available')->get(),
        ]);
    }

    public function quickRegisterPatient(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'age' => 'nullable|integer|min:0',
            'gender' => 'nullable|in:male,female,other',
            'blood_group' => 'nullable|string|max:5',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make('PatientSecure2026!'),
                'uid' => 'pat_'.Str::lower(Str::random(12)),
                'is_active' => true,
            ]);
            $user->assignRole('patient');
            PatientProfile::create([
                'user_id' => $user->id,
                'age' => $validated['age'] ?? 18,
                'gender' => $validated['gender'] ?? 'other',
                'blood_group' => $validated['blood_group'] ?? null,
                'emergency_contact' => $validated['phone'],
                'medical_history' => [],
            ]);
        });

        return back()->with('success', 'Patient registered. Temporary password: PatientSecure2026!');
    }

    public function searchPatients(Request $request): JsonResponse
    {
        $q = trim((string) $request->get('q', ''));
        $patients = User::role('patient')
            ->when($q !== '', fn ($query) => $query->where(function ($inner) use ($q) {
                $inner->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
            }))
            ->orderBy('name')
            ->limit(15)
            ->get(['id', 'name', 'email']);

        return response()->json($patients);
    }

    public function getAvailableSlots(User $doctor, Request $request): JsonResponse
    {
        $date = Carbon::parse($request->get('date', now()->toDateString()));
        $schedule = Schedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $date->dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (! $schedule) {
            return response()->json(['slots' => []]);
        }

        $booked = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->pluck('appointment_date')
            ->map(fn ($dt) => Carbon::parse($dt)->format('H:i'))
            ->all();

        $slots = [];
        $cursor = Carbon::parse($date->toDateString().' '.$schedule->start_time);
        $end = Carbon::parse($date->toDateString().' '.$schedule->end_time);

        while ($cursor->lt($end)) {
            $key = $cursor->format('H:i');
            if (! in_array($key, $booked, true)) {
                $slots[] = $cursor->format('Y-m-d H:i');
            }
            $cursor->addMinutes((int) $schedule->slot_duration);
        }

        return response()->json(['slots' => $slots]);
    }

    public function bookAppointment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after:now',
            'visit_type' => 'required|in:initial,follow_up,routine_check',
            'reason' => 'nullable|string',
        ]);

        $doctor = User::findOrFail($validated['doctor_id']);

        Appointment::create([
            ...$validated,
            'department_id' => $doctor->doctorProfile?->department_id ?? Department::query()->value('id'),
            'status' => 'confirmed',
            'token_number' => (string) $this->generateDailyToken((int) $validated['doctor_id'], $validated['appointment_date']),
            'symptoms_summary' => $validated['reason'] ?? 'Walk-in / front desk booking',
        ]);

        return back()->with('success', 'Appointment booked and confirmed.');
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);
        $appointment->update($validated);

        return back()->with('success', 'Appointment status updated.');
    }

    public function bedMap(): View
    {
        return view('receptionist.beds', [
            'beds' => Bed::with(['room.department'])->orderBy('id')->get(),
            'admissions' => Admission::where('status', 'admitted')->with(['patient', 'bed'])->get()->keyBy('bed_id'),
        ]);
    }

    public function admitPatient(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'bed_id' => 'required|exists:beds,id',
            'reason_for_admission' => 'required|string',
        ]);

        DB::transaction(function () use ($validated) {
            $bed = Bed::where('id', $validated['bed_id'])->lockForUpdate()->first();
            if ($bed->status !== 'available') {
                abort(422, 'Bed is currently occupied or under maintenance.');
            }
            Admission::create([
                ...$validated,
                'admission_date' => now(),
                'status' => 'admitted',
            ]);
            $bed->update(['status' => 'occupied']);
        });

        return back()->with('success', 'Patient admitted.');
    }

    public function dischargePatient(Admission $admission): RedirectResponse
    {
        DB::transaction(function () use ($admission) {
            $admission->update([
                'status' => 'discharged',
                'discharge_date' => now(),
            ]);
            optional($admission->bed)->update(['status' => 'available']);
        });

        return back()->with('success', 'Patient discharged and bed released.');
    }

    public function ambulanceIndex(): View
    {
        return view('receptionist.ambulance', [
            'requests' => AmbulanceRequest::latest()->paginate(20),
        ]);
    }

    public function dispatchAmbulance(Request $request, AmbulanceRequest $ambulanceRequest): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_code' => 'required|string|max:50',
            'driver_name' => 'required|string|max:255',
            'status' => 'required|in:dispatched,en_route,arrived,completed,cancelled',
        ]);

        $ambulanceRequest->update([
            ...$validated,
            'dispatched_at' => $ambulanceRequest->dispatched_at ?? now(),
        ]);

        return back()->with('success', 'Ambulance status updated.');
    }

    private function generateDailyToken(int $doctorId, string $date): int
    {
        return Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->count() + 1;
    }
}
