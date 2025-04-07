<div>
    <div class="modal-header">
        <h5>
            Update Stock
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="$emitUp('closeModal')"
            aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <x-common.input label="Stock" type="number" name="selectedStock" wire:model='stock'
                max="{{ $maxStock }}" min="0" />
            @error('stock')
                <span class="text-danger">{{ $message }}</span>
            @enderror

        </div>
        <div class="form-group">
            <label for="note">Note</label>
            <textarea class="form-control" name="note" id="note" wire:model="notes" placeholder="Note"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click="$emitUp('closeModal')"
            data-bs-dismiss="modal">Batal</button>

        <button type="button" class="btn btn-success" wire:click="updateStock">Save</button>
    </div>
</div>
