<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="w-100 d-flex px-5 justify-content-between">
        <a class="navbar-brand" href="{{ route('root') }}">
            {{-- <svg class="fill-current h-8 w-8 mr-2" width="54" height="54" viewBox="0 0 54 54" xmlns="http://www.w3.org/2000/svg"><path d="M13.5 22.1c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05zM0 38.3c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05z"/></svg> --}}
            {{-- {{ config('app.name', 'Laravel') }} - ERP --}}
            @if (env('COMPANY') == 'SNE')
                <img height="35px" src="{{ asset('logo/sne.png') }}">
            @elseif(env('COMPANY') == 'NADIC')
                <img height="35px" src="{{ asset('logo/nadic.png') }}">
            @else
                <img height="35px" src="{{ asset('logo/dev.png') }}">
            @endif
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav me-auto">
                @auth
                    @if (auth()->user()->type == 'adminlapangan')
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Master Data
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('projects') }}">
                                    {{ __('Project') }}
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Purchases
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                @if (auth()->user()->type == 'it' ||
                                        auth()->user()->type == 'purchasing' ||
                                        auth()->user()->type == 'adminlapangan' ||
                                        auth()->user()->type == 'manager' ||
                                        auth()->user()->type == 'lapangan' ||
                                        auth()->user()->type == 'admin_2')
                                    <a class="dropdown-item" href="{{ url('purchase-requests') }}">
                                        {{ __('Purchase Request') }}
                                    </a>
                                @endif

                            </div>
                        </li>
                    @else
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

                        @if (auth()->user()->type == 'it' ||
                                auth()->user()->type == 'manager' ||
                                auth()->user()->type == 'purchasing' ||
                                auth()->user()->type == 'finance' ||
                                auth()->user()->type == 'admin_2')
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Approval
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('aprv_waitinglists') }}">
                                        {{ __('Waiting List') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('approval-histories') }}">
                                        {{ __('History') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ url('payment_list') }}">
                                        {{ __('Payment List') }}
                                    </a>
                                </div>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Log History
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('log-purchase') }}">
                                    {{ __('History Purchase') }}
                                </a>
                                {{-- </div><div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown"> --}}
                                <a class="dropdown-item" href="{{ url('log.payment') }}">
                                    {{ __('History Payment') }}
                                </a>
                            </div>
                        </li>
                        {{-- comment in go live --}}
                        {{-- <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Logistic
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            @if (auth()->user()->type == 'it' || auth()->user()->type == 'adminlapangan' || auth()->user()->type == 'lapangan' || auth()->user()->type == 'manager')
                                <a class="dropdown-item" href="{{ url('gudang_transfer_requests') }}">
                                    {{ __('Gudang Transfer Request') }}
                                </a>
                                <a class="dropdown-item" href="{{ url('gudang_transfers') }}">
                                    {{ __('Gudang Transfer') }}
                                </a>
                                <a class="dropdown-item" href="{{ url('inventory_usages') }}">
                                    {{ __('Inventory Usage') }}
                                </a>
                                <a class="dropdown-item" href="#">
                                    {{ __('Stock') }}
                                </a>
                            @endif

                        </div>

                    </li> --}}

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Purchases
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                {{-- @if (auth()->user()->type == 'it' || auth()->user()->type == 'adminlapangan' || auth()->user()->type == 'lapangan' || auth()->user()->type == 'manager' || auth()->user()->type == 'admin_2')
                                <a class="dropdown-item" href="{{ url('gudang_transfers') }}">
                                    {{ __('Gudang Transfer') }}
                                </a>
                                <a class="dropdown-item" href="{{ url('inventory_usages') }}">
                                    {{ __('Inventory Usage') }}
                                </a>
                            @endif --}}
                                @if (auth()->user()->type == 'it' ||
                                        auth()->user()->type == 'purchasing' ||
                                        auth()->user()->type == 'adminlapangan' ||
                                        auth()->user()->type == 'manager' ||
                                        auth()->user()->type == 'lapangan' ||
                                        auth()->user()->type == 'admin_2')
                                    <a class="dropdown-item" href="{{ url('purchase-requests') }}">
                                        {{ __('Purchase Request') }}
                                    </a>
                                @endif
                                @if (auth()->user()->type == 'it' ||
                                        auth()->user()->type == 'purchasing' ||
                                        auth()->user()->type == 'adminlapangan' ||
                                        auth()->user()->type == 'lapangan' ||
                                        auth()->user()->type == 'manager' ||
                                        auth()->user()->type == 'finance' ||
                                        auth()->user()->type == 'admin_2')
                                    <a class="dropdown-item" href="{{ url('purchase-orders') }}">
                                        {{ __('Purchase Order') }}
                                    </a>
                                    {{-- <a class="dropdown-item" href="{{ url('returs') }}">
                                    {{ __('Retur') }}
                                </a> --}}
                                @endif

                                {{-- @if (auth()->user()->type == 'it' || auth()->user()->type == 'purchasing' || auth()->user()->type == 'adminlapangan' || auth()->user()->type == 'lapangan' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance')
                                <a class="dropdown-item" href="{{ url('stocks') }}">
                                    {{ __('Stock') }}
                                </a>
                            @endif --}}
                            </div>
                        </li>
                        @if (auth()->user()->type == 'it' ||
                                auth()->user()->type == 'manager' ||
                                auth()->user()->type == 'purchasing' ||
                                auth()->user()->type == 'adminlapangan' ||
                                auth()->user()->type == 'finance' ||
                                auth()->user()->type == 'admin_2')
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Master Data
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item" href="{{ url('items') }}">
                                        {{ __('Item') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ url('delivery_services') }}">
                                        {{ __('Jasa Pengiriman') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ url('event_types') }}">
                                        {{ __('Jenis Notifikasi') }}
                                    </a>
                                    {{-- <a class="dropdown-item" href="{{ url('paymentmetodes') }}">
                                    {{ __('Metode Pembayaran') }}
                                </a> --}}
                                    <a class="dropdown-item" href="{{ url('projects') }}">
                                        {{ __('Project') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ url('prices') }}">
                                        {{ __('Price') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ url('suppliers') }}">
                                        {{ __('Supplier') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ url('warehouses') }}">
                                        {{ __('Warehouse') }}
                                    </a>


                                </div>
                            </li>
                        @endif

                        {{-- payable sementara di commnet --}}

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Payable
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance')
                                @endif
                                {{-- @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance' || auth()->user()->type == 'purchasing')
                                <a class="dropdown-item" href="{{ url('delivery_orders') }}">
                                    {{ __('Delivery Order') }}
                                </a>
                            @endif --}}
                                {{-- @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance')
                                <a class="dropdown-item" href="{{ url('payment_list_cash') }}">
                                    {{ __('Payment List Cash') }}
                                </a>
                            @endif
                            @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance')
                                <a class="dropdown-item" href="{{ url('payment_list_noncash') }}">
                                    {{ __('Payment List Non Cash') }}
                                </a>
                            @endif --}}
                                @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance')
                                    <a class="dropdown-item" href="{{ url('payment_waiting_lists') }}">
                                        {{ __('Payment Waiting List') }}
                                    </a>
                                @endif
                                @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance')
                                    <a class="dropdown-item" href="{{ route('payment.history') }}">
                                        {{ __('Payment History') }}
                                    </a>
                                @endif
                                @if (auth()->user()->type == 'it' ||
                                        auth()->user()->type == 'manager' ||
                                        auth()->user()->type == 'finance' ||
                                        auth()->user()->type == 'adminlapangan')
                                    <a class="dropdown-item" href="{{ url('invoices_index') }}">
                                        {{ __('Invoice List') }}
                                    </a>
                                @endif

                            </div>
                        </li>


                        {{-- @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance')
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Receivable
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('#') }}">
                                    {{ __('Accounts Receivable') }}
                                </a>
                            </div>
                        </li>
                    @endif --}}

                        {{-- @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager' || auth()->user()->type == 'finance')
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Direksi
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ url('concern_page') }}">
                                    {{ __('Concern List') }}
                                </a>
                                <a class="dropdown-item" href="{{ url('payment_list') }}">
                                    {{ __('Payment Waiting List') }}
                                </a>
                            </div>
                        </li>
                    @endif --}}
                        {{-- <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Validasi Berkas
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ url('upload-submition') }}">
                                {{ __('Validasi Berkas PO') }}
                            </a>
                            <a class="dropdown-item" href="{{ url('upload-submition') }}">
                                {{ __('Validasi Berkas GT') }}
                            </a>
                            <a class="dropdown-item" href="{{ url('upload-submition') }}">
                                {{ __('Validasi Berkas Usage') }}
                            </a>
                            <a class="dropdown-item" href="{{ url('upload-submition') }}">
                                {{ __('Validasi Berkas Retur') }}
                            </a>
                        </div>

                    </li> --}}
                    @endif
                @endauth


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
                    @if (auth()->user()->type == 'it' || auth()->user()->type == 'manager')
                        <li class="nav-item">
                            <a href="{{ route('settings') }}" class="nav-link position-relative me-3"><i
                                    class="fa-solid fa-gear fa-xl"></i></a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link position-relative me-3" href="{{ url('notifications') }}">
                            @php
                                $notif = count(
                                    auth()
                                        ->user()
                                        ->notification->where('read_at', null),
                                );
                            @endphp
                            @if ($notif)
                                @if ($notif < 99)
                                    <i class="fa-solid fa-bell fa-shake fa-xl"></i>
                                    <span
                                        class="position-absolute top-0 start-100 mt-2 me-1 translate-middle badge rounded-pill bg-danger">
                                        {{ $notif }}
                                        <span class="visually-hidden">Unread Messages</span>
                                    </span>
                                @else
                                    <i class="fa-solid fa-bell fa-shake fa-xl"></i>
                                    <span
                                        class="position-absolute top-0 start-100 mt-2 me-1 translate-middle badge rounded-pill bg-danger">
                                        99+
                                        <span class="visually-hidden">Unread Messages</span>
                                    </span>
                                @endif
                            @else
                                <i class="fa-regular fa-bell fa-xl"></i>
                                {{-- <span class="position-absolute top-0 start-100 mt-2 me-1 translate-middle badge rounded-pill bg-secondary">
                                    {{ $notif }}
                                    <span class="visually-hidden">Unread Messages</span>
                                </span> --}}
                            @endif
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ url('profiles') }}">
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
