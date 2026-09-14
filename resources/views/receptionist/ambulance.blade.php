@extends('layouts.portal')
@section('title', 'Ambulance desk')
@section('content')
<h1 class="text-2xl font-bold mb-4">Ambulance dispatch</h1>
<div class="space-y-4">
    @forelse($requests as $req)
        <div class="rounded-xl border bg-white p-4">
            <div class="flex justify-between gap-4">
                <div>
                    <div class="font-semibold">{{ $req->caller_name }} · {{ $req->caller_phone }}</div>
                    <p class="text-sm text-slate-600">Pickup: {{ $req->pickup_address }}</p>
                    <p class="text-xs uppercase text-slate-400">{{ $req->priority }} · {{ $req->status }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('receptionist.ambulance.dispatch', $req) }}" class="mt-3 grid md:grid-cols-4 gap-2 text-sm">
                @csrf
                <input name="vehicle_code" value="{{ $req->vehicle_code }}" placeholder="Vehicle" class="border rounded p-2">
                <input name="driver_name" value="{{ $req->driver_name }}" placeholder="Driver" class="border rounded p-2">
                <select name="status" class="border rounded p-2">
                    @foreach(['dispatched','en_route','arrived','completed','cancelled'] as $st)
                        <option value="{{ $st }}" @selected($req->status === $st)>{{ $st }}</option>
                    @endforeach
                </select>
                <button class="bg-indigo-600 text-white rounded px-3">Update</button>
            </form>
        </div>
    @empty
        <p class="text-slate-400">No ambulance requests.</p>
    @endforelse
</div>
<div class="mt-4">{{ $requests->links() }}</div>
@endsection
