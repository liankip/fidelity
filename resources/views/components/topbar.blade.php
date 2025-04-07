<header class="mb-3">
    <nav class="navbar navbar-expand navbar-light navbar-top">
        <div class="container-fluid">
            <a href="#" class="burger-btn d-block">
                <i class="fas fa-align-justify fs-3"></i>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-lg-0">
                    @hasanyrole('it')
                        <li class="nav-item">
                            <a href="{{ route('settings') }}" class="nav-link position-relative me-3"><i
                                    class="fa-solid fa-gear fa-xl"></i></a>
                        </li>
                    @endhasanyrole
                    <li class="nav-item">
                        <a class="nav-link position-relative me-3" href="{{ url('notifications') }}">
                            @php
                                $notif = count(auth()->user()->notification->where('read_at', null));
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
                </ul>
                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-menu d-flex">
                            <div class="user-name text-end me-3">
                                <h6 class="mb-0 text-gray-600">{{ Auth::user()->name }}</h6>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton"
                        style="min-width: 11rem;">
                        <li><a class="dropdown-item" href="{{ url('profiles') }}">
                                {{ __('Profile') }}
                            </a></li>
                        @hasrole('super-admin')
                            <li>
                                <a class="dropdown-item" href="{{ url('user-management') }}">
                                    {{ __('User Management') }}
                                </a>
                            </li>
                        @endhasrole
                        <li><a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
