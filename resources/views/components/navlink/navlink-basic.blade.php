@props(['name'])
<li class="sidebar-item {{ request()->url() == $attributes->get('href') ? 'active' : '' }}">
    <a {{ $attributes->merge(['class' => 'sidebar-link']) }}>
        <div style="width: 25px;">
            {{ $icon }}
        </div>
        <span>{{ $name }}</span>
    </a>
</li>
