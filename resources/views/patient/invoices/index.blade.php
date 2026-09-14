@extends('layouts.patient')
@section('title', 'Accounting Records')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Financial Accounting Ledger</h1>
    <p class="text-sm text-gray-500">Review outstanding fees, service billing accounts, and transaction receipts.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
                <th class="px-6 py-4">Statement Reference ID</th>
                <th class="px-6 py-4">Description Context</th>
                <th class="px-6 py-4">Date Generated</th>
                <th class="px-6 py-4">Total Amount</th>
                <th class="px-6 py-4 text-right">Payment Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm">
            @forelse($invoices as $inv)
                <tr class="hover:bg-gray-50/50 transition text-gray-600">
                    <td class="px-6 py-4 font-mono font-bold text-gray-900 tracking-wide">{{ $inv->invoice_number }}</td>
                    <td class="px-6 py-4">{{ $inv->description }}</td>
                    <td class="px-6 py-4">{{ $inv->created_at->format('M d, Y - h:i A') }}</td>
                    <td class="px-6 py-4 font-bold text-gray-900">${{ number_format($inv->amount, 2) }}</td>
                    <td class="px-6 py-4 text-right">
                        <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-full uppercase
                            {{ $inv->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $inv->status }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-12 text-gray-400">No balance sheets or invoices registered.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t bg-gray-50">{{ $invoices->links() }}</div>
</div>
@endsection