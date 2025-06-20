<!-- resources/views/properties/properties.blade.php -->
@extends('layouts.app')

@use('App\Models\Apartment')
@use('App\Models\House')
@use('App\Models\Office')
@use('App\Models\Bunker')
@use('App\Models\Warehouse')
@use('App\Models\Facility')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Include Filter Buttons -->
    @include('partials.filter_types', ['types' => $types])
    @if (Route::is('properties'))
        @include('partials.filter_prices')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Left Sidebar: Scrollable Property Listings -->
        <div class="lg:col-span-1 bg-white shadow rounded-lg p-4 max-h-[700px] overflow-y-auto">
            <!-- Sorting Options -->
            <div class="mb-6">
                <div class="grid grid-cols-3 gap-2 items-center">
                    @php
                        $activeSort = request('sort', session('sort_option', 'title'));
                    @endphp
                    <div class="text-gray-700 font-medium text-sm">Price:</div>
                    <!-- Price: Low to High -->
                    <a href="{{ route(request()->routeIs('properties') ? 'properties' : 'businesses', array_merge(request()->except('sort'), ['sort' => 'price-low-to-high'])) }}"
                    class="px-3 py-1.5 rounded-full font-medium text-xs transition-all duration-300 shadow-sm hover:shadow-md 
                            {{ $activeSort === 'price-low-to-high' ? 'bg-green-600 text-white shadow-green-500/30' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 hover:border-gray-400' }}">
                        Low to High
                    </a>
                    <!-- Price: High to Low -->
                    <a href="{{ route(request()->routeIs('properties') ? 'properties' : 'businesses', array_merge(request()->except('sort'), ['sort' => 'price-high-to-low'])) }}"
                    class="px-3 py-1.5 rounded-full font-medium text-xs transition-all duration-300 shadow-sm hover:shadow-md 
                            {{ $activeSort === 'price-high-to-low' ? 'bg-green-600 text-white shadow-green-500/30' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 hover:border-gray-400' }}">
                        High to Low
                    </a>
                </div>
            </div>

            @if ($properties->isEmpty())
                <p class="text-gray-600">No properties found.</p>
            @else
                @foreach ($properties as $property)
                    @php
                        // Determine the type and ownership
                        $type = $property->type; // Already mapped in displayProperties (e.g., 'house', 'apartment')
                        $isFromSoldProperties = in_array($type, ['house', 'apartment']);
                        $isOwned = Auth::check() && Auth::user()->ownedProperties->contains(function ($ownedProperty) use ($property, $isFromSoldProperties) {
                            return $isFromSoldProperties
                                ? ($ownedProperty->ownable_id === $property->id && $ownedProperty->ownable_type === 'sold_property')
                                : ($ownedProperty->ownable_id === $property->id && $ownedProperty->ownable_type === get_class($property));
                        });
                    @endphp
                    <a href="{{ request()->routeIs('properties') ? route('properties', ['id' => $property->id, 'type' => $type]) : route('businesses', ['id' => $property->id, 'type' => $type]) }}"
                        class="block p-4 border-b last:border-b-0 hover:bg-gray-100 transition">
                        <!-- Image: Full width -->
                        @if (Str::startsWith($property->images, ['http://', 'https://']))
                            <img src="{{$property->images}}"
                                alt="{{ $property->title }}"
                                class="w-full h-40 object-cover rounded-lg mb-4">
                        @else
                            <img src="{{ $isFromSoldProperties ? (isset($property->images) && !empty(json_decode($property->images, true)) ? asset('storage/' . json_decode($property->images, true)[0]) : 'https://placehold.co/100x100?text=Property') : ($property->images ?? 'https://placehold.co/100x100?text=Property') }}"
                                alt="{{ $property->title }}"
                                class="w-full h-40 object-cover rounded-lg mb-4">
                        @endif
                        <!-- Property Info -->
                        <div class="mb-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $property->title }}</h3>
                            @if ($isOwned)
                                <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">Owned</span>
                            @endif
                            <p class="text-gray-600">{{ $property->location }}</p>
                            <p class="text-gray-600">{{ ucfirst($property->type) }}</p>
                        </div>

                        <!-- Price and View Button: Grid layout -->
                        <div class="grid grid-cols-2 gap-4 items-center">
                            <p class="text-gray-900 font-bold text-lg">${{ number_format($property->price) }}</p>
                            <div class="text-right">
                                @if ($isOwned)
                                    <span class="inline-block bg-gray-400 text-white font-semibold py-1.5 px-3 rounded-lg cursor-not-allowed">
                                        Owned
                                    </span>
                                @else
                                    <span class="inline-block bg-green-600 text-white font-semibold py-1.5 px-3 rounded-lg hover:bg-green-700 transition">
                                        View
                                    </span>
                                @endif
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
                    $selectedProperty = $properties->firstWhere(function ($property) {
                        return $property->id == request('id') && $property->type == request('type');
                    });

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
                            @if (Str::startsWith($property->images, ['http://', 'https://']))
                                <img src="{{$property->images}}"
                                    alt="{{ $selectedProperty->title }}"
                                    class="w-full lg:w-1/3 h-48 object-cover rounded-lg">
                            @else
                                <img src="{{ $isFromSoldProperties ? (isset($property->images) && !empty(json_decode($property->images, true)) ? asset('storage/' . json_decode($property->images, true)[0]) : 'https://placehold.co/100x100?text=Property') : ($property->images ?? 'https://placehold.co/100x100?text=Property') }}"
                                    alt="{{ $selectedProperty->title }}"
                                    class="w-full lg:w-1/3 h-48 object-cover rounded-lg">
                            @endif
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $selectedProperty->title }}</h2>
                                <p class="text-gray-600">Location: {{ $selectedProperty->location }}</p>
                                @if (in_array($selectedProperty->type, ['house', 'apartment']))
                                    <p class="text-gray-600">Size: {{ $selectedProperty->size }} sq ft</p>
                                    <p class="text-gray-600">Seller: {{ $selectedProperty->seller_type === 'emperium' ? 'Emperium 11' : ($selectedProperty->seller_id ? \DB::table('users')->where('id', $selectedProperty->seller_id)->value('name') : 'Emperium 11') }}</p>
                                @endif
                                <p class="text-gray-900 font-bold">Full price: ${{ number_format($selectedProperty->price) }}</p>
                                <p class="text-gray-900 font-bold mb-4">Rent price: ${{ number_format($selectedProperty->price * 0.011) }}/month</p>
                                @if (Auth::check())
                                    @php
                                        $isOwned = Auth::check() && Auth::user()->ownedProperties->contains(function ($ownedProperty) use ($selectedProperty) {
                                            return in_array($selectedProperty->type, ['house', 'apartment'])
                                                ? ($ownedProperty->ownable_id === $selectedProperty->id && $ownedProperty->ownable_type === 'sold_property')
                                                : ($ownedProperty->ownable_id === $selectedProperty->id && $ownedProperty->ownable_type === get_class($selectedProperty));
                                        });
                                    @endphp
                                    @if ($isOwned)
                                        <span class="inline-block bg-gray-400 text-white font-semibold py-1.5 px-3 rounded-lg cursor-not-allowed">
                                            Owned
                                        </span>
                                    @else
                                        <form action="{{ route('property.buy', $selectedProperty->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="type" value="{{ $selectedProperty->type }}">
                                            <button type="submit" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                                                Buy Now
                                            </button>
                                        </form>
                                    @endif
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