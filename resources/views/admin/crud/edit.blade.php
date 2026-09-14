@extends('layouts.portal')

@section('title', 'Edit '.$title)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold text-slate-800">Edit {{ $title }}</h1>
    <a href="{{ route($resource.'.index') }}" class="text-sm text-indigo-600 hover:underline">Back to list</a>
</div>

<form action="{{ route($resource.'.update', $item) }}" method="POST" class="max-w-3xl space-y-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
    @csrf
    @method('PUT')
    @foreach($fields as $field)
        @php
            $value = old($field['name'], data_get($item, $field['name']));
            if ($value instanceof \Carbon\CarbonInterface) {
                $value = ($field['type'] ?? 'text') === 'datetime-local' ? $value->format('Y-m-d\TH:i') : $value->format('Y-m-d');
            } elseif (is_array($value) || $value instanceof \ArrayObject || $value instanceof \Illuminate\Support\Collection) {
                $value = json_encode($value, JSON_PRETTY_PRINT);
            } elseif (is_bool($value)) {
                $value = $value ? '1' : '0';
            }
        @endphp
        <div>
            @if(($field['type'] ?? 'text') === 'checkbox')
                <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="checkbox" name="{{ $field['name'] }}" value="1" class="rounded border-slate-300 text-indigo-600" @checked(old($field['name'], data_get($item, $field['name'])))>
                    {{ $field['label'] }}
                </label>
            @else
                <label class="mb-1 block text-sm font-medium text-slate-700">
                    {{ $field['label'] }}
                    @if(!empty($field['required']))<span class="text-red-500">*</span>@endif
                </label>
                @if(($field['type'] ?? 'text') === 'textarea')
                    <textarea name="{{ $field['name'] }}" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" @required(!empty($field['required']))>{{ $value }}</textarea>
                @elseif(($field['type'] ?? 'text') === 'select')
                    <select name="{{ $field['name'] }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" @required(!empty($field['required']))>
                        <option value="">Select {{ strtolower($field['label']) }}</option>
                        @foreach(($field['options'] ?? []) as $optionValue => $optionLabel)
                            <option value="{{ $optionValue }}" @selected((string) old($field['name'], data_get($item, $field['name'])) === (string) $optionValue)>{{ $optionLabel }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="{{ $field['type'] ?? 'text' }}" name="{{ $field['name'] }}" value="{{ is_object($value) ? '' : $value }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" @required(!empty($field['required']) && ($field['type'] ?? '') !== 'password')>
                @endif
            @endif
        </div>
    @endforeach
    <div class="pt-2">
        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Update</button>
    </div>
</form>
@endsection
