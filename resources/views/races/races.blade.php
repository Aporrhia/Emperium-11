@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center space-x-2">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9-7-9-7-9 7 9 7zm0 0V5"></path>
            </svg>
            <span>Horse Track</span>
            <!-- Help Icon -->
            <button id="help-button" class="text-green-600 hover:text-green-800 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-full" aria-label="Open help modal">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke-width="2" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.5 9.5a2.5 2.5 0 015 0c0 1.5-2 2-2 2m0 3h0" />
                </svg>
            </button>
        </h1>
    </div>

    <!-- Help Modal -->
    <div id="help-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300 opacity-0" role="dialog" aria-modal="true" aria-labelledby="help-modal-title">
        <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative transform transition-transform duration-300 scale-95">
            <!-- Close Button -->
            <button id="close-modal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 focus:outline-none" aria-label="Close help modal">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <!-- Modal Content -->
            <h2 id="help-modal-title" class="text-2xl font-semibold text-gray-800 mb-4">About the Bidding System</h2>
            <div class="text-gray-600 space-y-4">
                <p>
                    Welcome to the Horse Track bidding system! Here’s how it works:
                </p>
                <ul class="list-disc list-inside space-y-2">
                    <li><strong>Upcoming Races:</strong> You can place bids on horses in upcoming races before they start. Each race lists the horses, their speed ratings, and current bids.</li>
                    <li><strong>Bidding:</strong> To place a bid, select a horse and enter your bid amount. You can only place one bid per race, and your balance must cover the bid amount.</li>
                    <li><strong>Race Simulation:</strong> Once a race starts, it will run for its duration (e.g., 5 minutes). After the race ends, the system simulates the results based on horse speeds with a random factor.</li>
                    <li><strong>Payouts:</strong> If your horse wins, you’ll receive a payout based on the total pool of bids, minus a 10% commission. The payout is calculated as your bid amount multiplied by the payout per dollar, which depends on the total winning bids.</li>
                    <li><strong>Balance & Stats:</strong> Your balance, total expenses, and total income are tracked in your profile. Winnings are added to your balance, and bids are deducted from it.</li>
                    <li><strong>Auto-Refresh:</strong> The page automatically refreshes race statuses every 10 seconds.</li>
                </ul>
                <p>
                    Have fun and good luck at the races!
                </p>
            </div>
        </div>
    </div>

    <!-- Horse Winning Rates Box -->
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Horse Winning Rates</h2>
        <div class="bg-white shadow-lg rounded-lg p-6">
            @if ($horses->isEmpty())
                <p class="text-gray-600">No horses available at the moment.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($horses as $horse)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-900 font-medium">{{ $horse->name }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="inline-block px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full">
                                    {{ $horse->winning_rate }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Progress Bar for Auto-Refresh -->
    <div class="mb-6">
        <p class="text-sm text-gray-600 mb-1">Next status update in 10 seconds...</p>
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div id="progress-bar" class="bg-green-600 h-2.5 rounded-full" style="width: 0%"></div>
        </div>
    </div>

    <!-- Upcoming Races -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Upcoming Races</h2>
        @if ($upcomingRaces->isEmpty())
            <p class="text-gray-600">No upcoming races at the moment.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($upcomingRaces as $race)
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Race #{{ $race->id }}
                                </h3>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $race->start_time->setTimezone(Auth::user() ? Auth::user()->timezone : 'UTC')->format('M d, Y H:i') }}
                                </h3>
                                <p class="text-gray-600">Distance: {{ $race->distance }} meters</p>
                                
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-gray-700">Current Bids:</p>
                                    @foreach ($race->horses as $horse)
                                        <p class="text-sm text-gray-600">
                                            {{ $horse->name }}: 
                                            <span class="font-semibold">
                                                ${{ number_format($race->bids->where('horse_id', $horse->id)->sum('amount'), 2) }}
                                            </span>
                                            ({{ $race->bids->where('horse_id', $horse->id)->count() }} bids)
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900 mb-2">
                                    Bids: ${{ number_format($race->total_bid_sum, 2) }}
                                </p>
                                <a href="{{ route('races.show', $race) }}" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                                    View Race
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Running Races -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Running Races</h2>
        @if ($runningRaces->isEmpty())
            <p class="text-gray-600">No races are currently running.</p>
        @else
            <div class="space-y-4">
                @foreach ($runningRaces as $race)
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Race #{{ $race->id }}
                                </h3>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $race->start_time->setTimezone(Auth::user() ? Auth::user()->timezone : 'UTC')->format('M d, Y H:i') }}
                                </h3>
                                <p class="text-gray-600">Distance: {{ $race->distance }} meters</p>
                                
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-gray-700">Final Bids:</p>
                                    @foreach ($race->horses as $horse)
                                        <p class="text-sm text-gray-600">
                                            {{ $horse->name }}: 
                                            <span class="font-semibold">
                                                ${{ number_format($race->bids->where('horse_id', $horse->id)->sum('amount'), 2) }}
                                            </span>
                                            ({{ $race->bids->where('horse_id', $horse->id)->count() }} bids)
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900 mb-2">
                                    Bids: ${{ number_format($race->total_bid_sum, 2) }}
                                </p>
                                <a href="{{ route('races.show', $race) }}" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                                    View Race
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Finished Races -->
    <div>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Finished Races</h2>
        @if ($finishedRaces->isEmpty())
            <p class="text-gray-600">No finished races yet.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
                @foreach ($finishedRaces as $race)
                    <div class="bg-white shadow-lg rounded-lg p-6 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Race #{{ $race->id }}
                                </h3>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $race->start_time->setTimezone(Auth::user() ? Auth::user()->timezone : 'UTC')->format('M d, Y H:i') }}
                                </h3>
                                <p class="text-gray-600">Distance: {{ $race->distance }} meters</p>
                                @if ($race->raceResults->where('position', 1)->first())
                                    <p class="text-green-600 font-semibold">
                                        Winner: {{ $race->raceResults->where('position', 1)->first()->horse->name }}
                                    </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900 mb-2">
                                    Bids: ${{ number_format($race->total_bid_sum, 2) }}
                                </p>
                                <a href="{{ route('races.show', $race) }}" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                                    View Race
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    // Progress bar animation
    const progressBar = document.getElementById('progress-bar');
    let progress = 0;
    const intervalTime = 10000; // 10 seconds
    const increment = 100 / (intervalTime / 100); // Increment per 100ms

    function animateProgressBar() {
        progress = 0;
        progressBar.style.width = '0%';

        const animation = setInterval(function() {
            progress += increment;
            if (progress >= 100) {
                progress = 100;
                clearInterval(animation);
            }
            progressBar.style.width = progress + '%';
        }, 100);
    }

    animateProgressBar();
    setInterval(function() {
        fetch('{{ route('races.updateStatuses') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            }
        });
        animateProgressBar();
    }, intervalTime);

    // Modal functionality
    const helpButton = document.getElementById('help-button');
    const helpModal = document.getElementById('help-modal');
    const closeModal = document.getElementById('close-modal');

    // Open modal
    helpButton.addEventListener('click', function() {
        helpModal.classList.remove('hidden');
        helpModal.classList.remove('opacity-0');
        helpModal.querySelector('div').classList.remove('scale-95');
        closeModal.focus(); // Focus the close button when the modal opens
    });

    // Close modal when clicking the close button
    closeModal.addEventListener('click', function() {
        helpModal.classList.add('opacity-0');
        helpModal.querySelector('div').classList.add('scale-95');
        setTimeout(() => helpModal.classList.add('hidden'), 300);
    });

    // Close modal when clicking outside
    helpModal.addEventListener('click', function(event) {
        if (event.target === helpModal) {
            helpModal.classList.add('opacity-0');
            helpModal.querySelector('div').classList.add('scale-95');
            setTimeout(() => helpModal.classList.add('hidden'), 300);
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !helpModal.classList.contains('hidden')) {
            helpModal.classList.add('opacity-0');
            helpModal.querySelector('div').classList.add('scale-95');
            setTimeout(() => helpModal.classList.add('hidden'), 300);
        }
    });

    // Trap focus inside the modal
    helpModal.addEventListener('keydown', function(event) {
        if (event.key === 'Tab') {
            event.preventDefault();
            closeModal.focus(); // Keep focus within the modal
        }
    });
</script>
@endsection