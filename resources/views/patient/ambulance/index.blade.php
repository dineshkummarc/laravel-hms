@extends('layouts.patient')
@section('title', 'Ambulance')
@section('content')
<h1 class="text-2xl font-bold mb-4">Request an ambulance</h1>
<form method="POST" action="{{ route('patient.ambulance.store') }}" class="max-w-xl bg-white border rounded-xl p-6 space-y-3 mb-8">
    @csrf
    <input name="caller_name" value="{{ auth()->user()->name }}" required class="w-full border rounded p-2 text-sm">
    <input name="caller_phone" required placeholder="Phone" class="w-full border rounded p-2 text-sm">
    <input name="pickup_address" required placeholder="Pickup address" class="w-full border rounded p-2 text-sm">
    <input name="destination" placeholder="Destination (optional)" class="w-full border rounded p-2 text-sm">
    <select name="priority" class="w-full border rounded p-2 text-sm">
        <option value="urgent">Urgent</option>
        <option value="critical">Critical</option>
        <option value="standard">Standard</option>
    </select>
    <textarea name="notes" class="w-full border rounded p-2 text-sm" placeholder="Notes"></textarea>
    <button class="bg-red-600 text-white px-4 py-2 rounded">Request dispatch</button>
</form>
@foreach($requests as $req)
    <div class="bg-white border rounded-xl p-4 mb-2 text-sm">{{ $req->status }} · {{ $req->pickup_address }} · {{ $req->created_at->diffForHumans() }}</div>
@endforeach
@endsection
