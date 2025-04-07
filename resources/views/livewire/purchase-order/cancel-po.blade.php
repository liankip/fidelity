<div>
    <div class="modal-header">
        <h5>Cancel Purchase Order!</h5>
        <button type="button" class="btn-close" wire:click="$emitUp('closeModal')" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            @if($po_no !== "")
                Are you sure want to cancel Purchase Order <b>{{ $po_no }}</b>?
            @else
                Are you sure want to cancel this Purchase Order?
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <form action="{{ route('cancel', $po_id) }}" method="post">
            @csrf
            @method('put')
            <button class="btn btn-danger" type="submit">Continue</button>
        </form>
        <button class="btn btn-light" wire:click="$emitUp('closeModal')">Close</button>
    </div>
</div>
