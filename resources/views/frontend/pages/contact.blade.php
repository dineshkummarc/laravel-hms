@extends('layouts.frontend.master')
@section('content')
<div class="max-w-lg bg-white p-8 shadow rounded mx-auto my-10">
    <h1 class="text-2xl font-bold mb-4">Contact Us</h1>
    <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Your Name" required class="w-full border p-2 rounded">
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Your Email" required class="w-full border p-2 rounded">
        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone" class="w-full border p-2 rounded">
        <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Subject" class="w-full border p-2 rounded">
        <textarea name="message" placeholder="Your Message" required class="w-full border p-2 rounded">{{ old('message') }}</textarea>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Send Message</button>
    </form>
</div>
@endsection
