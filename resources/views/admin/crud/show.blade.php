@extends('layouts.portal')

@section('title', $title)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-slate-800">{{ $title }}</h1>
    <div class="flex gap-3">
        <a href="{{ route($resource.'.edit', $item) }}" class="text-sm text-indigo-600 hover:underline">Edit</a>
        <a href="{{ route($resource.'.index') }}" class="text-sm text-slate-600 hover:underline">Back to list</a>
    </div>
</div>

<div class="max-w-3xl overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <dl class="divide-y divide-slate-100">
        @foreach($columns as $column)
            @php
                $value = data_get($item, $column);
                if ($value instanceof \Illuminate\Support\Collection) {
                    $value = $value->pluck('name')->filter()->implode(', ');
                } elseif ($value instanceof \Carbon\CarbonInterface) {
                    $value = $value->format('Y-m-d H:i');
                } elseif (is_bool($value)) {
                    $value = $value ? 'Yes' : 'No';
                } elseif (is_array($value) || $value instanceof \ArrayObject) {
                    $value = json_encode($value, JSON_PRETTY_PRINT);
                }
            @endphp
            <div class="grid grid-cols-3 gap-4 px-5 py-3">
                <dt class="text-sm font-medium text-slate-500">{{ str_replace('_', ' ', \Illuminate\Support\Str::afterLast($column, '.')) }}</dt>
                <dd class="col-span-2 whitespace-pre-wrap text-sm text-slate-800">{{ $value ?? '—' }}</dd>
            </div>
        @endforeach
    </dl>
</div>
@endsection
