@extends('layouts.portal')

@section('title', $heading)

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">{{ $heading }}</h1>
    @isset($subtitle)
        <p class="text-sm text-slate-500">{{ $subtitle }}</p>
    @endisset
</div>

<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 mb-8">
    @foreach($stats as $stat)
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="text-xs uppercase tracking-wide text-slate-400">{{ $stat['label'] }}</div>
            <div class="mt-1 text-2xl font-bold text-slate-800">{{ $stat['value'] }}</div>
        </div>
    @endforeach
</div>

@isset($recent)
<div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b text-sm font-semibold text-slate-700">Recent appointments</div>
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
            <tr>
                <th class="px-4 py-2">Patient</th>
                <th class="px-4 py-2">Doctor</th>
                <th class="px-4 py-2">When</th>
                <th class="px-4 py-2">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($recent as $row)
                <tr>
                    <td class="px-4 py-2">{{ $row->patient->name ?? $row->guest_name ?? '—' }}</td>
                    <td class="px-4 py-2">{{ $row->doctor->name ?? '—' }}</td>
                    <td class="px-4 py-2">{{ optional($row->appointment_date)->format('M d, H:i') }}</td>
                    <td class="px-4 py-2 capitalize">{{ $row->status }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-slate-400">No activity yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endisset
@endsection
