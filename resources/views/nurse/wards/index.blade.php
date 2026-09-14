@extends('layouts.portal')

@section('title', 'Ward Status')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Active Ward Admissions</h1>
    <p class="text-sm text-slate-500">Patients currently admitted with bed and attending doctor.</p>
</div>

<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
            <tr>
                <th class="px-4 py-3 font-semibold">Patient</th>
                <th class="px-4 py-3 font-semibold">Doctor</th>
                <th class="px-4 py-3 font-semibold">Room</th>
                <th class="px-4 py-3 font-semibold">Bed</th>
                <th class="px-4 py-3 font-semibold">Admitted</th>
                <th class="px-4 py-3 font-semibold">Reason</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($admissions as $admission)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">{{ $admission->patient->name ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $admission->doctor->name ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $admission->bed->room->room_number ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $admission->bed->bed_number ?? '—' }}</td>
                    <td class="px-4 py-3">{{ optional($admission->admission_date)->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-3">{{ $admission->reason_for_admission }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-slate-400">No current admissions.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
