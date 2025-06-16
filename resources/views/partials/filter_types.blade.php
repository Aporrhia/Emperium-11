<div class="mb-6">
    <!-- Filter Buttons -->
    <div class="flex flex-wrap gap-3">
        <!-- All Button -->
        @if (Route::is('properties')) 
            <a href="{{ route('properties', array_merge(request()->except('filter_type'), ['filter_type' => 'all'])) }}"
            class="px-5 py-2.5 rounded-full font-medium text-sm transition-all duration-300 shadow-md hover:shadow-lg 
                    {{ !request('filter_type') || request('filter_type') === 'all' ? 'bg-green-600 text-white shadow-green-500/30' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 hover:border-gray-400' }}">
                All
            </a>
        @elseif (Route::is('businesses')) 
            <a href="{{ route('businesses', array_merge(request()->except('filter_type'), ['filter_type' => 'all'])) }}"
            class="px-5 py-2.5 rounded-full font-medium text-sm transition-all duration-300 shadow-md hover:shadow-lg 
                    {{ !request('filter_type') || request('filter_type') === 'all' ? 'bg-green-600 text-white shadow-green-500/30' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 hover:border-gray-400' }}">
                All
            </a>
        @endif

        <!-- Type Buttons -->
        @if (Route::is('properties')) 
            @foreach ($types as $type)
                <a href="{{ route('properties', array_merge(request()->except('filter_type'), ['filter_type' => $type])) }}"
                class="px-5 py-2.5 rounded-full font-medium text-sm transition-all duration-300 shadow-md hover:shadow-lg 
                        {{ request('filter_type') === $type ? 'bg-green-600 text-white shadow-green-500/30' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 hover:border-gray-400' }}">
                    {{ ucfirst($type) }}
                </a>
            @endforeach
        @elseif (Route::is('businesses')) 
            @foreach ($types as $type)
                <a href="{{ route('businesses', array_merge(request()->except('filter_type'), ['filter_type' => $type])) }}"
                class="px-5 py-2.5 rounded-full font-medium text-sm transition-all duration-300 shadow-md hover:shadow-lg 
                        {{ request('filter_type') === $type ? 'bg-green-600 text-white shadow-green-500/30' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 hover:border-gray-400' }}">
                    {{ ucfirst($type) }}
                </a>
            @endforeach
        @endif
    </div>
</div>