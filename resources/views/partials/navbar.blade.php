<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900">
                    <img src="{{ asset('storage/logo/logo.png') }}"
                        alt="Emperion 11 Logo"
                        class="mx-auto h-48 w-auto">
                </a>
            </div>

            <!-- Navigation Links and Profile Dropdown -->
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('races') }}" class="text-gray-900 hover:text-gray-500 px-3 py-2 rounded-md font-medium">
                        Horse Track
                    </a>

                    <a href="{{ route('properties.sell') }}" class="text-gray-900 hover:text-gray-500 px-3 py-2 rounded-md font-medium">
                        Sell Property
                    </a>

                    <a href="{{ route('tier.list') }}" class="text-gray-900 hover:text-gray-500 px-3 py-2 rounded-md font-medium">
                        Tier List
                    </a>
                @endauth

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button id="dropdownButton" class="flex items-center text-gray-900 hover:text-gray-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="ml-2">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                        @if (Auth::check())
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-900 hover:bg-gray-100">
                                Profile
                            </a>

                            <a href="{{ route('privacy.settings') }}" class="block px-4 py-2 text-gray-900 hover:bg-gray-100">
                                Privacy Settings
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-gray-900 hover:bg-gray-100">Sign Out</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-900 hover:bg-gray-100">Sign In</a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-gray-900 hover:bg-gray-100">Sign Up</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('dropdownButton').addEventListener('click', function () {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    });
    
    document.addEventListener('click', function (event) {
        const button = document.getElementById('dropdownButton');
        const menu = document.getElementById('dropdownMenu');
        if (!button.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>