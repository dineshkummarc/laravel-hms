@extends('layouts.portal')
@section('title', 'Doctor dashboard')
@section('content')
<h1 class="text-2xl font-bold mb-4">Today's clinic</h1>
<div class="rounded-xl border bg-white overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500"><tr><th class="px-4 py-2">Time</th><th class="px-4 py-2">Patient</th><th class="px-4 py-2">Status</th><th></th></tr></thead>
        <tbody>
            @forelse($todayAppointments as $a)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ optional($a->appointment_date)->format('H:i') }}</td>
                    <td class="px-4 py-2">{{ $a->patient->name ?? $a->guest_name }}</td>
                    <td class="px-4 py-2">{{ $a->status }}</td>
                    <td class="px-4 py-2"><a class="text-indigo-600" href="{{ route('doctor.appointments.show', $a) }}">Open chart</a></td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-slate-400">No appointments today.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
