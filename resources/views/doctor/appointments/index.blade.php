@extends('layouts.portal')
@section('title', 'Appointments')
@section('content')
<h1 class="text-2xl font-bold mb-4">My appointments</h1>
<div class="rounded-xl border bg-white overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500"><tr><th class="px-4 py-2">When</th><th class="px-4 py-2">Patient</th><th class="px-4 py-2">Type</th><th class="px-4 py-2">Status</th><th></th></tr></thead>
        <tbody>
            @foreach($appointments as $a)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ optional($a->appointment_date)->format('M d, Y H:i') }}</td>
                    <td class="px-4 py-2">{{ $a->patient->name ?? $a->guest_name }}</td>
                    <td class="px-4 py-2">{{ $a->visit_type }}</td>
                    <td class="px-4 py-2">{{ $a->status }}</td>
                    <td class="px-4 py-2"><a class="text-indigo-600" href="{{ route('doctor.appointments.show', $a) }}">Chart</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $appointments->links() }}</div>
@endsection
