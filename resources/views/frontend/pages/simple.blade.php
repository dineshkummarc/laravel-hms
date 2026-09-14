@extends('layouts.frontend.master')
@section('content')
<div class="max-w-3xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-2">{{ $title }}</h1>
    <p class="text-gray-600 mb-8">{{ $lead }}</p>
    @foreach($sections as $heading => $body)
        <div class="bg-white border rounded-xl p-5 mb-4">
            <h2 class="font-semibold mb-1">{{ $heading }}</h2>
            <p class="text-sm text-gray-600">{{ $body }}</p>
        </div>
    @endforeach
</div>
@endsection
