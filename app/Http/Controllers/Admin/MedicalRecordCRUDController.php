<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicalRecordCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Medical records',
            'resource' => 'admin.medical-records',
            'items'    => MedicalRecord::with(['patient', 'doctor', 'appointment'])->latest()->paginate(20),
            'columns'  => ['patient.name', 'doctor.name', 'diagnosis', 'created_at'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Medical record',
            'resource' => 'admin.medical-records',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['vitals'] = $this->decodeJson($request->input('vitals'));

        MedicalRecord::create($validated);

        return redirect()->route('admin.medical-records.index')->with('success', 'Medical record created.');
    }

    public function show(MedicalRecord $medical_record): View
    {
        $medical_record->load(['patient', 'doctor', 'appointment']);

        return view('admin.crud.show', [
            'title'    => 'Medical record',
            'resource' => 'admin.medical-records',
            'item'     => $medical_record,
            'columns'  => ['patient.name', 'doctor.name', 'appointment_id', 'clinical_notes', 'diagnosis', 'vitals'],
        ]);
    }

    public function edit(MedicalRecord $medical_record): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Medical record',
            'resource' => 'admin.medical-records',
            'item'     => $medical_record,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, MedicalRecord $medical_record): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['vitals'] = $this->decodeJson($request->input('vitals'));

        $medical_record->update($validated);

        return redirect()->route('admin.medical-records.index')->with('success', 'Medical record updated.');
    }

    public function destroy(MedicalRecord $medical_record): RedirectResponse
    {
        $medical_record->delete();

        return redirect()->route('admin.medical-records.index')->with('success', 'Medical record deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'patient_id'     => 'required|exists:users,id',
            'doctor_id'      => 'required|exists:users,id',
            'clinical_notes' => 'required|string',
            'diagnosis'      => 'required|string',
            'vitals'         => 'nullable|string',
        ]);
    }

    private function decodeJson(?string $value): array
    {
        if ($value === null || trim($value) === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? $decoded : [];
    }

    private function fields(): array
    {
        return [
            ['name' => 'appointment_id', 'label' => 'Appointment', 'type' => 'select', 'required' => true, 'options' => Appointment::orderByDesc('id')->pluck('id', 'id')->all()],
            ['name' => 'patient_id', 'label' => 'Patient', 'type' => 'select', 'required' => true, 'options' => User::role('patient')->orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'doctor_id', 'label' => 'Doctor', 'type' => 'select', 'required' => true, 'options' => User::role('doctor')->orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'clinical_notes', 'label' => 'Clinical notes', 'type' => 'textarea', 'required' => true],
            ['name' => 'diagnosis', 'label' => 'Diagnosis', 'type' => 'textarea', 'required' => true],
            ['name' => 'vitals', 'label' => 'Vitals (JSON)', 'type' => 'textarea', 'required' => false],
        ];
    }
}
