@props([
    'id' => '',
    'isCentered' => false,
    'title' => '',
])
<div wire:ignore class="modal fade {{ $isCentered ? 'modal-dialog-centered' : '' }}" id="{{ $id }}"
    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="{{ $id }}Label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="{{ $id }}Label">
                    {{ $title }}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $modalBody }}
            </div>
            <div class="modal-footer">
                {{ $modalFooter }}
            </div>
        </div>
    </div>
</div>
