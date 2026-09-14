@extends('layouts.patient')
@section('title', 'Feedback')
@section('content')
<h1 class="text-2xl font-bold mb-4">Share feedback</h1>
<form method="POST" action="{{ route('patient.feedback.store') }}" class="max-w-xl bg-white border rounded-xl p-6 space-y-3 mb-8">
    @csrf
    <select name="doctor_id" class="w-full border rounded p-2 text-sm">
        <option value="">Hospital in general</option>
        @foreach($doctors as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
    </select>
    <select name="category" class="w-full border rounded p-2 text-sm">
        <option value="general">General</option>
        <option value="doctor">Doctor</option>
        <option value="nursing">Nursing</option>
        <option value="facility">Facility</option>
        <option value="billing">Billing</option>
    </select>
    <input type="number" min="1" max="5" name="rating" value="5" required class="w-full border rounded p-2 text-sm">
    <textarea name="comment" placeholder="Comments" class="w-full border rounded p-2 text-sm"></textarea>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Submit</button>
</form>
@foreach($feedback as $item)
    <div class="bg-white border rounded-xl p-4 mb-2 text-sm">{{ $item->rating }}/5 · {{ $item->category }} · {{ $item->comment }}</div>
@endforeach
@endsection
