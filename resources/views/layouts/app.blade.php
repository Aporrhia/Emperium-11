<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Emperium 11</title>
        <link rel="icon" href="{{ asset('storage/logo/favicon.png') }}" type="image/png" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@sonner/toast@1.0.0/dist/sonner.min.js"></script>
        @vite('resources/css/app.css')
        @vite('resources/css/sonner.css')
    </head>
    <body class="bg-gray-100">
        @include('partials.navbar')
        @include('partials.banner')

        <div class="toast-container" id="toast-container"></div>

        <main>
            @if (session('success'))
                
            @endif
            @yield('content')
        </main>
        @if (session('success') || session('error'))
            @include('partials.toast')
        @endif
    </body>
</html>