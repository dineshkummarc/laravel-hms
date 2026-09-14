@extends('layouts.frontend.master')
@section('content')
<div class="max-w-md mx-auto py-16 px-4 text-center">
    @if($ok)
        <h1 class="text-2xl font-bold text-green-700 mb-2">Payment received</h1>
        <p class="text-gray-600 mb-4">Invoice #{{ $invoice->id }} is marked paid. Laboratory can now process the sample.</p>
        <a href="{{ route('patient.invoices.index') }}" class="text-blue-600">View invoices</a>
    @else
        <h1 class="text-2xl font-bold text-red-700 mb-2">Payment failed</h1>
        <p class="text-gray-600">Try again from checkout or pay at the cashier desk.</p>
    @endif
</div>
@endsection
