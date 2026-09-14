@extends('layouts.patient')

@section('title', 'My Diagnostic Lab Reports')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">My Lab Reports History</h1>
    <p class="text-sm text-gray-500">Access and securely download processed historical diagnostic files.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <th class="px-6 py-4">Test Name</th>
                    <th class="px-6 py-4">Department</th>
                    <th class="px-6 py-4">Prescribing Doctor</th>
                    <th class="px-6 py-4">Date Issued</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($reports as $report)
                    <tr class="hover:bg-gray-50 transition text-gray-600">
                        <td class="px-6 py-4 font-bold text-gray-900">
                            {{ $report->labTest->name ?? 'Diagnostic Test' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $report->labTest->department->name ?? 'Pathology Center' }}
                        </td>
                        <td class="px-6 py-4">
                            Dr. {{ $report->doctor->name ?? 'Staff Attendant' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $report->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($report->status === 'completed')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Completed</span>
                            @elseif($report->status === 'processing')
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">In Lab</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($report->status === 'completed' && $report->document_path)
                                <a href="{{ route('patient.labs.download', $report) }}" class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded font-medium hover:bg-blue-700 transition shadow-sm inline-flex items-center gap-1">
                                    📥 Download PDF
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">Processing</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-400">
                            No processing data logs or archived lab documentation found on file.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($reports->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            {{ $reports->links() }}
        </div>
    @endif
</div>
@endsection