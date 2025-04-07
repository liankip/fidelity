<div>
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <a href="{{ route('order.index', ['id' => $project_id]) }}" class="third-color-sne"> <i
                            class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="primary-color-sne">Insert Order</h2>
                </div>

                <livewire:common.alert />

                <div class="mt-5">
                    <div class="card primary-box-shadow">
                        <div class="card-body">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="nama" class="form-label">Produk</label>
                                    <select class="form-select" aria-label="Default select example"
                                        wire:model="product">
                                        <option value="">-- Product --</option>
                                        @foreach ($products as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('product')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="nama" class="form-label">Quantity</label>
                                    <input type="text" class="form-control" placeholder="Quantity"
                                        wire:model="quantity" aria-label="Quantity" aria-describedby="basic-addon1">
                                    @error('quantity')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-primary ml-3" id="save" wire:click="insert()"
                                wire:loading.attr="disabled" wire:loading.class="btn btn-primary ml-3 disabled">
                                <span wire:loading.remove>
                                    <i class="fa-solid fa-floppy-disk pe-2"></i>
                                    Save
                                </span>
                                <span wire:loading>
                                    <div class="spinner-border spinner-border-sm text-light" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </span>
                            </button>
                            <a href="{{ route('order.index', ['id' => $project_id]) }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
