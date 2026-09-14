@extends('layouts.portal')
@section('title', 'Nurse dashboard')
@section('content')
<h1 class="text-2xl font-bold mb-2">Nursing station</h1>
<p class="text-sm text-slate-500 mb-6">{{ $todayVitals }} vitals recorded today.</p>
<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 rounded-xl border bg-white overflow-hidden">
        <div class="px-4 py-3 font-semibold border-b">Active admissions</div>
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500"><tr><th class="px-4 py-2">Patient</th><th class="px-4 py-2">Bed</th><th class="px-4 py-2">Doctor</th></tr></thead>
            <tbody>
                @forelse($activeAdmissions as $a)
                    <tr class="border-t"><td class="px-4 py-2">{{ $a->patient->name ?? '—' }}</td><td class="px-4 py-2">{{ $a->bed->bed_number ?? '—' }}</td><td class="px-4 py-2">{{ $a->doctor->name ?? '—' }}</td></tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-6 text-center text-slate-400">No inpatients.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="rounded-xl border bg-white p-4">
        <h2 class="font-semibold mb-3">Staff notices</h2>
        @forelse($notices as $n)
            <p class="text-sm mb-2"><span class="font-medium">{{ $n->title }}</span><br><span class="text-slate-500">{{ \Illuminate\Support\Str::limit($n->body, 80) }}</span></p>
        @empty
            <p class="text-sm text-slate-400">No notices.</p>
        @endforelse
    </div>
</div>
@endsection
