@extends('layouts.portal')
@section('title', 'Pharmacy')
@section('content')
<h1 class="text-2xl font-bold mb-6">Pharmacy</h1>
<div class="grid md:grid-cols-2 gap-6 mb-8">
    <div class="rounded-xl border bg-white p-5">
        <h2 class="font-semibold mb-3">Low stock</h2>
        @forelse($lowStock as $m)
            <p class="text-sm py-1 border-b">{{ $m->name }} — {{ $m->stock_qty }} {{ $m->unit }}</p>
        @empty
            <p class="text-sm text-slate-400">Stock levels are healthy.</p>
        @endforelse
    </div>
    <div class="rounded-xl border bg-white p-5">
        <h2 class="font-semibold mb-3">Expiring in 30 days</h2>
        @forelse($expiring as $m)
            <p class="text-sm py-1 border-b">{{ $m->name }} — {{ optional($m->expiry_date)->toDateString() }}</p>
        @empty
            <p class="text-sm text-slate-400">Nothing expiring soon.</p>
        @endforelse
    </div>
</div>
<div class="rounded-xl border bg-white overflow-hidden">
    <div class="px-4 py-3 font-semibold border-b">Recent dispenses</div>
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500"><tr><th class="px-4 py-2">Patient</th><th class="px-4 py-2">Medicine</th><th class="px-4 py-2">Qty</th><th class="px-4 py-2">Total</th></tr></thead>
        <tbody>
            @forelse($recent as $row)
                <tr class="border-t"><td class="px-4 py-2">{{ $row->patient->name ?? '—' }}</td><td class="px-4 py-2">{{ $row->medicine->name ?? '—' }}</td><td class="px-4 py-2">{{ $row->quantity }}</td><td class="px-4 py-2">{{ number_format($row->total, 2) }}</td></tr>
            @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-slate-400">No dispenses yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
