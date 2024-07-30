<!DOCTYPE html>
<html lang="en">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>
        @if (trim($__env->yieldContent('title')))
        @yield('title') | {{ config('app.name', 'Laravel') }}
        @else
        {{ config('app.name', 'Laravel') }}
        @endif
    </title>
    <meta name="theme-color" content="#ffffff">
    @stack('before-styles')
    @vite('resources/sass/app.scss')
    @stack('after-styles')
    <style>
        .sidebar-nav .nav-link.active{
            color: #eaeaea;
            background-color: #4ead57;
        }
        .nav-link {
            color: #eaeaea !important;
        }
        .sidebar-nav .nav-link:hover{
            background-color: #4ead57;
        }
    </style>
</head>

<body>
    <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar" style="background-color: #496E4D;">
        <div class="sidebar-brand d-none d-md-flex">
            <img src="{{ asset('images/logo.png') }}" alt="logo kz" class="sidebar-brand-full w-100 h-100">
            <img src="{{ asset('images/logo.png') }}" alt="logo kz" class="sidebar-brand-narrow w-100 h-100">
        </div>
        @include('layouts.navigation')
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>
    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <!-- Header block -->
        @include('layouts.includes.header')
        <!-- / Header block -->

        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                <!-- Errors block -->
                @include('layouts.includes.errors')
                <!-- / Errors block -->
                <div class="mb-4">@yield('content')</div>
            </div>
        </div>

        <!-- Footer block -->
        @include('layouts.includes.footer')
        <!-- / Footer block -->
    </div>

    <!-- Scripts -->
    @stack('before-scripts')

    @vite('resources/js/app.js')

    @stack('after-scripts')
    <!-- / Scripts -->

</body>

</html>
