<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
        @include('layouts.partials.header')
        <main class="py-4">
            @yield('content')
        </main>
        @include('layouts.partials.footer')
        @include('layouts.partials.underfooter')
    </div>
    <script>
        function openNav() {
            document.getElementById('mobile-menu').style.width = '100%';
        }
        function closeNav() {
            document.getElementById('mobile-menu').style.width = '0';
        }
        function searchTutor(num) {
            window.localStorage.setItem('search', JSON.stringify({
                subjectTaughtTypes: [num]
            }));
            window.location.href = '/search-tutors';
        }
        function searchOnlineTutor() {
            window.localStorage.setItem('search', JSON.stringify({
                locationTypes: ['online']
            }));
            window.location.href = '/search-tutors';
        }
    </script>
</body>
</html>