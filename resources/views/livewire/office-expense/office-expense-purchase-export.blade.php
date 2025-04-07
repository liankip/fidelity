<div wire:ignore.self id="office-expense-purchase-export">
    <div class="modal-header">
        <h3>Pilih Tanggal</h3>
        <button type="button" class="btn-close" wire:click="$emitUp('closeModal')" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <div class="mb-3">
                <label for="star_date">Start Date</label>
                <input id="star_date" wire:model="start_date"
                    class="form-control @error('start_date') is-invalid @enderror" type="date" />
                @error('start_date')
                    <div class="text-danger"> {{ $message }} </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="end_date">End Date</label>
                <input id="end_date" wire:model="end_date" class="form-control @error('end_date') is-invalid @enderror"
                    type="date" />
                @error('end_date')
                    <div class="text-danger"> {{ $message }} </div>
                @enderror
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click="$emitUp('closeModal')"
            data-bs-dismiss="modal">Close
        </button>

        <button type="button" class="btn btn-success" wire:click="export()">Export Excel </button>
    </div>

    <script>
        window.addEventListener('closeModal', event => {
            $("#office-expense-purchase-export").modal('hide');
        })
    </script>
</div>
