<div class="mb-6">
    <div class="flex flex-wrap gap-3">
        <!-- All Button -->
        <a href="{{ route('properties', array_merge(request()->except('price_category'), ['price_category' => 'all'])) }}"
           class="px-5 py-2.5 rounded-full font-medium text-sm transition-all duration-300 shadow-md hover:shadow-lg 
                  {{ !request('price_category') || request('price_category') === 'all' ? 'bg-green-600 text-white shadow-green-500/30' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 hover:border-gray-400' }}">
            All
        </a>

        <!-- Price Category Buttons -->
        @foreach (['low-end' => 'Low-end', 'medium' => 'Medium', 'high-end' => 'High-end'] as $value => $label)
            <a href="{{ route('properties', array_merge(request()->except('price_category'), ['price_category' => $value])) }}"
               class="px-5 py-2.5 rounded-full font-medium text-sm transition-all duration-300 shadow-md hover:shadow-lg 
                      {{ request('price_category') === $value ? 'bg-green-600 text-white shadow-green-500/30' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 hover:border-gray-400' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>