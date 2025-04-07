<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <a href="{{ route('office-expense.item', ['office' => $office, 'purchase' => $purchase]) }}"
                    class="third-color-sne"> <i class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                <h2 class="primary-color-sne">Insert Office Expense Item</h2>
            </div>
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
    </div>

    <div class="card mt-5 primary-box-shadow">
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                    <div class="form-group">
                        <strong>Tanggal Pembelian <span class="text-danger">*</span></strong>
                        <input class="form-control" type="date" wire:model="purchase_date">
                        @error('purchase_date')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                    <div class="form-group">
                        <strong>Total Pengeluaran <span class="text-danger">*</span></strong>
                        <input class="form-control" type="number" wire:model="total_expense">
                        @error('total_expense')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Vendor <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Vendor" wire:model="receiver_name">
                        @error('receiver_name')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <strong>Nama Bank <span class="text-danger">*</span></strong>
                            <input type="text" class="form-control" placeholder="Nama Bank" wire:model="vendor">
                            @error('vendor')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <strong>Nomor Rekening <span class="text-danger">*</span></strong>
                            <input type="number" class="form-control" placeholder="Nomor Rekening"
                                wire:model="account_number">
                            @error('account_number')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 mt-4">
                    <div class="form-group">
                        <strong>Notes (Optional)</strong>
                        <textarea class="form-control" wire:model="notes"></textarea>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('office-expense.item', ['office' => $office, 'purchase' => $purchase]) }}"
                        class="btn btn-danger ml-3">Cancel</a>
                    <button class="btn btn-primary ml-3" wire:click="insert"><i
                            class="fa-solid fa-floppy-disk pe-2"></i>Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
