<div>
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <a href="{{ route('sales.index') }}" class="third-color-sne"> <i
                            class="fa-solid fa-chevron-left fa-xs"></i> Back</a>

                    <h2 class="primary-color-sne">Create Sales</h2>
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
                    <div class="card mt-4 primary-box-shadow">
                        <div class="card-body">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="row mb-3">
                                    <div class="form-group col-md-12 mb-4">
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
                                    </div>

                                    <div class="form-group col-md-12 mb-4">
                                        <label for="address" class="form-label">Alamat<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="address" id="address" wire:model="address">
                                            <option value="" selected>Pilih Alamat</option>
                                            @foreach($addresses as $address)
                                                <option value="{{ $address->shipping_address }}">{{ $address->shipping_address }}
                                                    <br></option>
                                            @endforeach
                                        </select>
                                        @error('address')
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
                                                    <input type="text"
                                                            wire:model.defer="qty.{{ $index }}"
                                                            class="form-control"
                                                            placeholder="Quantity">
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
                                    </div>

                                    <div class="form-group col-md-12 mb-4">
                                        <label for="payment_method" class="form-label">Tata Cara Bayar<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="payment_method" id="payment_method"
                                                wire:model="payment_method">
                                            <option value="" selected>Pilih Tata Cara Bayar</option>
                                            <option value="Pembayaran dimuka">Pembayaran dimuka</option>
                                            <option value="Pelunasan setelah barang diterima">Pelunasan setelah barang
                                                diterima
                                            </option>
                                            <option value="Kredit 7 hari">Kredit 7 hari</option>
                                            <option value="Kredit 30 hari">Kredit 30 hari</option>
                                        </select>
                                        @error('payment_method')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-12 mb-4">
                                        <label for="notes" class="form-label">Notes Pembeli</label>
                                        <textarea rows="3" wire:model="notes" class="form-control"
                                                  placeholder="Notes Pembeli"></textarea>
                                        @error('notes')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-12 mb-4">
                                        <label for="finish_date" class="form-label">Tanggal Selesai<span
                                                class="text-danger">*</span></label>
                                        <input type="date" wire:model="finish_date" class="form-control"
                                               placeholder="Pilih Tanggal">
                                        @error('finish_date')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-12 mb-4">
                                        <label for="sales_person" class="form-label">Sales Person<span
                                                class="text-danger">*</span></label>
                                        <input type="text" wire:model="sales_person" class="form-control"
                                               placeholder="Sales Person">
                                        @error('sales_person')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('sales.index') }}" class="btn btn-danger">Cancel</a>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-floppy-disk me-2"></i>Save
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
