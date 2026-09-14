@extends('layouts.patient')
@section('title', 'Medical records')
@section('content')
<h1 class="text-2xl font-bold mb-4">Medical records</h1>
<div class="space-y-3">
    @forelse($records as $record)
        <div class="bg-white border rounded-xl p-4">
            <div class="font-semibold">{{ $record->created_at->format('M d, Y') }} — {{ $record->doctor->name ?? 'Doctor' }}</div>
            <p class="text-sm text-blue-700">{{ $record->diagnosis }}</p>
            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $record->clinical_notes }}</p>
        </div>
    @empty
        <p class="text-gray-400">No records yet.</p>
    @endforelse
</div>
<div class="mt-4">{{ $records->links() }}</div>
@endsection
