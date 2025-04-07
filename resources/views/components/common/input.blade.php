@props(['label', 'optional' => false])
@php
    $attributes = $attributes->class(['form-control', 'is-invalid' => $errors->has($attributes->get('name'))]);
@endphp
<div>
    @if (isset($label))
        <label for="" class="form-label">
            {{ $label }}
            @if ($optional)
                <span class="text-muted text-sm">(Opsional)</span>
            @endif
            @if ($attributes->has('required'))
                <span class="text-danger fw-bold">*</span>
            @endif
        </label>
    @endif
    <input {{ $attributes }} value="{{ old($attributes->get('name')) }}">
    @error($attributes->get('name'))
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
