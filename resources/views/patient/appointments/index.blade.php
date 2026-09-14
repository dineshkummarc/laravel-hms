@extends('layouts.patient')
@section('title', 'Consultation Logs')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Historical Appointment Records</h1>
    <p class="text-sm text-gray-500">Track and monitor your historical consultation status values.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
                <th class="px-6 py-4">Practitioner Name</th>
                <th class="px-6 py-4">Department Base</th>
                <th class="px-6 py-4">Schedule Timestamp</th>
                <th class="px-6 py-4">Symptom Summary Context</th>
                <th class="px-6 py-4 text-right">Status Profile</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @forelse($appointments as $apt)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $apt->doctor->name ?? 'Staff Attendant' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $apt->department->name ?? '—' }}</td>
                    <td class="px-6 py-4 font-medium text-gray-700">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y - h:i A') }}</td>
                    <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $apt->reason }}</td>
                    <td class="px-6 py-4 text-right">
                        <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full uppercase
                            {{ $apt->status === 'confirmed' ? 'bg-green-100 text-green-800' : ($apt->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $apt->status }}
                        </span>
                        @if(in_array($apt->status, ['pending', 'confirmed']))
                            <form method="POST" action="{{ route('patient.appointments.cancel', $apt) }}" class="inline">
                                @csrf @method('PATCH')
                                <button class="ml-2 text-xs text-red-600">Cancel</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-12 text-gray-400">No appointments recorded.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t bg-gray-50">{{ $appointments->links() }}</div>
</div>
@endsection