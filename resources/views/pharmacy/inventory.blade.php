@extends('layouts.portal')
@section('title', 'Inventory')
@section('content')
<h1 class="text-2xl font-bold mb-4">Medicine inventory</h1>
<div class="rounded-xl border bg-white overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
            <tr><th class="px-4 py-2">Name</th><th class="px-4 py-2">Stock</th><th class="px-4 py-2">Reorder</th><th class="px-4 py-2">Price</th><th class="px-4 py-2">Expiry</th></tr>
        </thead>
        <tbody>
            @foreach($medicines as $m)
                <tr class="border-t {{ $m->isLowStock() ? 'bg-amber-50' : '' }}">
                    <td class="px-4 py-2">{{ $m->name }}</td>
                    <td class="px-4 py-2">{{ $m->stock_qty }} {{ $m->unit }}</td>
                    <td class="px-4 py-2">{{ $m->reorder_level }}</td>
                    <td class="px-4 py-2">{{ number_format($m->unit_price, 2) }}</td>
                    <td class="px-4 py-2">{{ optional($m->expiry_date)->toDateString() ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $medicines->links() }}</div>
@endsection
