@extends('layouts.frontend.master')
@section('content')
<div class="max-w-5xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-6">Departments</h1>
    <div class="grid md:grid-cols-2 gap-4">
        @foreach($departments as $dept)
            <div class="bg-white rounded-xl p-5 border">
                <h2 class="font-semibold text-lg">{{ $dept->name }}</h2>
                <p class="text-sm text-gray-500">{{ $dept->doctors_count }} listed specialists</p>
            </div>
        @endforeach
    </div>
</div>
@endsection
