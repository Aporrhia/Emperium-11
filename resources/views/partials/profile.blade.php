@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Your Profile</h1>
    <div class="bg-white shadow rounded-lg p-6">
        <p class="text-gray-700">Welcome, {{ Auth::user()->name }}!</p>
    </div>
</div>
@endsection