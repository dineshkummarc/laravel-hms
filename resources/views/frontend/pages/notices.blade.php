@extends('layouts.frontend.master')
@section('content')
<div class="max-w-3xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-6">Hospital notices</h1>
    @forelse($notices as $notice)
        <article class="bg-white border rounded-xl p-5 mb-4">
            <div class="text-xs uppercase text-rose-500">{{ $notice->priority }}</div>
            <h2 class="font-semibold text-lg">{{ $notice->title }}</h2>
            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $notice->body }}</p>
        </article>
    @empty
        <p class="text-gray-400">No public notices.</p>
    @endforelse
    {{ $notices->links() }}
</div>
@endsection
