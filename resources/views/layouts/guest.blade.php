<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}
    <title>{{ config('app.company', 'SNE') }} - ERP</title>


    {{-- //select2 --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    {{-- <link rel="stylesheet" href="/css/style.css"> --}}
    {{-- <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" /> --}}

    <!-- Core CSS -->
    {{-- <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" /> --}}

    <!-- Vendors CSS -->
    {{-- <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" /> --}}

    <!-- Page CSS -->

    <!-- Helpers -->
    {{-- <script src="../assets/vendor/js/helpers.js"></script> --}}

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    {{-- <script src="../assets/js/config.js"></script> --}}

    {{-- <script src="https://kit.fontawesome.com/20a7009ed2.js" crossorigin="anonymous"></script> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    @livewireStyles
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
</head>

<body>
    @livewireScripts
    {{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<x-livewire-alert::scripts />
<x-livewire-alert::flash /> --}}
    <div id="app">
        {{--        @include("components.navigation") --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="d-flex px-md-5 justify-content-between">
                <a href="{{ route('root') }}">
                    {{-- <svg class="fill-current h-8 w-8 mr-2" width="54" height="54" viewBox="0 0 54 54" xmlns="http://www.w3.org/2000/svg"><path d="M13.5 22.1c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05zM0 38.3c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05z"/></svg> --}}
                    {{-- {{ config('app.name', 'Laravel') }} - ERP --}}
                    @if (env('COMPANY') == 'SNE')
                        <img height="40px" src="{{ asset('logo/sne.png') }}">
                    @elseif(env('COMPANY') == 'NADIC')
                        <img height="40px" src="{{ asset('logo/nadic.png') }}">
                    @elseif(env('COMPANY') == 'SGE')
                        <img height="40px" src="{{ asset('logo/sge.png') }}">
                    @elseif(env('COMPANY') == 'SMI')
                        <img height="40px" src="{{ asset('logo/smi.png') }}">
                    @else
                        <img height="40px" src="{{ asset('logo/dev.png') }}">
                    @endif
                </a>
            </div>
        </nav>
        <main class="mt-5">
            <div class="px-1 px-md-5 mt-2">
                @yield('content')
            </div>
        </main>
    </div>


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    {{-- <script src="assets/vendor/libs/popper/popper.js"></script>
<script src="assets/vendor/js/bootstrap.js"></script>
<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script> --}}

    {{-- <script src="assets/vendor/js/menu.js"></script> --}}
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    {{-- <script src="assets/js/main.js"></script> --}}

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
