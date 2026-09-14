@extends('layouts.portal')
@section('title', 'Dispense')
@section('content')
<div class="max-w-xl">
    <h1 class="text-2xl font-bold mb-4">Dispense medicine</h1>
    <form method="POST" action="{{ route('pharmacy.dispense.store') }}" class="rounded-xl border bg-white p-6 space-y-4">
        @csrf
        <div>
            <label class="text-sm font-medium">Patient</label>
            <select name="patient_id" required class="mt-1 w-full border rounded-lg p-2 text-sm">
                @foreach($patients as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium">Medicine</label>
            <select name="medicine_id" required class="mt-1 w-full border rounded-lg p-2 text-sm">
                @foreach($medicines as $m)<option value="{{ $m->id }}">{{ $m->name }} ({{ $m->stock_qty }} in stock)</option>@endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium">Linked prescription (optional)</label>
            <select name="prescription_id" class="mt-1 w-full border rounded-lg p-2 text-sm">
                <option value="">None</option>
                @foreach($prescriptions as $rx)
                    <option value="{{ $rx->id }}">#{{ $rx->id }} — {{ $rx->medicalRecord->patient->name ?? 'Patient' }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-medium">Quantity</label>
            <input type="number" min="1" name="quantity" value="1" required class="mt-1 w-full border rounded-lg p-2 text-sm">
        </div>
        <div>
            <label class="text-sm font-medium">Notes</label>
            <textarea name="notes" class="mt-1 w-full border rounded-lg p-2 text-sm"></textarea>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">Dispense</button>
    </form>
</div>
@endsection
