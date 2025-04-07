<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{url('/')}}">
                        @if (env('COMPANY') == 'SNE')
                            <img style="height:35px" src="{{ asset('logo/sne.png') }}">
                        @elseif(env('COMPANY') == 'NADIC')
                            <img style="height:35px" src="{{ asset('logo/nadic.png') }}">
                        @else
                            <img style="height:35px" src="{{ asset('logo/dev.png') }}">
                        @endif
                    </a>
                </div>
                <div class="theme-toggle d-flex gap-2  align-items-center mt-2">

                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input  me-0" type="checkbox" id="toggle-dark"
                               style="display: none">
                        <label class="form-check-label"></label>
                    </div>
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="fas fa-x  fs-3"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <x-navlink.navlink-basic name="Dashboard" :href="route('vendors.dashboard')">
                    <x-slot:icon>
                        <i class="fas fa-home"></i>
                    </x-slot:icon>
                </x-navlink.navlink-basic>
                <x-navlink.navlink-basic name="Items" :href="route('vendors.items')">
                    <x-slot:icon>
                        <i class="fas fa-box"></i>
                    </x-slot:icon>
                </x-navlink.navlink-basic>
            </ul>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var active = $('.submenu-item.active')
            active.parent().addClass('active');
            active.parent().parent().addClass('active');
        });
    </script>

</div>

