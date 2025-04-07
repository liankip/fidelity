<div class="bg-black opacity-25" wire:click="toggleModalVoucher"
     style="height: 100%;width: 100%;left: 0;top: 0;overflow: hidden;position: fixed;z-index: 999"></div>
<div class="modal d-block" tabindex="-1" role="dialog" aria-labelledby="voucherModalLabel" aria-hidden="true">
    <div class="modal-dialog d-block">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="voucherModalLabel">Print Voucher</h1>
                <button type="button" class="btn-close" wire:click="toggleModalVoucher"></button>
            </div>
            <div class="modal-body">
                <label for="date_from">Tanggal</label>
                <input id="date_from" class="form-control" type="date" wire:model="voucherDate"/>
                @error('voucherDate')
                <div class="text-danger"> {{ $message }} </div>
                @enderror

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="toggleModalVoucher">Close
                </button>
                <button type="button" class="btn btn-primary" wire:click="print">Save changes</button>
            </div>
        </div>
    </div>
</div>

