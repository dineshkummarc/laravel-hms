@extends('layouts.portal')
@section('title', 'My attendance')
@section('content')
<h1 class="text-2xl font-bold mb-4">Attendance log</h1>
<div class="rounded-xl border bg-white overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500"><tr><th class="px-4 py-2">Time</th><th class="px-4 py-2">Type</th><th class="px-4 py-2">Device</th><th class="px-4 py-2">Status</th></tr></thead>
        <tbody>
            @forelse($logs as $log)
                <tr class="border-t"><td class="px-4 py-2">{{ optional($log->punch_time)->format('Y-m-d H:i') }}</td><td class="px-4 py-2">{{ $log->punch_type }}</td><td class="px-4 py-2">{{ $log->device->name ?? '—' }}</td><td class="px-4 py-2">{{ $log->status }}</td></tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400">No punches yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $logs->links() }}</div>
@endsection
