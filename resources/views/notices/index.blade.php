@extends(auth()->user()->hasRole('patient') ? 'layouts.patient' : 'layouts.portal')
@section('title', 'Notices')
@section('content')
<h1 class="text-2xl font-bold mb-4">Notice board</h1>
<div class="space-y-3">
    @forelse($notices as $notice)
        <article class="rounded-xl border bg-white p-5">
            <div class="text-xs uppercase text-slate-400">{{ $notice->priority }} · {{ $notice->audience }}</div>
            <h2 class="font-semibold text-lg">{{ $notice->title }}</h2>
            <p class="text-sm text-slate-600 whitespace-pre-wrap">{{ $notice->body }}</p>
        </article>
    @empty
        <p class="text-slate-400">No published notices.</p>
    @endforelse
</div>
<div class="mt-4">{{ $notices->links() }}</div>
@endsection
