@props(['name'])
<li class="sidebar-item has-sub">
    <a href="#" class='sidebar-link'>
        <div style="width: 25px;">
            {{ $icon }}
        </div>
        <span>{{ $name }}</span>
    </a>
    <ul class="submenu">
        {{ $slot }}
    </ul>
</li>
