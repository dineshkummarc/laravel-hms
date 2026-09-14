@extends('layouts.portal')
@section('title', 'Laboratory')
@section('content')
<h1 class="text-2xl font-bold mb-6">Laboratory</h1>
<div class="grid md:grid-cols-2 gap-4 mb-6">
    <div class="rounded-xl border bg-white p-4"><div class="text-xs text-slate-400">Open queue</div><div class="text-2xl font-bold">{{ $pending }}</div></div>
    <div class="rounded-xl border bg-white p-4"><div class="text-xs text-slate-400">Completed today</div><div class="text-2xl font-bold">{{ $completedToday }}</div></div>
</div>
<a href="{{ route('laboratory.queue.pending') }}" class="inline-flex bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Open processing queue</a>
@endsection
