@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Privacy Settings</h1>

    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('privacy.settings.update') }}">
            @csrf
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Privacy Preferences</h2>
                <div class="space-y-4">
                    <!-- Show in Tier List -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="show_in_tier_list" {{ old('show_in_tier_list', Auth::user()->privacySetting->show_in_tier_list ?? true) ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <span class="ml-2 text-gray-700">Show me in the tier list</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">If unchecked, you will not appear in the public tier list.</p>
                    </div>

                    <!-- Hide Personal Info -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="hide_personal_info" {{ old('hide_personal_info', Auth::user()->privacySetting->hide_personal_info ?? false) ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <span class="ml-2 text-gray-700">Hide my personal information</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">If checked, only your username will be shown in the tier list (e.g., email and full name will be hidden).</p>
                    </div>
                </div>
            </div>

            <!-- Timezone Selection -->
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Timezone Preference</h2>
                <div class="space-y-4">
                    <div>
                        <label for="timezone" class="block text-gray-700 font-medium mb-2">
                            Select Your Timezone
                        </label>
                        <select name="timezone" id="timezone"
                                class="p-2 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm">
                            @foreach (DateTimeZone::listIdentifiers() as $tz)
                                <option value="{{ $tz }}" {{ Auth::user()->timezone === $tz ? 'selected' : '' }}>
                                    {{ $tz }}
                                </option>
                            @endforeach
                        </select>
                        @error('timezone')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>

    <div class="mt-6">
        <a href="{{ route('profile') }}" class="text-blue-600 hover:underline font-medium">
            ← Back to Profile
        </a>
    </div>
</div>
@endsection