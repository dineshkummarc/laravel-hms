<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrescriptionCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Prescriptions',
            'resource' => 'admin.prescriptions',
            'items'    => Prescription::with('medicalRecord')->latest()->paginate(20),
            'columns'  => ['medical_record_id', 'instructions', 'created_at'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Prescription',
            'resource' => 'admin.prescriptions',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['medications'] = $this->decodeJson($request->input('medications'));

        Prescription::create($validated);

        return redirect()->route('admin.prescriptions.index')->with('success', 'Prescription created.');
    }

    public function show(Prescription $prescription): View
    {
        $prescription->load('medicalRecord');

        return view('admin.crud.show', [
            'title'    => 'Prescription',
            'resource' => 'admin.prescriptions',
            'item'     => $prescription,
            'columns'  => ['medical_record_id', 'medications', 'instructions'],
        ]);
    }

    public function edit(Prescription $prescription): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Prescription',
            'resource' => 'admin.prescriptions',
            'item'     => $prescription,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, Prescription $prescription): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['medications'] = $this->decodeJson($request->input('medications'));

        $prescription->update($validated);

        return redirect()->route('admin.prescriptions.index')->with('success', 'Prescription updated.');
    }

    public function destroy(Prescription $prescription): RedirectResponse
    {
        $prescription->delete();

        return redirect()->route('admin.prescriptions.index')->with('success', 'Prescription deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'medications'       => 'nullable|string',
            'instructions'      => 'nullable|string',
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
            ['name' => 'medical_record_id', 'label' => 'Medical record', 'type' => 'select', 'required' => true, 'options' => MedicalRecord::orderByDesc('id')->pluck('id', 'id')->all()],
            ['name' => 'medications', 'label' => 'Medications (JSON)', 'type' => 'textarea', 'required' => false],
            ['name' => 'instructions', 'label' => 'Instructions', 'type' => 'textarea', 'required' => false],
        ];
    }
}
