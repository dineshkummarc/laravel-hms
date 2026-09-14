@extends(auth()->user()->hasRole('patient') ? 'layouts.patient' : 'layouts.portal')
@section('title', $message->subject ?: 'Message')
@section('content')
<div class="max-w-2xl rounded-xl border bg-white p-6">
    <h1 class="text-xl font-bold mb-2">{{ $message->subject ?: '(no subject)' }}</h1>
    <p class="text-sm text-slate-500 mb-4">{{ $message->sender->name }} → {{ $message->receiver->name }} · {{ $message->created_at->format('M d, Y H:i') }}</p>
    <p class="whitespace-pre-wrap text-sm">{{ $message->body }}</p>
</div>
@endsection
