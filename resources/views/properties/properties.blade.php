@extends('layouts.app')

@use('App\Models\Apartment')
@use('App\Models\House')
@use('App\Models\Office')
@use('App\Models\Bunker')
@use('App\Models\Warehouse')
@use('App\Models\Facility')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $title }}</h1>

    <!-- Include Filter Buttons -->
    @include('partials.filter_types', ['types' => $types])

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Left Sidebar: Scrollable Property Listings -->
        <div class="lg:col-span-1 bg-white shadow rounded-lg p-4 max-h-[600px] overflow-y-auto">
            @if ($properties->isEmpty())
                <p class="text-gray-600">No properties found.</p>
            @else
                @foreach ($properties as $property)
                    @php
                        // Determine the type based on the model class
                        $type = $property instanceof \App\Models\Apartment ? 'apartment' :
                                ($property instanceof \App\Models\House ? 'house' :
                                ($property instanceof \App\Models\Office ? 'office' :
                                ($property instanceof \App\Models\Bunker ? 'bunker' :
                                ($property instanceof \App\Models\Warehouse ? 'warehouse' :
                                ($property instanceof \App\Models\Facility ? 'facility' : '')))));
                    @endphp
                    <a href="{{ request()->routeIs('properties') ? route('properties', ['id' => $property->id, 'type' => $type]) : route('businesses', ['id' => $property->id, 'type' => $type]) }}"
                       class="block p-4 border-b last:border-b-0 hover:bg-gray-100 transition">
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
        </div>

        <!-- Map -->
        <div class="lg:col-span-3 bg-white shadow rounded-lg p-6">
            @include('partials.map', ['properties' => $properties])

            <!-- Selected Property Details (Below Map) -->
            @if (request()->has('id'))
                @php
                    // First, try to find the property in the current collection
                    $selectedProperty = $properties->firstWhere('id', request('id'));

                    // If not found in the current collection, fetch from the database using the type
                    if (!$selectedProperty && request()->has('type')) {
                        $type = request('type');
                        if ($type === 'apartment') {
                            $selectedProperty = Apartment::find(request('id'));
                        } elseif ($type === 'house') {
                            $selectedProperty = House::find(request('id'));
                        } elseif ($type === 'office') {
                            $selectedProperty = Office::find(request('id'));
                        } elseif ($type === 'bunker') {
                            $selectedProperty = Bunker::find(request('id'));
                        } elseif ($type === 'warehouse') {
                            $selectedProperty = Warehouse::find(request('id'));
                        } elseif ($type === 'facility') {
                            $selectedProperty = Facility::find(request('id'));
                        }
                    }
                @endphp

                @if ($selectedProperty)
                    <div class="mt-6">
                        <div class="flex flex-col lg:flex-row space-y-4 lg:space-y-0 lg:space-x-6">
                            <img src="{{ $selectedProperty->images ?? 'https://placehold.co/600x400?text=Property' }}" alt="{{ $selectedProperty->title }}" class="w-full lg:w-1/3 h-48 object-cover rounded-lg">
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $selectedProperty->title }}</h2>
                                <p class="text-gray-600 mb-4">Location: {{ $selectedProperty->location }}</p>
                                @if (Auth::check())
                                    <a href="{{ route('property.show', ['id' => $selectedProperty->id, 'type' => $selectedProperty->type]) }}"
                                       class="inline-block bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition">
                                        View Details
                                    </a>
                                @else
                                    <p class="text-gray-600">Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">log in</a> to view full details and buy this property.</p>
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