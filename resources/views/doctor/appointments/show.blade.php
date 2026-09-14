@extends('layouts.portal')
@section('title', 'Consultation')
@section('content')
<div class="max-w-3xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold">{{ $appointment->patient->name ?? $appointment->guest_name }}</h1>
        <p class="text-sm text-slate-500">{{ optional($appointment->appointment_date)->format('M d, Y H:i') }} · {{ $appointment->visit_type }} · {{ $appointment->status }}</p>
        @if($appointment->patient)
            <a class="text-sm text-indigo-600" href="{{ route('doctor.patients.labs.history', $appointment->patient) }}">Lab history</a>
            · <a class="text-sm text-indigo-600" href="{{ route('doctor.appointments.followup', $appointment) }}">Follow-up context</a>
        @endif
    </div>

    @if($appointment->patient && $appointment->patient->patientProfile)
        <div class="rounded-xl border bg-white p-4 text-sm">
            Age {{ $appointment->patient->patientProfile->age }} · {{ $appointment->patient->patientProfile->gender }} · {{ $appointment->patient->patientProfile->blood_group }}
        </div>
    @endif

    @if($appointment->medicalRecord)
        <div class="rounded-xl border bg-white p-4 text-sm">
            <div class="font-semibold mb-1">Diagnosis</div>
            <p>{{ $appointment->medicalRecord->diagnosis }}</p>
            <p class="mt-2 whitespace-pre-wrap">{{ $appointment->medicalRecord->clinical_notes }}</p>
        </div>
    @elseif($appointment->patient)
        <form method="POST" action="{{ route('doctor.records.store', $appointment) }}" class="rounded-xl border bg-white p-5 space-y-3">
            @csrf
            <textarea name="clinical_notes" required placeholder="Clinical notes" class="w-full border rounded p-2 text-sm"></textarea>
            <input name="diagnosis" required placeholder="Diagnosis" class="w-full border rounded p-2 text-sm">
            <div class="grid grid-cols-3 gap-2">
                <input name="vitals[bp]" required placeholder="BP" class="border rounded p-2 text-sm">
                <input name="vitals[pulse]" required placeholder="Pulse" class="border rounded p-2 text-sm">
                <input name="vitals[temp]" required placeholder="Temp" class="border rounded p-2 text-sm">
            </div>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">Save record & close visit</button>
        </form>
    @endif

    @if($appointment->medicalRecord && ! $appointment->medicalRecord->prescription)
        <form method="POST" action="{{ route('doctor.prescriptions.store', $appointment->medicalRecord) }}" class="rounded-xl border bg-white p-5 space-y-2 text-sm">
            @csrf
            @foreach(range(0,2) as $i)
                <div class="grid grid-cols-4 gap-2">
                    <input name="medications[{{ $i }}][drug]" placeholder="Drug" {{ $i === 0 ? 'required' : '' }} class="border rounded p-2">
                    <input name="medications[{{ $i }}][dosage]" placeholder="Dose" {{ $i === 0 ? 'required' : '' }} class="border rounded p-2">
                    <input name="medications[{{ $i }}][frequency]" placeholder="1-0-1" {{ $i === 0 ? 'required' : '' }} class="border rounded p-2">
                    <input name="medications[{{ $i }}][duration]" placeholder="7 days" {{ $i === 0 ? 'required' : '' }} class="border rounded p-2">
                </div>
            @endforeach
            <textarea name="instructions" placeholder="Instructions" class="w-full border rounded p-2"></textarea>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded">Attach prescription</button>
        </form>
    @endif
</div>
@endsection
