@extends('layouts.portal')
@section('title', 'Lab queue')
@section('content')
<h1 class="text-2xl font-bold mb-4">Diagnostic queue</h1>
<div class="space-y-4">
    @forelse($pendingReports as $report)
        <div class="rounded-xl border bg-white p-4">
            <div class="flex justify-between">
                <div>
                    <div class="font-semibold">{{ $report->labTest->name ?? 'Test' }}</div>
                    <div class="text-sm text-slate-500">{{ $report->patient->name ?? 'Patient' }} · {{ $report->status }}</div>
                </div>
                @if($report->status === 'requested')
                    <form method="POST" action="{{ route('laboratory.reports.sample', $report) }}">@csrf @method('PATCH')<button class="text-sm text-indigo-600">Collect sample</button></form>
                @endif
            </div>
            @if($report->status !== 'completed')
                <form method="POST" action="{{ route('laboratory.reports.submit', $report) }}" enctype="multipart/form-data" class="mt-3 space-y-2">
                    @csrf @method('PUT')
                    <textarea name="results_text" required placeholder="Result summary" class="w-full border rounded p-2 text-sm"></textarea>
                    <input type="file" name="document" class="text-sm">
                    <button class="bg-indigo-600 text-white text-sm px-3 py-1.5 rounded">Submit results</button>
                </form>
            @else
                <form method="POST" action="{{ route('laboratory.reports.handover', $report) }}" class="mt-2">@csrf @method('PATCH')<button class="text-sm text-emerald-700">Mark handed over</button></form>
            @endif
        </div>
    @empty
        <p class="text-slate-400">Queue is empty.</p>
    @endforelse
</div>
@endsection
