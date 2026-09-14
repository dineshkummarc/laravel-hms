@extends('layouts.frontend.master')

@section('content')
<div class="py-8">
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-gray-800">Our Medical Specialists</h1>
        <p class="text-gray-500 mt-2">Book an appointment with our world-class medical professionals.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($doctors as $doctor)
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mb-4 text-blue-500 text-3xl font-bold">
                    {{ substr($doctor->name, 0, 1) }}
                </div>
                <h3 class="text-xl font-bold text-gray-800">{{ $doctor->name }}</h3>
                <p class="text-blue-600 font-medium">{{ $doctor->doctorProfile->specialty ?? 'General Physician' }}</p>
                <p class="text-sm text-gray-500 mt-1 mb-4">{{ $doctor->doctorProfile->department->name ?? 'General' }}</p>
                
                    <form action="{{ route('appointments.store') }}" method="POST" class="w-full mt-auto space-y-2">
                    @csrf
                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                    <input type="date" name="appointment_date" required class="w-full border rounded p-2 text-sm" min="{{ date('Y-m-d') }}">
                    <input type="text" name="patient_name" placeholder="Your Name" required class="w-full border rounded p-2 text-sm">
                    <input type="text" name="patient_phone" placeholder="Phone Number" required class="w-full border rounded p-2 text-sm">
                    <button class="w-full bg-blue-600 text-white rounded py-2 font-semibold hover:bg-blue-700 transition">Book Request</button>
                </form>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $doctors->links() }}
    </div>
</div>
@endsection