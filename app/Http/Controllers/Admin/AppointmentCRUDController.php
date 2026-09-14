<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Appointments',
            'resource' => 'admin.appointments',
            'items'    => Appointment::with(['patient', 'doctor', 'department'])->latest('appointment_date')->paginate(20),
            'columns'  => ['patient.name', 'doctor.name', 'appointment_date', 'visit_type', 'status'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Appointment',
            'resource' => 'admin.appointments',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Appointment::create($this->validated($request));

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment created.');
    }

    public function show(Appointment $appointment): View
    {
        $appointment->load(['patient', 'doctor', 'department']);

        return view('admin.crud.show', [
            'title'    => 'Appointment',
            'resource' => 'admin.appointments',
            'item'     => $appointment,
            'columns'  => ['patient.name', 'doctor.name', 'department.name', 'appointment_date', 'token_number', 'visit_type', 'status', 'parent_appointment_id'],
        ]);
    }

    public function edit(Appointment $appointment): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Appointment',
            'resource' => 'admin.appointments',
            'item'     => $appointment,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $appointment->update($this->validated($request));

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'patient_id'            => 'required|exists:users,id',
            'doctor_id'             => 'required|exists:users,id',
            'department_id'         => 'required|exists:departments,id',
            'parent_appointment_id' => 'nullable|exists:appointments,id',
            'appointment_date'      => 'required|date',
            'token_number'          => 'nullable|string|max:255',
            'visit_type'            => 'required|in:initial,follow_up,routine_check',
            'status'                => 'required|in:pending,confirmed,completed,cancelled',
        ]);
    }

    private function fields(): array
    {
        return [
            ['name' => 'patient_id', 'label' => 'Patient', 'type' => 'select', 'required' => true, 'options' => User::role('patient')->orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'doctor_id', 'label' => 'Doctor', 'type' => 'select', 'required' => true, 'options' => User::role('doctor')->orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'department_id', 'label' => 'Department', 'type' => 'select', 'required' => true, 'options' => Department::orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'parent_appointment_id', 'label' => 'Parent appointment ID', 'type' => 'number', 'required' => false],
            ['name' => 'appointment_date', 'label' => 'Appointment date', 'type' => 'datetime-local', 'required' => true],
            ['name' => 'token_number', 'label' => 'Token number', 'type' => 'text', 'required' => false],
            ['name' => 'visit_type', 'label' => 'Visit type', 'type' => 'select', 'required' => true, 'options' => ['initial' => 'Initial', 'follow_up' => 'Follow up', 'routine_check' => 'Routine check']],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'cancelled' => 'Cancelled']],
        ];
    }
}
