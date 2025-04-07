<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.company', 'SNE') }} - ERP</title>


    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    {{-- <script src="https://kit.fontawesome.com/20a7009ed2.js" crossorigin="anonymous"></script> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="/assets/vendor/libs/jquery/jquery.js"></script>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>
    <!-- Or for RTL support -->
    <link rel="stylesheet" href="/assets/css/select2/css/select2.min.css">
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.6/r-2.5.0/sb-1.6.0/sp-2.2.0/datatables.min.css"
          rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css"/>

    <!-- Scripts -->
    <script src="/assets/js/select2/js/select2.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
    @stack('styles')
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/r-2.5.0/sb-1.6.0/sp-2.2.0/datatables.min.js"></script>

</head>

<body>
<div id="app">
    <x-vendors.sidebar/>
    <div id="main" class="layout-navbar min-vh-100">
        <x-vendors.topbar/>

        <div class="px-3 px-md-5 mt-2">
            {{ $slot }}
        </div>

    </div>
</div>

@livewireScripts
<livewire:common.modal/>

<script src="{{asset('assets/vendor/libs/popper/popper.js')}}"></script>
<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
