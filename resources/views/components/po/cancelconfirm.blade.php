<div wire:click="closeshowai" class="bg-dark opacity-25"
    style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed; z-index: 4;">
</div>
<div class="modal d-block" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Cancel Purchase Order !!</h3>
                <button type="button" class="btn-close" wire:click="closecc" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    Are you sure want to cancel Purchase Order '{{ $ponowilldelete }}'?
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('cancel', $willdeletepo) }}" method="post">
                    @csrf
                    @method('put')
                    <button class="btn btn-danger" type="submit">Continue</button>
                </form>
                <button class="btn" wire:click="closecc">close</button>
            </div>
        </div>
    </div>
</div>
