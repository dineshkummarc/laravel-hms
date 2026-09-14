@extends('layouts.frontend.master')

@section('content')
<div class="py-8">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-bold text-gray-800">Diagnostics Catalog</h1>
            <p class="text-gray-500 mt-2">Browse and book lab tests from our advanced pathology center.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($labTests as $test)
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-bold text-gray-800 leading-tight">{{ $test->name }}</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                        ${{ number_format($test->cost, 2) }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    {{ $test->department->name }}
                </p>
                <form action="{{ route('cart.add', $test) }}" method="POST">
                    @csrf
                    <button class="w-full bg-gray-50 text-blue-600 border border-blue-200 rounded py-2 font-semibold hover:bg-blue-50 transition">
                        Add to Cart
                    </button>
                </form>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $labTests->links() }}
    </div>
</div>
@endsection