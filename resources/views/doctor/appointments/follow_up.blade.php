@extends('layouts.portal')
@section('title', 'Follow-up')
@section('content')
<h1 class="text-2xl font-bold mb-4">Prior notes for {{ $appointment->patient->name ?? 'patient' }}</h1>
<div class="space-y-3">
    @forelse($previousRecords as $record)
        <div class="rounded-xl border bg-white p-4 text-sm">
            <div class="font-semibold">{{ $record->created_at->format('M d, Y') }} — {{ $record->diagnosis }}</div>
            <p class="text-slate-600 whitespace-pre-wrap">{{ $record->clinical_notes }}</p>
        </div>
    @empty
        <p class="text-slate-400">No previous records.</p>
    @endforelse
</div>
@endsection
