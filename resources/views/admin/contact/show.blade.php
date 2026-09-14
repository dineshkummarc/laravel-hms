@extends('layouts.portal')
@section('title', 'Contact message')
@section('content')
<div class="max-w-3xl space-y-4">
    <h1 class="text-2xl font-bold">{{ $item->subject ?: 'Contact message' }}</h1>
    <div class="rounded-xl border bg-white p-6 text-sm space-y-2">
        <p><strong>From:</strong> {{ $item->name }} ({{ $item->email }})</p>
        <p><strong>Phone:</strong> {{ $item->phone ?: '—' }}</p>
        <p><strong>Status:</strong> {{ $item->status }}</p>
        <p class="whitespace-pre-wrap">{{ $item->message }}</p>
        @if($item->admin_reply)
            <div class="mt-4 rounded-lg bg-slate-50 p-3"><strong>Reply:</strong><div class="whitespace-pre-wrap">{{ $item->admin_reply }}</div></div>
        @endif
    </div>
    <form method="POST" action="{{ route('admin.contact-messages.update', $item) }}" class="rounded-xl border bg-white p-6 space-y-3">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="replied">
        <textarea name="admin_reply" rows="4" class="w-full border rounded-lg p-2 text-sm" placeholder="Internal reply note">{{ $item->admin_reply }}</textarea>
        <button class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">Save reply</button>
    </form>
</div>
@endsection
