@php
    if (Route::is('properties')) {
        $path = asset('storage/banner/property-banner.png');
    } elseif (Route::is('businesses')) {
        $path = asset('storage/banner/business-banner.png');
    } elseif (Route::is('races')) {
        $path = asset('storage/banner/track-banner.png');
    } elseif (Route::is('profile')) {
        $path = asset($user->stats->banner ?? 'https://placehold.co/');
    } else {
        $path = asset('storage/banner/property-banner.png');
    }
@endphp

<div class="relative bg-cover bg-center h-48" style="background-image: url('{!! $path !!}');">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-center">
        <div class="text-center">
            @if (Route::is('properties'))
                <img src="{{ asset('storage/logo/property-logo.png') }}"
                    alt="Property Logo"
                    class="mx-auto h-96 w-auto">
            @elseif (Route::is('businesses'))
                <img src="{{ asset('storage/logo/business-logo.png') }}"
                    alt="Business Logo"
                    class="mx-auto h-74 w-auto">
            @endif
        </div>
    </div>
</div>