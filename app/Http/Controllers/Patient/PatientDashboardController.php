<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\AmbulanceRequest;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\Invoice;
use App\Models\LabReport;
use App\Models\LabTest;
use App\Models\MedicalRecord;
use App\Models\Notice;
use App\Models\Prescription;
use App\Models\User;
use App\Services\LabInvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PatientDashboardController extends Controller
{
    public function index(): View
    {
        $patientId = Auth::id();

        $upcomingAppointments = Appointment::where('patient_id', $patientId)
            ->where('appointment_date', '>=', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->with(['doctor', 'department'])
            ->orderBy('appointment_date')
            ->take(3)
            ->get();

        $recentLabs = LabReport::where('patient_id', $patientId)
            ->with('labTest')
            ->latest()
            ->take(5)
            ->get();

        $notices = Notice::active()->forAudience('patient')->latest()->take(4)->get();

        return view('patient.dashboard', compact('upcomingAppointments', 'recentLabs', 'notices'));
    }

    public function showBookingForm(): View
    {
        $departments = Department::orderBy('name')->get();
        $doctors = User::role('doctor')->with('doctorProfile.department')->orderBy('name')->get();

        return view('patient.appointments.book', compact('departments', 'doctors'));
    }

    public function storeAppointment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'appointment_date' => 'required|date|after:now',
            'reason' => 'required|string|max:1000',
        ]);

        Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $validated['doctor_id'],
            'department_id' => $validated['department_id'],
            'appointment_date' => $validated['appointment_date'],
            'reason' => $validated['reason'],
            'symptoms_summary' => $validated['reason'],
            'visit_type' => 'initial',
            'status' => 'pending',
        ]);

        return redirect()->route('patient.appointments.index')->with('success', 'Appointment requested. Reception will confirm the slot.');
    }

    public function myAppointments(): View
    {
        $appointments = Appointment::where('patient_id', Auth::id())
            ->with(['doctor', 'department'])
            ->orderByDesc('appointment_date')
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    public function cancelAppointment(Appointment $appointment): RedirectResponse
    {
        abort_unless($appointment->patient_id === Auth::id(), 403);

        if (! in_array($appointment->status, ['pending', 'confirmed'], true)) {
            return back()->with('error', 'This appointment can no longer be cancelled.');
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Appointment cancelled.');
    }

    public function showLabPurchaseForm(): View
    {
        $labTests = LabTest::where('is_active', true)->orderBy('name')->get();

        return view('patient.labs.buy', compact('labTests'));
    }

    public function storeLabPurchase(Request $request, LabInvoiceService $service): RedirectResponse
    {
        $validated = $request->validate([
            'lab_test_id' => 'required|exists:lab_tests,id',
        ]);

        $service->createInvoiceForTests(Auth::id(), null, [(int) $validated['lab_test_id']]);

        return redirect()->route('patient.invoices.index')->with('success', 'Lab order created. Please settle the invoice.');
    }

    public function myLabReports(): View
    {
        $reports = LabReport::where('patient_id', Auth::id())
            ->with(['labTest'])
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('patient.labs.index', compact('reports'));
    }

    public function downloadPDF(LabReport $labReport): StreamedResponse
    {
        abort_if($labReport->patient_id !== Auth::id(), 403);
        abort_if(! $labReport->document_path, 404, 'No file is attached to this report yet.');

        return Storage::disk('public')->download($labReport->document_path);
    }

    public function myPrescriptions(): View
    {
        $prescriptions = Prescription::whereHas('medicalRecord', fn ($q) => $q->where('patient_id', Auth::id()))
            ->with(['medicalRecord.doctor'])
            ->latest()
            ->paginate(10);

        return view('patient.prescriptions.index', compact('prescriptions'));
    }

    public function myInvoices(): View
    {
        $invoices = Invoice::where('patient_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('patient.invoices.index', compact('invoices'));
    }

    public function medicalRecords(): View
    {
        $records = MedicalRecord::where('patient_id', Auth::id())
            ->with(['doctor', 'prescription'])
            ->latest()
            ->paginate(10);

        return view('patient.records.index', compact('records'));
    }

    public function feedbackForm(): View
    {
        $doctors = User::role('doctor')->orderBy('name')->get();
        $feedback = Feedback::where('patient_id', Auth::id())->latest()->get();

        return view('patient.feedback.index', compact('doctors', 'feedback'));
    }

    public function storeFeedback(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'doctor_id' => 'nullable|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'category' => 'required|in:general,doctor,nursing,facility,billing',
            'comment' => 'nullable|string|max:2000',
        ]);

        Feedback::create([
            ...$validated,
            'patient_id' => Auth::id(),
            'is_published' => false,
        ]);

        return back()->with('success', 'Thank you. Your feedback was submitted for review.');
    }

    public function ambulanceForm(): View
    {
        $requests = AmbulanceRequest::where('patient_id', Auth::id())->latest()->get();

        return view('patient.ambulance.index', compact('requests'));
    }

    public function storeAmbulance(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'caller_name' => 'required|string|max:255',
            'caller_phone' => 'required|string|max:30',
            'pickup_address' => 'required|string|max:255',
            'destination' => 'nullable|string|max:255',
            'priority' => 'required|in:standard,urgent,critical',
            'notes' => 'nullable|string',
        ]);

        AmbulanceRequest::create([
            ...$validated,
            'patient_id' => Auth::id(),
            'status' => 'requested',
        ]);

        return back()->with('success', 'Ambulance requested. Dispatch will contact you shortly.');
    }
}
