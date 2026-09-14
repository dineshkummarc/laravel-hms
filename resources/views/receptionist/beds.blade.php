@extends('layouts.portal')
@section('title', 'Bed map')
@section('content')
<h1 class="text-2xl font-bold mb-4">Bed occupancy</h1>
<div class="grid md:grid-cols-3 gap-4">
    @foreach($beds as $bed)
        @php $admission = $admissions[$bed->id] ?? null; @endphp
        <div class="rounded-xl border bg-white p-4 {{ $bed->status === 'occupied' ? 'border-rose-200' : ($bed->status === 'maintenance' ? 'border-amber-200' : 'border-emerald-200') }}">
            <div class="font-semibold">{{ $bed->room->room_number ?? 'Room' }} / {{ $bed->bed_number }}</div>
            <div class="text-xs uppercase text-slate-400 mb-2">{{ $bed->status }}</div>
            @if($admission)
                <p class="text-sm">{{ $admission->patient->name ?? 'Patient' }}</p>
                <form method="POST" action="{{ route('receptionist.admissions.discharge', $admission) }}" class="mt-2">
                    @csrf
                    <button class="text-xs text-indigo-600">Discharge</button>
                </form>
            @endif
        </div>
    @endforeach
</div>
@endsection
