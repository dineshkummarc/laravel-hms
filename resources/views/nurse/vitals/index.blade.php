@extends('layouts.portal')
@section('title', 'Vitals')
@section('content')
<h1 class="text-2xl font-bold mb-4">Patient vitals</h1>
<div class="grid lg:grid-cols-2 gap-6">
    <form method="POST" action="{{ route('nurse.vitals.store') }}" class="rounded-xl border bg-white p-5 space-y-3 text-sm">
        @csrf
        <select name="patient_id" required class="w-full border rounded p-2">
            @foreach($patients as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
        </select>
        <select name="admission_id" class="w-full border rounded p-2">
            <option value="">No admission</option>
            @foreach($admissions as $a)<option value="{{ $a->id }}">{{ $a->patient->name ?? 'Patient' }} (admit #{{ $a->id }})</option>@endforeach
        </select>
        <div class="grid grid-cols-2 gap-2">
            <input name="temperature" placeholder="Temp °C" class="border rounded p-2">
            <input name="pulse" placeholder="Pulse" class="border rounded p-2">
            <input name="respiratory_rate" placeholder="RR" class="border rounded p-2">
            <input name="blood_pressure" placeholder="BP 120/80" class="border rounded p-2">
            <input name="spo2" placeholder="SpO2" class="border rounded p-2">
            <input name="weight_kg" placeholder="Weight kg" class="border rounded p-2">
        </div>
        <textarea name="notes" placeholder="Notes" class="w-full border rounded p-2"></textarea>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded">Record vitals</button>
    </form>
    <div class="rounded-xl border bg-white overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500"><tr><th class="px-3 py-2">Patient</th><th class="px-3 py-2">BP</th><th class="px-3 py-2">Pulse</th><th class="px-3 py-2">Temp</th></tr></thead>
            <tbody>
                @foreach($vitals as $v)
                    <tr class="border-t"><td class="px-3 py-2">{{ $v->patient->name ?? '—' }}</td><td class="px-3 py-2">{{ $v->blood_pressure }}</td><td class="px-3 py-2">{{ $v->pulse }}</td><td class="px-3 py-2">{{ $v->temperature }}</td></tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $vitals->links() }}</div>
    </div>
</div>
@endsection
