@props(['name', 'to' => '#', 'hasSub' => false])

<li class="submenu-item {{ request()->url() == $to ? 'active' : '' }}">
    <a href="{{$to}}">{{$slot}}</a>

    @if ($hasSub)
        <ul class="submenu">
            {{ $slot }}
        </ul>
    @endif
</li>
