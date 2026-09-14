@extends(auth()->user()->hasRole('patient') ? 'layouts.patient' : 'layouts.portal')
@section('title', 'Messages')
@section('content')
<h1 class="text-2xl font-bold mb-4">Internal messages</h1>
<div class="grid lg:grid-cols-2 gap-6">
    <form method="POST" action="{{ route('messages.store') }}" class="rounded-xl border bg-white p-5 space-y-3 text-sm">
        @csrf
        <select name="receiver_id" required class="w-full border rounded p-2">
            @foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>@endforeach
        </select>
        <input name="subject" placeholder="Subject" class="w-full border rounded p-2">
        <textarea name="body" required rows="4" class="w-full border rounded p-2"></textarea>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded">Send</button>
    </form>
    <div class="space-y-3">
        <h2 class="font-semibold">Inbox</h2>
        @forelse($inbox as $msg)
            <a href="{{ route('messages.show', $msg) }}" class="block rounded-xl border bg-white p-3 text-sm {{ $msg->isUnread() ? 'border-indigo-300' : '' }}">
                <div class="font-medium">{{ $msg->subject ?: '(no subject)' }}</div>
                <div class="text-slate-500">From {{ $msg->sender->name ?? '—' }}</div>
            </a>
        @empty
            <p class="text-slate-400 text-sm">Inbox empty.</p>
        @endforelse
        {{ $inbox->links() }}
    </div>
</div>
@endsection
