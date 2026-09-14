@extends('layouts.portal')
@section('title', 'Billing')
@section('content')
<h1 class="text-2xl font-bold mb-6">Cashier desk</h1>
<div class="grid md:grid-cols-3 gap-4 mb-6">
    <div class="rounded-xl border bg-white p-4"><div class="text-xs text-slate-400">Open invoices</div><div class="text-2xl font-bold">{{ $unpaid }}</div></div>
    <div class="rounded-xl border bg-white p-4"><div class="text-xs text-slate-400">Collected today</div><div class="text-2xl font-bold">{{ number_format($today, 2) }}</div></div>
    <a href="{{ route('billing.labs.invoice.form') }}" class="rounded-xl border bg-indigo-600 text-white p-4 flex items-center font-semibold">Create lab invoice →</a>
</div>
<div class="rounded-xl border bg-white overflow-hidden">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500"><tr><th class="px-4 py-2">Patient</th><th class="px-4 py-2">Total</th><th class="px-4 py-2">Status</th><th></th></tr></thead>
        <tbody>
            @foreach($recent as $inv)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $inv->patient->name ?? '—' }}</td>
                    <td class="px-4 py-2">{{ number_format($inv->total, 2) }}</td>
                    <td class="px-4 py-2">{{ $inv->status }}</td>
                    <td class="px-4 py-2"><a class="text-indigo-600" href="{{ route('billing.invoices.show', $inv) }}">Open</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
