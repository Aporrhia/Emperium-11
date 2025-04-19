@extends('layouts.app')

@section('content')
<div class="relative bg-cover bg-center h-64" style="background-image: url('https://placehold.co/1200x400?text=Ocean+View')">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-white">EMPERIUM 11</h1>
            <p class="text-xl text-white mt-2">The best move you'll ever make</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Apartments / Houses -->
        <div>
            <a href="{{ route('properties') }}" class="block relative bg-cover bg-center h-96 rounded-lg overflow-hidden shadow-lg transition-transform transform hover:scale-105">
                <!-- Background Image with Overlay -->
                <div class="absolute inset-0 bg-black opacity-40"></div>
                <img src="https://placehold.co/600x400?text=Iconic+Property" alt="Iconic Property" class="w-full h-full object-cover">
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
                <img src="https://placehold.co/600x400?text=Iconic+Office" alt="Iconic Businesses" class="w-full h-full object-cover">
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
        We have more real estate listings than any other broker in the city. In this town, we know that biggest and best are the same thing.
    </p>
    <p class="text-gray-700 mt-2">
        It’s a buyer’s market. It’s a seller’s market. It’s whatever you want it to be. At Emperium 11, your dreams come pre-approved.
    </p>
    <p class="text-gray-700 mt-4 italic">
        For a professional, personal, sometimes inappropriate touch, look no further than Emperium 11. Don’t think of us as your realtor, think of us as your friend.
    </p>
    <p class="text-gray-700 mt-2">
        Call Emperium 11 today, and say hello to a good BUY!
    </p>
</div>
@endsection