<div>
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <a href="{{ route('work-order.index') }}" class="third-color-sne"> <i
                            class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="primary-color-sne">Create Work Order</h2>
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

                <form wire:submit.prevent="insert">
                    <div class="card mt-5 primary-box-shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                                    <div class="form-group mb-4">
                                        <strong>No. Work Order<span class="text-danger">*</span></strong>
                                        <input type="text" wire:model="number" class="form-control" readonly disabled>
                                        @error('number')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- <div class="form-group col-md-12 mb-4">
                                        <label for="id_customer" class="form-label">Customer Name<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="id_customer" id="id_customer"
                                                wire:model="id_customer">
                                            <option value="" selected>Pilih Nama Customer</option>
                                            @foreach($customers as $c)
                                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_customer')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> --}}
                                    
                                    <div class="form-group col-md-12 mb-4">
                                        <label for="deadline" class="form-label">Tanggal Deadline <span class="text-danger">*</span></label>
                                            <input type="date" wire:model.defer="deadline_date" class="form-control">
                                            @error('deadline')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                      
                                    </div>

                                    <div class="form-group col-md-12 mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <strong>Product<span class="text-danger">*</span></strong>
                                            <a href="#" wire:click.prevent="addNewProduct" class="text-primary">
                                                + Tambah Produk
                                            </a>
                                        </div>

                                        @foreach ($product as $index => $productId)
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="w-75 me-2">
                                                    <select
                                                        class="form-select"
                                                        name="product_{{ $index }}"
                                                        id="product_{{ $index }}"
                                                        wire:model.defer="product.{{ $index }}"
                                                    >
                                                        <option value="" selected>Pilih Produk</option>
                                                        @foreach ($allProducts as $p)
                                                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error("product.$index")
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="w-25 me-2">
                                                    <input
                                                        type="text"
                                                        wire:model.defer="qty.{{ $index }}"
                                                        class="form-control"
                                                        placeholder="Quantity"
                                                    >
                                                    @error("qty.$index")
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <button class="btn btn-danger btn-sm ms-2"
                                                        wire:click.prevent="removeProduct({{ $index }})"
                                                    {{ count($product) == 1 ? 'disabled' : '' }}>
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        @endforeach

                                        @if ($errors->has('product'))
                                            <span class="text-danger">{{ $errors->first('product') }}</span>
                                        @endif
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-floppy-disk me-2"></i>Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
