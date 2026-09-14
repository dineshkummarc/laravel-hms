@extends('layouts.frontend.master')
@section('content')
<div class="max-w-3xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-4">Lab cart</h1>
    @if(empty($cart))
        <p class="text-gray-500">Cart is empty. <a href="{{ route('lab.tests') }}" class="text-blue-600">Browse tests</a></p>
    @else
        <div class="bg-white border rounded-xl overflow-hidden mb-4">
            @foreach($cart as $id => $item)
                <div class="flex justify-between items-center p-4 border-b">
                    <div>
                        <div class="font-semibold">{{ $item['name'] }}</div>
                        <div class="text-sm text-gray-500">{{ $item['department'] ?? '' }}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span>{{ number_format($item['cost'], 2) }}</span>
                        <form method="POST" action="{{ route('cart.remove', $id) }}">@csrf @method('DELETE')<button class="text-red-600 text-sm">Remove</button></form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-between items-center">
            <form method="POST" action="{{ route('cart.clear') }}">@csrf<button class="text-sm text-gray-500">Clear cart</button></form>
            <div>
                <span class="font-bold mr-4">Total {{ number_format($total, 2) }}</span>
                <a href="{{ route('checkout.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Checkout</a>
            </div>
        </div>
    @endif
</div>
@endsection
