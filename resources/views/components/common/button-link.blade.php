@props(['route', 'color' => 'primary'])
<a {{ $attributes->merge(['class' => 'btn btn-' . $color]) }} href="{{ $route }}" {{ $attributes }}>
    {{ $slot }}
</a>
