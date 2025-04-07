<div wire:click="closeshowai" class="bg-dark opacity-50"
    style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;"></div>
<div class="modal d-block" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>PO sudah lebih dari 10 untuk hari ini !!</h3>
                <button type="button" class="btn-close" wire:click="closecc" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center">
                   Are you sure want to cancel pusrchase request ?
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('cancel_pr', $willdeletepr) }}" method="post">
                    @csrf
                    @method('put')
                    <button class="btn btn-warning" type="submit">Continue</button>
                </form>
                <button class="btn" wire:click="closecc">close</button>
            </div>

        </div>
    </div>
</div>
