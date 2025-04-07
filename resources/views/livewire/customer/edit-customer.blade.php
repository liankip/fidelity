<div>
    <form wire:submit.prevent="edit" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mt-2">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <a href="{{ route('customer.index') }}" class="third-color-sne"> <i
                                class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                        <h2 class="primary-color-sne">Edit Customer</h2>
                    </div>
                </div>
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
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
                                <div class="form-group mb-4">
                                    <strong>Nama Customer</strong>
                                    <input type="text" wire:model="name" class="form-control"
                                        placeholder="Nama Customer">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <strong>NPWP</strong>
                                    <input type="text" wire:model="npwp" class="form-control" placeholder="NPWP">
                                    @error('npwp')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <strong>Alamat Kirim</strong>
                                    <textarea type="text" wire:model="shipping_address" class="form-control" placeholder="Alamat Kirim"></textarea>
                                    @error('shipping_address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4 mb-4">
                                    <label for="ktp" class="form-label">KTP (Optional)</label>
                                    <div class="file-upload">
                                        <label for="ktp-input" class="btn btn-outline-secondary">
                                            Browse File
                                        </label>
                                        <input type="file" wire:model="ktp" id="ktp-input" class="form-control">
                                    </div>

                                    <div wire:loading wire:target="ktp" class="mb-3">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                role="progressbar" style="width: 100%;"></div>
                                        </div>
                                        <p class="mt-2">Uploading...</p>
                                    </div>

                                    @if ($ktp)
                                        <img class="mt-2" src="{{ $ktp->temporaryUrl() }}" alt="Preview KTP"
                                            style="max-width: 100%; height: auto;">
                                    @elseif ($ktpUrl)
                                        <img class="mt-2" src="{{ $ktpUrl }}" alt="KTP Saat Ini"
                                            style="max-width: 100%; height: auto;">
                                    @else
                                        <p class="text-muted">KTP belum diunggah.</p>
                                    @endif
                                </div>

                                <div class="form-group mb-4">
                                    <strong>Nama PIC</strong>
                                    <input type="text" wire:model="pic_name" class="form-control"
                                        placeholder="Nama PIC">
                                    @error('pic_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <strong>No. Telp PIC</strong>
                                    <input type="text" wire:model="pic_phone" class="form-control"
                                        placeholder="No. Telp PIC">
                                    @error('pic_phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <strong>Email PIC</strong>
                                    <input type="text" wire:model="pic_email" class="form-control"
                                        placeholder="Email PIC">
                                    @error('pic_email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <strong>Nama Penerima</strong>
                                    <input type="text" wire:model="recipient_name" class="form-control"
                                        placeholder="Nama Penerima">
                                    @error('recipient_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <strong>No. Telp Penerima</strong>
                                    <input type="text" wire:model="recipient_phone" class="form-control"
                                        placeholder="No. Telp Penerima">
                                    @error('recipient_phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <strong>Alamat Penagihan</strong>
                                        <a href="#" wire:click.prevent="addNewBillingAddress"
                                            class="text-primary">
                                            + Tambah Alamat
                                        </a>
                                    </div>

                                    @foreach ($billing_address as $index => $address)
                                        <div class="d-flex align-items-start mb-2">
                                            <textarea wire:change="updateBillingAddress({{ $index }}, $event.target.value)" class="form-control mb-2"
                                                placeholder="Alamat Penagihan">{{ $address }}</textarea>
                                            <button type="button"
                                                wire:click="removeBillingAddress({{ $index }})"
                                                class="btn btn-danger btn-sm ms-2"
                                                {{ count($billing_address) == 1 ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach

                                    <input type="hidden" name="billing_address_json"
                                        value="{{ json_encode($billing_address) }}">
                                </div>

                                <div class="form-group mb-4">
                                    <strong>No. Telp Penagihan</strong>
                                    <input type="text" wire:model="billing_phone" class="form-control"
                                        placeholder="No. Telp Penagihan">
                                    @error('billing_phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <strong>Email Penagihan</strong>
                                    <input type="text" wire:model="billing_email" class="form-control"
                                        placeholder="Email Penagihan">
                                    @error('billing_email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('customer.index') }}" class="btn btn-danger">Cancel</a>

                            <button type="submit" class="btn btn-primary ml-3"><i
                                    class="fa-solid fa-plus pe-2"></i>Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </form>
</div>
