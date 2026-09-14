@extends('layouts.patient')
@section('title', 'Procure Diagnostics')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Procure Diagnostic Services</h1>
    <p class="text-sm text-gray-500">Order analytical tests directly. Invoice files will post automatically to your financial matrix.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($labTests as $test)
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between">
            <div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg mb-4 font-bold">🧪</div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $test->name }}</h3>
                <p class="text-xs font-semibold text-blue-600 mb-2 uppercase tracking-wider">{{ $test->department->name ?? 'Clinical Lab' }}</p>
                <p class="text-sm text-gray-500 line-clamp-3 mb-4">{{ $test->description ?? 'Standard laboratory screen process constraint validation asset.' }}</p>
            </div>
            <div class="border-t border-gray-100 pt-4 mt-2">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs text-gray-400 uppercase font-bold">Price</span>
                    <span class="text-xl font-black text-gray-900">${{ number_format($test->cost, 2) }}</span>
                </div>
                <form action="{{ route('patient.labs.buy.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="lab_test_id" value="{{ $test->id }}">
                    <button type="submit" class="w-full bg-gray-900 text-white text-sm py-2.5 rounded-xl font-bold hover:bg-blue-600 transition tracking-wide shadow-sm">
                        Purchase Procedure
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white p-12 text-center text-gray-400 rounded-2xl border">No testing items compiled.</div>
    @endforelse
</div>
@endsection