<div wire:click="closeshowai" class="bg-dark opacity-25"
    style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed; z-index: 4;">
</div>
<div class="modal d-block" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Limit Pengajuan PO</h3>
                <button type="button" class="btn-close" wire:click="close_modal_po_limit" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center">
                    Limit pengajuan PO sudah mencapai batas maksimal, silahkan klik tombol dibawah ini untuk meminta pembuatan PO lebih banyak.
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-warning" type="submit" wire:click="request_po_limit">Request Penambahan Limit PO</button>
                <button class="btn btn-outline-danger" wire:click="close_modal_po_limit">close</button>
        </div>
    </div>
</div>
