@extends('layouts.portal')
@section('title', 'My schedule')
@section('content')
<h1 class="text-2xl font-bold mb-4">Weekly schedule</h1>
<div class="rounded-xl border bg-white overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500"><tr><th class="px-4 py-2">Day</th><th class="px-4 py-2">Hours</th><th class="px-4 py-2">Slot</th><th class="px-4 py-2">Open</th></tr></thead>
        <tbody>
            @forelse($schedules as $s)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $s->dayName() }}</td>
                    <td class="px-4 py-2">{{ substr($s->start_time,0,5) }} – {{ substr($s->end_time,0,5) }}</td>
                    <td class="px-4 py-2">{{ $s->slot_duration }} min</td>
                    <td class="px-4 py-2">{{ $s->is_available ? 'Yes' : 'No' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400">No schedule rows yet. Ask admin to publish clinic hours.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
