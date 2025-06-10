@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Apartments / Houses -->
        <div>
            <a href="{{ route('properties') }}" class="block relative bg-cover bg-center h-96 rounded-lg overflow-hidden shadow-lg transition-transform transform hover:scale-105">
                <!-- Background Image with Overlay -->
                <div class="absolute inset-0 bg-black opacity-40"></div>
                <img src="{{asset('storage/banner/properties.png')}}" alt="Iconic Property" class="w-full h-full object-cover">
                <!-- Text Overlay -->
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-6">
                    <h2 class="text-3xl font-bold text-white mb-2">EMPERIUM 11 PROPERTY</h2>
                    <p class="text-xl text-yellow-400 font-semibold">CHECK OUT OUR NEW RANGE OF ICONIC PROPERTY</p>
                </div>
            </a>
        </div>

        <!-- For Businesses -->
        <div>
            <a href="{{ route('businesses') }}" class="block relative bg-cover bg-center h-96 rounded-lg overflow-hidden shadow-lg transition-transform transform hover:scale-105">
                <!-- Background Image with Overlay -->
                <div class="absolute inset-0 bg-black opacity-40"></div>
                <img src="{{asset('storage/banner/offices.png')}}" alt="Iconic Businesses" class="w-full h-full object-cover">
                <!-- Text Overlay -->
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-6">
                    <h2 class="text-3xl font-bold text-white mb-2">EMPERIUM 11 BUSINESSES</h2>
                    <p class="text-xl text-yellow-400 font-semibold">CHECK OUT OUR NEW RANGE OF ICONIC BUSINESSES</p>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center">
    <p class="text-gray-700">
        Discover the largest selection of properties and businesses in the city, exclusively at Emperium 11. Whether you’re looking to buy, sell, or invest, we set the standard for real estate excellence.
    </p>
    <p class="text-gray-700 mt-2">
        The market is yours—find your dream home, expand your business, or make your next big move. At Emperium 11, opportunity is always knocking.
    </p>
    <p class="text-gray-700 mt-4 italic">
        Experience a new level of service, innovation, and integrity. With Emperium 11, you’re not just a client—you’re part of our community.
    </p>
    <p class="text-gray-700 mt-2">
        Start your journey with Emperium 11 today. Your future begins here!
    </p>
</div>
@endsection