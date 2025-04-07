<div>
    {{-- @hasanyrole('it|top-manager|manager|purchasing|finance')
    <button class="btn btn-primary btn-sm mb-3" type="button" wire:click="toggleModalVoucher">Print Voucher
    </button>
    @endhasanyrole --}}
    @if ($showModal)
        <x-voucher.print-modal />
    @endif
</div>
