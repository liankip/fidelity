<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <x-layouts.head />

    <body>
        <div id="app">
            @include('components.sidebar')

            <div id="main" class="layout-navbar min-vh-100">
                @include('components.topbar')

                <div class="px-3 px-md-5 mt-2">
                    @yield('content')
                    {{ $slot }}
                </div>

            </div>
        </div>


        <!-- Core JS -->
        @stack('javascript')
        @livewireScripts
        <livewire:common.modal />

        <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
        <script async defer src="https://buttons.github.io/buttons.js"></script>
    </body>

</html>
