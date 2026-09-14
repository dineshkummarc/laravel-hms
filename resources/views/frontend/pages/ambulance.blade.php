@extends('layouts.frontend.master')
@section('content')
<div class="max-w-lg bg-white p-8 shadow rounded mx-auto my-10">
    <h1 class="text-2xl font-bold mb-2 text-red-600">Request an ambulance</h1>
    <p class="text-sm text-gray-500 mb-4">For life-threatening emergencies call +880 1700-000000 while this form is submitted.</p>
    <form method="POST" action="{{ route('ambulance.store') }}" class="space-y-3">
        @csrf
        <input name="caller_name" required placeholder="Caller name" class="w-full border p-2 rounded">
        <input name="caller_phone" required placeholder="Phone" class="w-full border p-2 rounded">
        <input name="pickup_address" required placeholder="Pickup address" class="w-full border p-2 rounded">
        <input name="destination" placeholder="Destination" class="w-full border p-2 rounded">
        <select name="priority" class="w-full border p-2 rounded">
            <option value="urgent">Urgent</option>
            <option value="critical">Critical</option>
            <option value="standard">Standard</option>
        </select>
        <textarea name="notes" placeholder="Patient condition" class="w-full border p-2 rounded"></textarea>
        <button class="w-full bg-red-600 text-white py-2 rounded font-semibold">Send dispatch request</button>
    </form>
</div>
@endsection
