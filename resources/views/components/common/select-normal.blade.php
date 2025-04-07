@props([
    'label',
])
<div class="form-group">
    <label for="">
        {{$label}}
        @if($attributes->has('required'))
            <span class="text-danger fw-bold">*</span>
        @endif
    </label>
    <select class="form-select rounded-3" aria-label="{{$label}}" {{$attributes}}>
        {{$slot}}
    </select>
</div>
