@extends('layouts.portal')
@section('title', 'Lab history')
@section('content')
<h1 class="text-2xl font-bold mb-4">Labs for {{ $user->name }}</h1>
<div class="rounded-xl border bg-white overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500"><tr><th class="px-4 py-2">Test</th><th class="px-4 py-2">Status</th><th class="px-4 py-2">Date</th></tr></thead>
        <tbody>
            @foreach($labReports as $r)
                <tr class="border-t"><td class="px-4 py-2">{{ $r->labTest->name ?? 'Test' }}</td><td class="px-4 py-2">{{ $r->status }}</td><td class="px-4 py-2">{{ $r->created_at->format('Y-m-d') }}</td></tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $labReports->links() }}</div>
@endsection
