@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span>{{ auth()->user()->name }}'s Profile</span>
        </h1>

        <a href="{{ route('home') }}"
           class="text-blue-600 hover:underline font-medium">
            ← Back to Main Menu
        </a>
    </div>

    <!-- Profile Card -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <div class="flex flex-col lg:flex-row lg:space-x-6">
            <!-- User Avatar -->
            <div class="flex-shrink-0 mb-4 lg:mb-0">
                <img src="{{ $user->stats->avatar_url }}"
                     alt="User Avatar"
                     class="w-32 h-32 rounded-full border-4 border-green-200 shadow-md">
                <!-- Avatar Upload Form -->
                <form action="{{ route('profile.updateAvatar') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    @method('PATCH')
                    <div class="flex items-center space-x-2">
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden" onchange="this.form.submit()">
                        <label for="avatar"
                               class="cursor-pointer bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition">
                            Change Avatar
                        </label>
                    </div>
                    @error('avatar')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </form>
                <form action="{{ route('profile.updateBanner') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    @method('PATCH')
                    <div class="flex items-center space-x-2">
                        <input type="file" name="banner" id="banner" accept="image/*" class="hidden" onchange="this.form.submit()">
                        <label for="banner"
                               class="cursor-pointer bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                            Change Banner
                        </label>
                    </div>
                    @error('banner')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </form>
            </div>

            <!-- User Info -->
            <div class="flex-1">
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">User Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600"><span class="font-medium">Name:</span> {{ $user->name }}</p>
                        <p class="text-gray-600"><span class="font-medium">Email:</span> {{ $user->email }}</p>
                        <p class="text-gray-600"><span class="font-medium">Joined:</span> {{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('privacy.settings') }}"
                    class="inline-flex items-center bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.1.9-2 2-2s2 .9 2 2-2 4-2 4m-4-4c0-1.1-.9-2-2-2s-2 .9-2 2 2 4 2 4m4-4V3m0 12v6m-6-6H3m6 0h6"></path>
                    </svg>
                    Change Privacy Settings
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center space-x-2">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Financial Stats</span>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-green-50 p-4 rounded-lg shadow-inner">
                <p class="text-gray-600 font-medium">Balance</p>
                <p class="text-2xl font-bold text-green-600">${{ number_format($user->stats->balance, 2) }}</p>
            </div>
            <div class="bg-blue-50 p-4 rounded-lg shadow-inner">
                <p class="text-gray-600 font-medium">Total Income</p>
                <p class="text-2xl font-bold text-blue-600">${{ number_format($user->stats->total_income, 2) }}</p>
            </div>
            <div class="bg-red-50 p-4 rounded-lg shadow-inner">
                <p class="text-gray-600 font-medium">Total Expenses</p>
                <p class="text-2xl font-bold text-red-600">${{ number_format($user->stats->total_expenses, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Owned Properties Card -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center space-x-2">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span>Owned Properties</span>
        </h2>
        @if ($user->ownedProperties->isEmpty())
            <p class="text-gray-600">You don't own any properties yet.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($user->ownedProperties as $ownedProperty)
                    @php
                        $property = $ownedProperty->ownable;
                    @endphp
                    <div class="bg-gray-50 p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <img src="{{ $property->images ?? 'https://placehold.co/300x200?text=Property' }}"
                             alt="{{ $property->title }}"
                             class="w-full h-40 object-cover rounded-md mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $property->title }}</h3>
                        <p class="text-gray-600 mb-1"><span class="font-medium">Location:</span> {{ $property->location }}</p>
                        <p class="text-gray-600 mb-1"><span class="font-medium">Type:</span> {{ ucfirst($property->type) }}</p>
                        <p class="text-gray-600 mb-3"><span class="font-medium">Price:</span> ${{ number_format($property->price, 2) }}</p>
                        <a href="{{ route('property.show', ['id' => $property->id, 'type' => $property->type]) }}"
                           class="text-blue-600 hover:underline font-medium">
                            View Details
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Bidding History -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Bidding History</h2>
            @if ($user->bids->isEmpty())
                <p class="text-gray-600">You have not placed any bids yet.</p>
            @else
                <button id="toggle-bids" class="w-full text-left bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-lg flex justify-between items-center transition">
                    <span>View Bidding History ({{ $user->bids->count() }} bids)</span>
                    <svg id="toggle-icon" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="bids-content" class="mt-4 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        @foreach ($user->bids as $bid)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-gray-900"><span class="font-medium">Race:</span> {{ $bid->race_id }}</p>
                                <p class="text-gray-900"><span class="font-medium">Horse:</span> {{ $bid->horse->name }}</p>
                                <p class="text-gray-900"><span class="font-medium">Bet Amount:</span> ${{ number_format($bid->amount, 2) }}</p>
                                <p class="text-gray-900"><span class="font-medium">Outcome:</span>
                                    @if ($bid->race->status !== 'finished')
                                        <span class="text-yellow-600">Pending (Race not finished)</span>
                                    @elseif ($bid->payout)
                                        <span class="text-green-600">Won ${{ number_format($bid->payout, 2) }}</span>
                                    @else
                                        <span class="text-red-600">Lost ${{ number_format($bid->amount, 2) }}</span>
                                    @endif
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
</div>

<script>
    // Toggle collapse for bidding history
    const toggleButton = document.getElementById('toggle-bids');
    const toggleIcon = document.getElementById('toggle-icon');
    const bidsContent = document.getElementById('bids-content');

    toggleButton.addEventListener('click', function() {
        bidsContent.classList.toggle('hidden');
        toggleIcon.classList.toggle('rotate-180');
    });
</script>
@endsection