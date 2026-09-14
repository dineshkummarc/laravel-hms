<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\LabReport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DoctorDashboardController extends Controller
{
    public function index(): View
    {
        $doctorId = Auth::id();
        $todayAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->with('patient')
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('doctor.dashboard', compact('todayAppointments'));
    }

    public function appointments(): View
    {
        $appointments = Appointment::where('doctor_id', Auth::id())
            ->with('patient')
            ->orderBy('appointment_date', 'desc')
            ->paginate(15);

        return view('doctor.appointments.index', compact('appointments'));
    }

    public function showAppointment(Appointment $appointment): View
    {
        // Ensure this doctor owns the appointment
        if ($appointment->doctor_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this medical record.');
        }

        $appointment->load(['patient.patientProfile', 'medicalRecord.prescription', 'department']);

        return view('doctor.appointments.show', compact('appointment'));
    }

    public function showFollowUpContext(Appointment $appointment): View
    {
        if ($appointment->doctor_id !== Auth::id()) {
            abort(403);
        }

        $previousRecords = $appointment->patient->medicalRecords()->latest()->take(5)->get();

        return view('doctor.appointments.follow_up', compact('appointment', 'previousRecords'));
    }

    public function patientLabHistory(User $user): View
    {
        // Ensure user is actually a patient
        abort_unless($user->hasRole('patient'), 404);

        $labReports = LabReport::where('patient_id', $user->id)
            ->with('labTest')
            ->latest()
            ->paginate(10);

        return view('doctor.patients.lab_history', compact('user', 'labReports'));
    }

    public function schedules(): View
    {
        $schedules = \App\Models\Schedule::where('doctor_id', Auth::id())->orderBy('day_of_week')->get();

        return view('doctor.schedules.index', compact('schedules'));
    }
}