@extends('layouts.portal')
@section('title', 'Front desk')
@section('content')
<h1 class="text-2xl font-bold mb-6">Reception desk</h1>
<div class="grid lg:grid-cols-2 gap-6">
    <div class="rounded-xl border bg-white p-5">
        <h2 class="font-semibold mb-3">Quick patient registration</h2>
        <form method="POST" action="{{ route('receptionist.patients.quick-reg') }}" class="space-y-2 text-sm">
            @csrf
            <input name="name" placeholder="Full name" required class="w-full border rounded p-2">
            <input type="email" name="email" placeholder="Email" required class="w-full border rounded p-2">
            <input name="phone" placeholder="Phone" required class="w-full border rounded p-2">
            <div class="grid grid-cols-3 gap-2">
                <input type="number" name="age" placeholder="Age" class="border rounded p-2">
                <select name="gender" class="border rounded p-2"><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select>
                <input name="blood_group" placeholder="O+" class="border rounded p-2">
            </div>
            <button class="bg-indigo-600 text-white px-3 py-2 rounded">Register</button>
        </form>
    </div>
    <div class="rounded-xl border bg-white p-5">
        <h2 class="font-semibold mb-3">Book appointment</h2>
        <form method="POST" action="{{ route('receptionist.appointments.book') }}" class="space-y-2 text-sm">
            @csrf
            <select name="patient_id" required class="w-full border rounded p-2">
                @foreach($patients as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
            </select>
            <select name="doctor_id" required class="w-full border rounded p-2">
                @foreach($doctors as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
            </select>
            <input type="datetime-local" name="appointment_date" required class="w-full border rounded p-2">
            <select name="visit_type" class="w-full border rounded p-2">
                <option value="initial">Initial</option>
                <option value="follow_up">Follow up</option>
                <option value="routine_check">Routine</option>
            </select>
            <textarea name="reason" placeholder="Reason" class="w-full border rounded p-2"></textarea>
            <button class="bg-indigo-600 text-white px-3 py-2 rounded">Confirm booking</button>
        </form>
    </div>
    <div class="rounded-xl border bg-white p-5">
        <h2 class="font-semibold mb-3">Admit patient</h2>
        <form method="POST" action="{{ route('receptionist.admissions.admit') }}" class="space-y-2 text-sm">
            @csrf
            <select name="patient_id" required class="w-full border rounded p-2">
                @foreach($patients as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
            </select>
            <select name="doctor_id" required class="w-full border rounded p-2">
                @foreach($doctors as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
            </select>
            <select name="bed_id" required class="w-full border rounded p-2">
                @foreach($availableBeds as $bed)
                    <option value="{{ $bed->id }}">{{ $bed->room->room_number ?? 'Room' }} / {{ $bed->bed_number }}</option>
                @endforeach
            </select>
            <textarea name="reason_for_admission" required placeholder="Reason" class="w-full border rounded p-2"></textarea>
            <button class="bg-indigo-600 text-white px-3 py-2 rounded">Admit</button>
        </form>
    </div>
    <div class="rounded-xl border bg-white overflow-hidden">
        <div class="px-4 py-3 font-semibold border-b">Today's appointments</div>
        <div class="divide-y text-sm">
            @forelse($todayAppointments as $a)
                <div class="px-4 py-2 flex justify-between gap-2">
                    <div>
                        <div class="font-medium">{{ $a->patient->name ?? $a->guest_name }}</div>
                        <div class="text-slate-500">{{ $a->doctor->name ?? '—' }} · {{ optional($a->appointment_date)->format('H:i') }}</div>
                    </div>
                    <form method="POST" action="{{ route('receptionist.appointments.status', $a) }}">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="border rounded text-xs p-1">
                            @foreach(['pending','confirmed','completed','cancelled'] as $st)
                                <option value="{{ $st }}" @selected($a->status === $st)>{{ $st }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @empty
                <p class="p-4 text-slate-400">No appointments today.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
