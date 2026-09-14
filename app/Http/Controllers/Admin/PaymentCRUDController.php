<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Payments',
            'resource' => 'admin.payments',
            'items'    => Payment::with('invoice')->latest()->paginate(20),
            'columns'  => ['invoice_id', 'amount', 'payment_method', 'status'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Payment',
            'resource' => 'admin.payments',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Payment::create($this->validated($request));

        return redirect()->route('admin.payments.index')->with('success', 'Payment created.');
    }

    public function show(Payment $payment): View
    {
        $payment->load('invoice');

        return view('admin.crud.show', [
            'title'    => 'Payment',
            'resource' => 'admin.payments',
            'item'     => $payment,
            'columns'  => ['invoice_id', 'amount', 'payment_method', 'transaction_reference', 'status'],
        ]);
    }

    public function edit(Payment $payment): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Payment',
            'resource' => 'admin.payments',
            'item'     => $payment,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $payment->update($this->validated($request));

        return redirect()->route('admin.payments.index')->with('success', 'Payment updated.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'invoice_id'             => 'required|exists:invoices,id',
            'amount'                 => 'required|numeric|min:0',
            'payment_method'         => 'required|in:cash,card,mobile_banking,insurance',
            'transaction_reference'  => 'nullable|string|max:255',
            'status'                 => 'required|in:pending,completed,failed,refunded',
        ]);
    }

    private function fields(): array
    {
        return [
            ['name' => 'invoice_id', 'label' => 'Invoice', 'type' => 'select', 'required' => true, 'options' => Invoice::orderByDesc('id')->pluck('id', 'id')->all()],
            ['name' => 'amount', 'label' => 'Amount', 'type' => 'number', 'required' => true],
            ['name' => 'payment_method', 'label' => 'Payment method', 'type' => 'select', 'required' => true, 'options' => ['cash' => 'Cash', 'card' => 'Card', 'mobile_banking' => 'Mobile banking', 'insurance' => 'Insurance']],
            ['name' => 'transaction_reference', 'label' => 'Transaction reference', 'type' => 'text', 'required' => false],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['pending' => 'Pending', 'completed' => 'Completed', 'failed' => 'Failed', 'refunded' => 'Refunded']],
        ];
    }
}
