@extends('layouts.portal')

@section('title', $title)

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-800">{{ $title }}</h1>
    <a href="{{ route($resource.'.create') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Add new</a>
</div>

<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
            <tr>
                @foreach($columns as $column)
                    <th class="px-4 py-3 font-semibold">{{ str_replace('_', ' ', \Illuminate\Support\Str::afterLast($column, '.')) }}</th>
                @endforeach
                <th class="px-4 py-3 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($items as $row)
                <tr class="hover:bg-slate-50">
                    @foreach($columns as $column)
                        @php
                            $value = data_get($row, $column);
                            if ($value instanceof \Illuminate\Support\Collection) {
                                $value = $value->pluck('name')->filter()->implode(', ');
                            } elseif ($value instanceof \Carbon\CarbonInterface) {
                                $value = $value->format('Y-m-d H:i');
                            } elseif (is_bool($value)) {
                                $value = $value ? 'Yes' : 'No';
                            } elseif (is_array($value) || $value instanceof \ArrayObject) {
                                $value = json_encode($value);
                            }
                        @endphp
                        <td class="px-4 py-3 text-slate-700">{{ $value ?? '—' }}</td>
                    @endforeach
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route($resource.'.show', $row) }}" class="text-indigo-600 hover:underline">View</a>
                            <a href="{{ route($resource.'.edit', $row) }}" class="text-slate-600 hover:underline">Edit</a>
                            <form action="{{ route($resource.'.destroy', $row) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) + 1 }}" class="px-4 py-8 text-center text-slate-400">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($items, 'links'))
    <div class="mt-4">{{ $items->links() }}</div>
@endif
@endsection
