@extends(auth()->user()->hasRole('patient') ? 'layouts.patient' : 'layouts.portal')

@section('title', 'Profile')

@section('content')
<div class="max-w-xl">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">My profile</h1>
    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium mb-1">Name</label>
            <input name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">New password</label>
            <input type="password" name="password" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Confirm password</label>
            <input type="password" name="password_confirmation" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
        </div>
        <button class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Save changes</button>
    </form>
</div>
@endsection
