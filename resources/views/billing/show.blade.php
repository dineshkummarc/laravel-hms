@extends('layouts.portal')
@section('title', 'Invoice')
@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold mb-2">Invoice #{{ $invoice->id }}</h1>
    <p class="text-sm text-slate-500 mb-4">{{ $invoice->patient->name ?? 'Patient' }} · {{ $invoice->status }} · remaining {{ number_format($invoice->remaining_balance, 2) }}</p>
    <div class="rounded-xl border bg-white p-4 mb-4 text-sm">
        @foreach(($invoice->line_items ?? []) as $line)
            <div class="flex justify-between py-1 border-b"><span>{{ $line['test_name'] ?? $line['name'] ?? 'Item' }}</span><span>{{ number_format($line['cost'] ?? 0, 2) }}</span></div>
        @endforeach
        <div class="flex justify-between pt-2 font-semibold"><span>Total</span><span>{{ number_format($invoice->total, 2) }}</span></div>
    </div>
    @if($invoice->status !== 'paid')
    <form method="POST" action="{{ route('billing.invoices.payments.process', $invoice) }}" class="rounded-xl border bg-white p-4 space-y-3">
        @csrf
        <input type="number" step="0.01" name="amount" value="{{ $invoice->remaining_balance }}" class="w-full border rounded p-2 text-sm" required>
        <select name="payment_method" class="w-full border rounded p-2 text-sm">
            <option value="cash">Cash</option>
            <option value="card">Card</option>
            <option value="mobile_banking">Mobile banking</option>
            <option value="insurance">Insurance</option>
        </select>
        <input name="transaction_reference" placeholder="Reference" class="w-full border rounded p-2 text-sm">
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Record payment</button>
    </form>
    @endif
</div>
@endsection
