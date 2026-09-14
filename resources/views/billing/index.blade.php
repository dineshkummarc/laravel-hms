@extends('layouts.portal')
@section('title', 'Invoices')
@section('content')
<h1 class="text-2xl font-bold mb-4">Invoices</h1>
<div class="rounded-xl border bg-white overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500"><tr><th class="px-4 py-2">#</th><th class="px-4 py-2">Patient</th><th class="px-4 py-2">Total</th><th class="px-4 py-2">Status</th><th></th></tr></thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $invoice->id }}</td>
                    <td class="px-4 py-2">{{ $invoice->patient->name ?? '—' }}</td>
                    <td class="px-4 py-2">{{ number_format($invoice->total, 2) }}</td>
                    <td class="px-4 py-2">{{ $invoice->status }}</td>
                    <td class="px-4 py-2"><a href="{{ route('billing.invoices.show', $invoice) }}" class="text-indigo-600">Collect</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $invoices->links() }}</div>
@endsection
