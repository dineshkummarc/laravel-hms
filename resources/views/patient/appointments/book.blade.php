@extends('layouts.patient')
@section('title', 'Schedule Consultation Slot')
@section('content')
<div class="max-w-2xl bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
    <h2 class="text-xl font-bold text-gray-800 mb-2">Request Consultation Slot</h2>
    <p class="text-sm text-gray-500 mb-6">Select clinical parameters below to queue an appointment assignment with our triage specialists.</p>

    <form action="{{ route('patient.appointments.book.store') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-gray-700">Target Clinic/Department</label>
            <select name="department_id" required class="w-full mt-1.5 border border-gray-300 rounded-lg p-2.5 bg-white shadow-sm focus:ring-2 focus:ring-blue-500">
                <option value="" selected disabled>Select Specialized Field</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Medical Professional / Practitioner</label>
            <select name="doctor_id" required class="w-full mt-1.5 border border-gray-300 rounded-lg p-2.5 bg-white shadow-sm focus:ring-2 focus:ring-blue-500">
                <option value="" selected disabled>Select Practitioner</option>
                @foreach($doctors as $doc)
                    <option value="{{ $doc->id }}">{{ $doc->name }} ({{ $doc->doctorProfile->specialty ?? 'Specialist' }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Preferred Date & Execution Time Window</label>
            <input type="datetime-local" name="appointment_date" required min="{{ now()->addHours(1)->format('Y-m-d\TH:i') }}"
                class="w-full mt-1.5 border border-gray-300 rounded-lg p-2.5 shadow-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700">Consultation Objective / Chief Symptoms</label>
            <textarea name="reason" rows="4" required placeholder="Describe present historical context or symptoms briefly..."
                class="w-full mt-1.5 border border-gray-300 rounded-lg p-2.5 shadow-sm focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-sm">
            Transmit Request to Triage
        </button>
    </form>
</div>
@endsection