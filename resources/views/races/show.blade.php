@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-6 flex items-center space-x-2">
        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9-7-9-7-9 7 9 7zm0 0V5"></path>
        </svg>
        <span>Race #{{ $race->id }}</span>
    </h1>

    <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
        <!-- User Balance -->
        @auth
            <div class="mb-6">
                <p class="text-lg font-semibold text-gray-800">
                    Your Balance: <span class="text-green-600">${{ number_format($userBalance, 2) }}</span>
                </p>
                @if ($userBalance < 1)
                    <p class="text-sm text-red-600 mt-1">You need at least $1 to place a bid. Add funds to your account to continue.</p>
                @endif
            </div>
        @endauth
        @php
            $statusClasses = [
                'upcoming' => 'text-blue-600 bg-blue-100',
                'running' => 'text-yellow-600 bg-yellow-100',
                'finished' => 'text-green-600 bg-green-100',
            ];
        @endphp
        <!-- Race Details -->
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Race Details</h2>
            <p class="text-gray-600 mb-2"><span class="font-medium">Start Time:</span> {{ $race->start_time->setTimezone(Auth::user() ? Auth::user()->timezone : 'UTC')->format('M d, Y H:i') }}</p>
            <p class="text-gray-600 mb-2"><span class="font-medium">Statues:</span> 
            <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $statusClasses[$race->status] ?? 'text-gray-600 bg-gray-100' }}">
                {{ ucfirst($race->status) }}
            </span>
            <p class="text-gray-600 mb-2"><span class="font-medium">Distance:</span> {{ $race->distance }} meters</p>
        </div>

        <!-- Horses -->
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Horses</h2>
            <div class="space-y-4">
                @foreach ($race->horses as $horse)
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $horse->name }}</h3>
                                <p class="text-gray-600">
                                    Total Bids: 
                                    <span class="font-semibold">${{ number_format($race->bids->where('horse_id', $horse->id)->sum('amount'), 2) }}</span>
                                    ({{ $race->bids->where('horse_id', $horse->id)->count() }} bids)
                                </p>
                            </div>
                            @if ($race->status === 'upcoming' && $race->start_time > now())
                                <form action="{{ route('races.bid', $race) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    <input type="hidden" name="horse_id" value="{{ $horse->id }}">
                                    <input type="number" name="amount" min="1" step="0.01" placeholder="Bid Amount" class="border rounded-lg px-3 py-2 w-32" required>
                                    <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition">
                                        Place Bid
                                    </button>
                                </form>
                            @endif
                        </div>
                        <!-- Existing Bids -->
                        @if ($race->bids->where('horse_id', $horse->id)->isNotEmpty())
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700">Bids on {{ $horse->name }}:</p>
                                <div class="space-y-2 mt-2">
                                    @foreach ($race->bids->where('horse_id', $horse->id) as $bid)
                                        <div class="flex items-center justify-between p-2 bg-white rounded-lg shadow-sm">
                                            <p class="text-sm text-gray-600">
                                                User: {{ $bid->user->privacySetting->hide_personal_info ?? false ? 'Anonymous' : $bid->user->name }}
                                            </p>
                                            <p class="text-sm text-gray-900 font-semibold">
                                                ${{ number_format($bid->amount, 2) }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-600 mt-2">No bids on this horse yet.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Race Results -->
        @if ($race->status === 'finished')
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Race Results</h2>
                <div class="space-y-2">
                    @foreach ($race->raceResults->sortBy('position') as $result)
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg {{ $result->position === 1 ? 'border-l-4 border-green-500' : '' }}">
                            <div class="w-12 text-center font-semibold text-gray-900">
                                #{{ $result->position }}
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-900 font-semibold">{{ $result->horse->name }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- User Bids -->
            @auth
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Your Bids</h2>
                    @if ($race->bids->where('user_id', Auth::id())->isEmpty())
                        <p class="text-gray-600">You did not place any bids on this race.</p>
                    @else
                        <div class="space-y-2">
                            @foreach ($race->bids->where('user_id', Auth::id()) as $bid)
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <p class="text-gray-900">Horse: {{ $bid->horse->name }}</p>
                                    <p class="text-gray-900">Bid Amount: ${{ number_format($bid->amount, 2) }}</p>
                                    <p class="text-gray-900">
                                        Result: 
                                        @if ($bid->payout)
                                            <span class="text-green-600 font-semibold">Won ${{ number_format($bid->payout, 2) }} (added to your balance)</span>
                                        @else
                                            <span class="text-red-600">Lost</span>
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('races') }}" class="text-blue-600 hover:underline font-medium flex items-center space-x-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Back to Races</span>
        </a>
    </div>
</div>
@endsection