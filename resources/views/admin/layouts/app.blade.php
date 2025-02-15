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
@php
    $currentRouteName = Route::currentRouteName();
@endphp
<body>
    <div class="flex flex-row py-4 px-6 bg-background gap-2">
        <div class="navbar w-full max-w-[260px] d-none d-sm-block">
            @include('admin.layouts.partials.nav')
        </div>
        <div id="app" class="relative w-full">
            @include('admin.layouts.partials.header')
            <main class="py-4">
                @yield('content')
            </main>
            @include('admin.layouts.partials.underfooter')
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(function(navItem) {
                navItem.addEventListener('click', function() {
                    let tmpContainer, tmpChevronIcon;
                    navItems.forEach(function(item) {
                        tmpContainer = item.closest('.nav-container');
                        tmpContainer.classList.remove('active');
                        tmpChevronIcon = tmpContainer.querySelector('i.chevron-icon');
                        if(tmpChevronIcon) {
                            tmpChevronIcon.classList.remove('mdi-chevron-down');
                            tmpChevronIcon.classList.add('mdi-chevron-left');    
                        }
                    });
                    const navContainer = this.closest('.nav-container');
                    navContainer.classList.add('active');
                    const chevronIcon = navContainer.querySelector('i.chevron-icon');
                    if(chevronIcon) {
                        chevronIcon.classList.remove('mdi-chevron-left');
                        chevronIcon.classList.add('mdi-chevron-down');
                    }
                })
            })
        })
        function openNav() {
            document.getElementById('mobile-menu').style.width = '100%';
        }
        function closeNav() {
            document.getElementById('mobile-menu').style.width = '0';
        }
    </script>
</body>
</html>