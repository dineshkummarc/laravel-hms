@extends('layouts.portal')
@section('title', 'Lab invoice')
@section('content')
<div class="max-w-md">
    <h1 class="text-2xl font-bold mb-4">Create lab invoice</h1>
    <form method="POST" action="{{ route('billing.labs.invoice.create') }}" class="rounded-xl border bg-white p-6 space-y-3">
        @csrf
        <select name="patient_id" required class="w-full border rounded p-2 text-sm">
            @foreach($patients as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
        </select>
        <select name="lab_test_id" required class="w-full border rounded p-2 text-sm">
            @foreach($tests as $t)<option value="{{ $t->id }}">{{ $t->name }} ({{ number_format($t->cost, 2) }})</option>@endforeach
        </select>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Create</button>
    </form>
</div>
@endsection
