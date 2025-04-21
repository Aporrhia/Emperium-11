@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $property->title }}</h1>

    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <div class="flex flex-col lg:flex-row space-y-6 lg:space-y-0 lg:space-x-6">
            <!-- Property Image -->
            <img src="{{ $property->images ?? 'https://placehold.co/600x400?text=Property' }}"
                 alt="{{ $property->title }}"
                 class="w-full lg:w-1/2 h-64 object-cover rounded-lg">

            <!-- Property Details -->
            <div class="flex-1">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Property Details</h2>
                <p class="text-gray-600 mb-2"><span class="font-medium">Location:</span> {{ $property->location }}</p>
                <p class="text-gray-600 mb-2"><span class="font-medium">Type:</span> {{ ucfirst($property->type) }}</p>
                <p class="text-gray-900 font-bold text-xl mb-4"><span class="font-medium">Price:</span> ${{ number_format($property->price) }}</p>
                <p class="text-gray-700 mb-6">{{ $property->description ?? 'No description available.' }}</p>

                @php
                    $user = Auth::user();
                    $isOwned = $user->ownedProperties->contains(function ($ownedProperty) use ($property) {
                        return $ownedProperty->ownable_id === $property->id && $ownedProperty->ownable_type === get_class($property);
                    });
                @endphp
                @if ($isOwned)
                    <span class="inline-block bg-gray-400 text-white font-semibold py-1.5 px-3 rounded-lg cursor-not-allowed">
                        Owned
                    </span>
                @else
                    <!-- Buy Now Form -->
                    <form method="POST" action="{{ route('property.buy', $property->id) }}">
                        @csrf
                        <input type="hidden" name="type" value="{{ $property->type }}">
                        <button type="submit"
                                class="bg-green-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-green-700 transition w-full lg:w-auto">
                            Buy Now
                        </button>
                    </form>
                @endif
                
            </div>
        </div>
    </div>
    <div class="bg-white shadow rounded-lg p-6">
        @include('partials.map', ['properties' => [$property]])
    </div>

    <!-- Back Link -->
    <div class="mt-6">
        <a href="{{ route('properties') }}"
           class="text-blue-600 hover:underline font-medium">
            &larr; Back to Properties
        </a>
    </div>
</div>
@endsection