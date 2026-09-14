<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Invoices',
            'resource' => 'admin.invoices',
            'items'    => Invoice::with('patient')->latest()->paginate(20),
            'columns'  => ['patient.name', 'subtotal', 'total', 'status'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Invoice',
            'resource' => 'admin.invoices',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Invoice::create($this->payload($request));

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice created.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load('patient');

        return view('admin.crud.show', [
            'title'    => 'Invoice',
            'resource' => 'admin.invoices',
            'item'     => $invoice,
            'columns'  => ['patient.name', 'subtotal', 'tax', 'discount', 'total', 'status', 'line_items'],
        ]);
    }

    public function edit(Invoice $invoice): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Invoice',
            'resource' => 'admin.invoices',
            'item'     => $invoice,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $invoice->update($this->payload($request));

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice updated.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted.');
    }

    private function payload(Request $request): array
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'subtotal'   => 'required|numeric|min:0',
            'tax'        => 'nullable|numeric|min:0',
            'discount'   => 'nullable|numeric|min:0',
            'total'      => 'nullable|numeric|min:0',
            'status'     => 'required|in:unpaid,partial,paid',
            'line_items' => 'nullable|string',
        ]);

        $validated['tax'] = $validated['tax'] ?? 0;
        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['total'] = $validated['total'] ?? max(0, $validated['subtotal'] + $validated['tax'] - $validated['discount']);
        $validated['line_items'] = $this->decodeJson($request->input('line_items'));

        return $validated;
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
            ['name' => 'subtotal', 'label' => 'Subtotal', 'type' => 'number', 'required' => true],
            ['name' => 'tax', 'label' => 'Tax', 'type' => 'number', 'required' => false],
            ['name' => 'discount', 'label' => 'Discount', 'type' => 'number', 'required' => false],
            ['name' => 'total', 'label' => 'Total', 'type' => 'number', 'required' => false],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['unpaid' => 'Unpaid', 'partial' => 'Partial', 'paid' => 'Paid']],
            ['name' => 'line_items', 'label' => 'Line items (JSON)', 'type' => 'textarea', 'required' => false],
        ];
    }
}
