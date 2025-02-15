<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-[#ffffff] sm:bg-[#f3f3f3]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Find A Super Tutor') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body>
    <div id="app">
        <main>
            @yield('content')
        </main>
    </div>
    <script>
        function openNav() {
            document.getElementById('mobile-menu').style.width = '100%';
        }
        function closeNav() {
            document.getElementById('mobile-menu').style.width = '0';
        }
    </script>
</body>
</html>