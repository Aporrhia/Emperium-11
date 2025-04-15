@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Properties for Rent</h1>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1 bg-white shadow rounded-lg p-4 max-h-[600px] overflow-y-auto">
            @if ($properties->isEmpty())
                <p class="text-gray-600">No properties found.</p>
            @else
                @foreach ($properties as $property)
                    <a href="{{ route('properties', ['id' => $property->id]) }}" class="block p-4 border-b last:border-b-0 hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $property->images ?? 'https://placehold.co/100x100?text=Property' }}" alt="{{ $property->title }}" class="w-16 h-16 object-cover rounded">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $property->title }}</h3>
                                <p class="text-gray-600">{{ $property->location }}</p>
                                <p class="text-gray-900 font-bold">${{ number_format($property->price) }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif

            <div class="mt-4">
                {{ $properties->links() }}
            </div>
        </div>

        <!-- Map -->
        <div class="lg:col-span-3 bg-white shadow rounded-lg p-6">
            @include('partials.map', ['properties' => $properties])

            @if (request()->has('id'))
                @php
                    $selectedProperty = $properties->firstWhere('id', request('id')) ?: (Apartment::find(request('id')) ?? House::find(request('id')));
                @endphp

                @if ($selectedProperty)
                    <div class="mt-6">
                        <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-6">
                            <img src="{{ $selectedProperty->images ?? 'https://placehold.co/600x400?text=Property' }}" alt="{{ $selectedProperty->title }}" class="w-full lg:w-1/3 h-48 object-cover rounded-lg">
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $selectedProperty->title }}</h2>
                                <p class="text-gray-600 mb-2">{{ $selectedProperty->location }}</p>
                                <p class="text-gray-900 font-bold text-xl mb-4">${{ number_format($selectedProperty->price) }}</p>
                                <p class="text-gray-700 mb-4">{{ $selectedProperty->description ?? 'No description available.' }}</p>
                                @if (Auth::check())
                                    <form method="POST" action="#">
                                        @csrf
                                        <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition">Add to Favorites</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-gray-600 mt-4">Property not found.</p>
                @endif
            @else
                <p class="text-gray-600 mt-4">Select a property from the list to view its location on the map.</p>
            @endif
        </div>
    </div>
</div>
@endsection