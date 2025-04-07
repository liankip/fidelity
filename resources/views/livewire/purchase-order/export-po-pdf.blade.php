<div>
    <div class="modal-header">
        <h3>Choose Date</h3>
        <button type="button" class="btn-close" wire:click="$emitUp('closeModal')" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <label for="date_from">Start Date</label>
        <input id="date_from" wire:model="date_from" class="form-control @error('date_from') is-invalid @enderror"
            type="date" />
        @error('date_from')
            <div class="text-danger"> {{ $message }} </div>
        @enderror
        <label for="date_to">End Date</label>
        <input id="date_to" wire:model="date_to" class="form-control @error('date_to') is-invalid @enderror"
            type="date" />
        @error('date_to')
            <div class="text-danger"> {{ $message }} </div>
        @enderror

        @foreach (['danger', 'warning', 'success', 'info'] as $key)
            @if (Session::has($key))
                <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                    {{ Session::get($key) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @endif
        @endforeach
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" wire:click="$emitUp('closeModal')"
            data-bs-dismiss="modal">Close
        </button>

        <button class="btn btn-success btn-sm" wire:click="generateExcel" wire:loading.attr="disabled">
            <span wire:loading.remove>Download Report</span>
            <div wire:loading>
                Generate Excel...
            </div>
        </button>
    </div>
</div>
