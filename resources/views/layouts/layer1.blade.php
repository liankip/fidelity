<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}
    <title>{{ config('app.company', 'SNE') }} - ERP</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../assets/js/config.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] {
            display: none;
        }
    </style>
</head>
<body >
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('#') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-wrench-adjustable-circle" viewBox="0 0 16 16">
                        <path d="M12.496 8a4.491 4.491 0 0 1-1.703 3.526L9.497 8.5l2.959-1.11c.027.2.04.403.04.61Z"/>
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0Zm-1 0a7 7 0 1 0-13.202 3.249l1.988-1.657a4.5 4.5 0 0 1 7.537-4.623L7.497 6.5l1 2.5 1.333 3.11c-.56.251-1.18.39-1.833.39a4.49 4.49 0 0 1-1.592-.29L4.747 14.2A7 7 0 0 0 15 8Zm-8.295.139a.25.25 0 0 0-.288-.376l-1.5.5.159.474.808-.27-.595.894a.25.25 0 0 0 .287.376l.808-.27-.595.894a.25.25 0 0 0 .287.376l1.5-.5-.159-.474-.808.27.596-.894a.25.25 0 0 0-.288-.376l-.808.27.596-.894Z"/>
                      </svg>
                    {{-- <svg class="fill-current h-8 w-8 mr-2" width="54" height="54" viewBox="0 0 54 54" xmlns="http://www.w3.org/2000/svg"><path d="M13.5 22.1c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05zM0 38.3c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05z"/></svg> --}}
                    {{-- {{ config('app.name', 'Laravel') }} - ERP --}}
                    {{ config('app.company', 'SNE') }} - ERP
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @guest
                            {{-- @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif --}}

                            {{-- @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif --}}
                        @else
                        @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager' || auth()->user()->type =='purchasing')
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Approval
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                <a class="dropdown-item" href="{{url('aprv_waitinglists')}}">
                                    {{ __('Waiting List') }}
                                </a>
                                <a class="dropdown-item" href="{{url('aprv_histories')}}">
                                    {{ __('History') }}
                                </a>
                            </div>
                        </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Inventory
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @if (auth()->user()->type == 'it' || auth()->user()->type == 'adminlapangan' || auth()->user()->type == 'lapangan' || auth()->user()->type == 'manager' )
                                    <a class="dropdown-item" href="{{url('gudang_transfers')}}">
                                        {{ __('Gudang Transfer') }}
                                    </a>
                                    <a class="dropdown-item" href="{{url('inventory_usages')}}">
                                        {{ __('Inventory Usage') }}
                                    </a>
                                @endif

                                @if (auth()->user()->type == 'it' || auth()->user()->type == 'purchasing' || auth()->user()->type == 'adminlapangan' || auth()->user()->type == 'lapangan' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance')
                                    <a class="dropdown-item" href="{{url('stocks')}}">
                                        {{ __('Stock') }}
                                    </a>
                                @endif
                            </div>
                        </li>
                        @if (auth()->user()->type == 'it' || auth()->user()->type == 'purchasing' || auth()->user()->type == 'adminlapangan')
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Master Data
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                <a class="dropdown-item" href="{{url('items')}}">
                                    {{ __('Barang') }}
                                </a>
                                <a class="dropdown-item" href="{{url('delivery_services')}}">
                                    {{ __('Jasa Pengiriman') }}
                                </a>
                                <a class="dropdown-item" href="{{url('event_types')}}">
                                    {{ __('Jenis Notifikasi') }}
                                </a>
                                <a class="dropdown-item" href="{{url('paymentmetodes')}}">
                                    {{ __('Metode Pembayaran') }}
                                </a>
                                <a class="dropdown-item" href="{{url('prices')}}">
                                    {{ __('Price') }}
                                </a>
                                <a class="dropdown-item" href="{{url('projects')}}">
                                    {{ __('Project') }}
                                </a>
                                <a class="dropdown-item" href="{{url('suppliers')}}">
                                    {{ __('Supplier') }}
                                </a>
                                <a class="dropdown-item" href="{{url('warehouses')}}">
                                    {{ __('Warehouse') }}
                                </a>


                            </div>
                        </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Payable
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance')
                                    <a class="dropdown-item" href="{{url('#')}}">
                                        {{ __('Corporate Debt') }}
                                    </a>
                                @endif
                                @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance' || auth()->user()->type == 'purchasing')
                                    <a class="dropdown-item" href="{{url('delivery_orders')}}">
                                        {{ __('Delivery Order') }}
                                    </a>
                                @endif
                                @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance')
                                    <a class="dropdown-item" href="{{url('payments')}}">
                                        {{ __('Payment List') }}
                                    </a>
                                @endif
                                @if (auth()->user()->type == 'it' || auth()->user()->type == 'purchasing' || auth()->user()->type == 'adminlapangan' || auth()->user()->type == 'lapangan')
                                    <a class="dropdown-item" href="{{url('purchase_requests')}}">
                                        {{ __('Purchase Request') }}
                                    </a>
                                @endif
                                @if (auth()->user()->type == 'it' || auth()->user()->type == 'purchasing' || auth()->user()->type == 'adminlapangan' || auth()->user()->type == 'lapangan' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance')
                                    <a class="dropdown-item" href="{{url('purchase-orders')}}">
                                        {{ __('Purchase Order') }}
                                    </a>
                                    <a class="dropdown-item" href="{{url('returs')}}">
                                        {{ __('Retur') }}
                                    </a>
                                @endif

                            </div>
                        </li>
                        @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance')
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Receivable
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{url('#')}}">
                                    {{ __('Accounts Receivable') }}
                                </a>
                            </div>
                        </li>
                        @endif

                        @endguest


                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <!-- Authentication Links -->
                        @guest
                            {{-- @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif --}}

                            {{-- @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif --}}
                        @else

                        {{-- <li lass="nav-item">
                            @if (auth()->user()->type == 'it' || auth()->user()->type == 'adminlapangan')
                            <a class="nav-link" href="{{route('cart.list')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
                                    <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"/>
                                    <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"/>
                                  </svg>


                                {{ Cart::getTotalQuantity()}}
                            </a>
                            @endif
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('notifications')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                                (0)
                            </a>
                        </li>

                            <li class="nav-item dropdown">
                                <a  id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{url('profiles')}}">
                                        {{ __('Profile') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>


                                </div>
                            </li>

                        @endguest
                    </ul>
                    <ul class="navbar-nav mt-2 mt-lg-0">

                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container mt-2">
                @yield('content')
            </div>

        </main>
    </div>
    @livewireScripts

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>
