@extends('layouts.frontend.master')
@section('content')
<div class="max-w-xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-4">Checkout</h1>
    <div class="bg-white border rounded-xl p-6 mb-4">
        @foreach($cart as $item)
            <div class="flex justify-between py-2 border-b text-sm"><span>{{ $item['name'] }}</span><span>{{ number_format($item['cost'], 2) }}</span></div>
        @endforeach
        <div class="flex justify-between font-bold pt-3"><span>Total</span><span>{{ number_format($total, 2) }}</span></div>
    </div>
    <form method="POST" action="{{ route('checkout.process') }}">
        @csrf
        <button class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold">Create invoice</button>
    </form>
</div>
@endsection
