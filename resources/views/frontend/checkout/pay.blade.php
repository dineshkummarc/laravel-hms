@extends('layouts.frontend.master')
@section('content')
<div class="max-w-md mx-auto py-12 px-4">
    <h1 class="text-2xl font-bold mb-4">Pay invoice #{{ $invoice->id }}</h1>
    <p class="mb-4">Amount due: <strong>{{ number_format($invoice->remaining_balance, 2) }}</strong></p>
    <form method="POST" action="{{ route('payment.charge', $invoice) }}" class="bg-white border rounded-xl p-6 space-y-3">
        @csrf
        <select name="payment_method" class="w-full border rounded p-2">
            <option value="card">Card</option>
            <option value="mobile_banking">Mobile banking</option>
            <option value="cash">Cash at counter</option>
        </select>
        <input name="transaction_reference" placeholder="Reference (optional)" class="w-full border rounded p-2">
        <button class="w-full bg-green-600 text-white py-2 rounded">Pay now</button>
    </form>
</div>
@endsection
