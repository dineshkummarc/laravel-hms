@extends('layouts.patient')
@section('title', 'Clinical Prescription Logs')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Prescription Archives</h1>
    <p class="text-sm text-gray-500">Access valid prescriptions and active practitioner directives.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
                <th class="px-6 py-4">Prescribing Practitioner</th>
                <th class="px-6 py-4">Consultation Vector</th>
                <th class="px-6 py-4">Date Issued</th>
                <th class="px-6 py-4">Instructions Summary</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @forelse($prescriptions as $prs)
                <tr class="hover:bg-gray-50/50 transition text-gray-600">
                    <td class="px-6 py-4 font-bold text-gray-900">{{ $prs->medicalRecord->doctor->name ?? 'Staff Attendant' }}</td>
                    <td class="px-6 py-4">Record #{{ $prs->medical_record_id }}</td>
                    <td class="px-6 py-4">{{ $prs->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 max-w-sm truncate">{{ $prs->instructions ?? 'Standard usage protocols outlined inside metadata array.' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-12 text-gray-400">No prescription records linked to this account matrix.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t bg-gray-50">{{ $prescriptions->links() }}</div>
</div>
@endsection