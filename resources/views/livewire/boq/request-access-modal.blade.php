<div>
    <div class="modal-header">
        <h5>
            {{ $title }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="$emitUp('closeModal')"
            aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <p>
            {{ $content }}
        </p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click="$emitUp('closeModal')"
            data-bs-dismiss="modal">Batal</button>

        <button type="button" class="btn btn-success" wire:click="requestAccessAdendum" data-bs-dismiss="modal">Minta
            Akses</button>
    </div>
</div>
