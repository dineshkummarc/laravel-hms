@extends('layouts.patient')

@section('title', 'Patient Dashboard Overview')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Patient Dashboard Overview</h1>
    <p class="text-sm text-gray-500">Track your active medical appointments and medical analysis files here.</p>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-4 border-b pb-3">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <span>📅</span> Upcoming Appointments
            </h2>
            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Next 3 Scheduled</span>
        </div>

        <div class="space-y-4">
            @forelse($upcomingAppointments as $appointment)
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-100 flex justify-between items-center">
                    <div>
                        <h4 class="font-bold text-gray-800">Dr. {{ $appointment->doctor->name ?? 'Assigned Medical Specialist' }}</h4>
                        <p class="text-xs text-blue-600 font-medium mb-1">
                            {{ $appointment->department->name ?? 'General Outpatient' }}
                        </p>
                        <p class="text-sm text-gray-500 flex items-center gap-1">
                            <span>⏰</span> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y \a\t h:i A') }}
                        </p>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full uppercase">
                        {{ $appointment->status }}
                    </span>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400 text-sm">
                    <p>No upcoming confirmed appointments scheduled.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-4 border-b pb-3">
            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <span>🧪</span> Recent Lab Records
            </h2>
            <a href="{{ route('patient.labs') }}" class="text-xs text-blue-600 hover:underline font-semibold">View All</a>
        </div>

        <div class="space-y-3">
            @forelse($recentLabs as $report)
                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 flex justify-between items-center text-sm">
                    <div>
                        <p class="font-bold text-gray-800">{{ $report->labTest->name ?? 'Diagnostic Procedure' }}</p>
                        <p class="text-xs text-gray-400">Issued: {{ $report->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        @if($report->status === 'completed' && $report->document_path)
                            <a href="{{ route('patient.labs.download', $report) }}" class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded font-medium hover:bg-blue-700 transition shadow-sm inline-flex items-center gap-1">
                                <span>📥</span> Download PDF
                            </a>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full uppercase">
                                {{ $report->status }}
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400 text-sm">
                    <p>No lab investigation logs found on your account profile.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

@isset($notices)
    @if($notices->isNotEmpty())
        <div class="mt-6 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h2 class="text-lg font-bold text-gray-800 mb-3">Patient notices</h2>
            @foreach($notices as $notice)
                <p class="text-sm text-gray-700 mb-2"><span class="font-semibold">{{ $notice->title }}</span> — {{ \Illuminate\Support\Str::limit($notice->body, 140) }}</p>
            @endforeach
        </div>
    @endif
@endisset
@endsection