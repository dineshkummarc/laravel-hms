<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\LabReport;
use App\Models\LabTest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LabReportCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Lab reports',
            'resource' => 'admin.lab-reports',
            'items'    => LabReport::with(['patient', 'labTest'])->latest()->paginate(20),
            'columns'  => ['patient.name', 'labTest.name', 'status', 'handed_over_at'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Lab report',
            'resource' => 'admin.lab-reports',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['results'] = $this->decodeJson($request->input('results'));

        LabReport::create($validated);

        return redirect()->route('admin.lab-reports.index')->with('success', 'Lab report created.');
    }

    public function show(LabReport $lab_report): View
    {
        $lab_report->load(['patient', 'labTest', 'invoice', 'handler']);

        return view('admin.crud.show', [
            'title'    => 'Lab report',
            'resource' => 'admin.lab-reports',
            'item'     => $lab_report,
            'columns'  => ['patient.name', 'labTest.name', 'invoice_id', 'status', 'results', 'document_path', 'handed_over_at', 'handed_over_by'],
        ]);
    }

    public function edit(LabReport $lab_report): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Lab report',
            'resource' => 'admin.lab-reports',
            'item'     => $lab_report,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, LabReport $lab_report): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['results'] = $this->decodeJson($request->input('results'));

        $lab_report->update($validated);

        return redirect()->route('admin.lab-reports.index')->with('success', 'Lab report updated.');
    }

    public function destroy(LabReport $lab_report): RedirectResponse
    {
        $lab_report->delete();

        return redirect()->route('admin.lab-reports.index')->with('success', 'Lab report deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'patient_id'     => 'required|exists:users,id',
            'doctor_id'      => 'required|exists:users,id',
            'invoice_id'     => 'nullable|exists:invoices,id',
            'lab_test_id'    => 'required|exists:lab_tests,id',
            'status'         => 'required|in:requested,sample_collected,analyzing,completed',
            'results'        => 'nullable|string',
            'document_path'  => 'nullable|string|max:255',
            'handed_over_at' => 'nullable|date',
            'handed_over_by' => 'nullable|exists:users,id',
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
            ['name' => 'patient_id', 'label' => 'Patient', 'type' => 'select', 'required' => true, 'options' => User::role('patient')->orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'doctor_id', 'label' => 'Doctor', 'type' => 'select', 'required' => true, 'options' => User::role('doctor')->orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'invoice_id', 'label' => 'Invoice', 'type' => 'select', 'required' => false, 'options' => Invoice::orderByDesc('id')->pluck('id', 'id')->all()],
            ['name' => 'lab_test_id', 'label' => 'Lab test', 'type' => 'select', 'required' => true, 'options' => LabTest::orderBy('name')->pluck('name', 'id')->all()],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['requested' => 'Requested', 'sample_collected' => 'Sample collected', 'analyzing' => 'Analyzing', 'completed' => 'Completed']],
            ['name' => 'results', 'label' => 'Results (JSON)', 'type' => 'textarea', 'required' => false],
            ['name' => 'document_path', 'label' => 'Document path', 'type' => 'text', 'required' => false],
            ['name' => 'handed_over_at', 'label' => 'Handed over at', 'type' => 'datetime-local', 'required' => false],
            ['name' => 'handed_over_by', 'label' => 'Handed over by', 'type' => 'select', 'required' => false, 'options' => User::orderBy('name')->pluck('name', 'id')->all()],
        ];
    }
}
