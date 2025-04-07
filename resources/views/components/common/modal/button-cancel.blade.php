@props([
    'text' => 'Close',
    'class' => null,
])
<button type="button" class="{{ $class ? $class : 'btn btn-secondary' }}"
    data-bs-dismiss="modal">{{ $text }}</button>
