<!-- resources/views/tiers.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">User Tier List</h1>

    <div class="bg-white shadow rounded-lg p-6">
        @if ($users->isEmpty())
            <p class="text-gray-600">No users are currently visible in the tier list.</p>
        @else
            <div class="space-y-4">
                @foreach ($users as $index => $user)
                    <div class="flex items-center p-2 h-28 hover:bg-gray-50 transition-all duration-200">
                        <!-- Rank -->
                        <div class="w-12 text-center">
                            <span class="inline-block px-3 py-1 text-lg font-semibold text-white bg-green-600 rounded-full shadow-sm">
                                #{{ $index + 1 }}
                            </span>
                        </div>
                        
                        <!-- Banner -->
                        @if (!($user->privacySetting->hide_personal_info ?? false) && $user->stats && $user->stats->avatar_url)
                            <div class="absolute ms-16 p-2 w-48 md:w-96 h-24 bg-cover bg-center rounded-lg" 
                                style="background-image: url('{{ $user->stats->banner ?? '' }}');">
                                <div class="absolute inset-0 bg-black opacity-30 rounded-lg"></div>
                                <!-- Fade Effect Overlay -->
                                <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(0, 0, 0, 0) 0%, rgba(255, 255, 255, 0.3) 70%, rgba(255, 255, 255, 0.7) 85%, rgba(255, 255, 255, 1) 100%);"></div>
                            </div>
                        @else
                            <div class="absolute ms-16 p-2 w-48 md:w-96 h-24 bg-cover bg-center rounded-lg" 
                                style="background-image: url('https://placehold.co/">
                                <div class="absolute inset-0 bg-black opacity-30 rounded-lg"></div>
                                <!-- Fade Effect Overlay -->
                                <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(0, 0, 0, 0) 0%, rgba(255, 255, 255, 0.3) 70%, rgba(255, 255, 255, 0.7) 85%, rgba(255, 255, 255, 1) 100%);"></div>
                            </div>
                        @endif
                        
                        <!-- User Block -->
                        <div class="relative flex items-center ml-4">
                            <!-- Overlay for better visibility -->
                            <div class="bg-stone-200/75 rounded-lg ms-4 p-2 relative flex items-center z-45">
                                <!-- Avatar -->
                                <div class="relative z-50">
                                    @if (!($user->privacySetting->hide_personal_info ?? false) && $user->stats && $user->stats->avatar_url)
                                        <img src="{{ $user->stats->avatar_url }}" alt="User Avatar" class="w-12 h-12 rounded-full border-2 border-green-200 shadow-md object-cover">
                                    @else
                                        <img src="https://placehold.co/48x48?text=?" alt="Default Avatar" class="w-12 h-12 rounded-full border-2 border-gray-200 shadow-md object-cover">
                                    @endif
                                </div>

                                <!-- Username -->
                                <div class="relative flex-1 ml-4 z-50 max-w-[95px] md:max-w-[300px]">
                                    <h3 class="text-xl font-semibold text-gray-900 break-words">
                                        {{ $user->privacySetting->hide_personal_info ?? false ? $user->name : ($user->name) }}
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <!-- Total Income -->
                        <div class="flex-1 text-right">
                            <p class="text-lg font-bold text-gray-900 z-100">
                                ${{ number_format($user->stats ? $user->stats->total_income : 0) }}
                            </p>
                            <p class="text-sm text-gray-500">Total Income</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection