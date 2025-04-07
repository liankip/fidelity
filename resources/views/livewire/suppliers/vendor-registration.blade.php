<div class="container">
    <div class="row justify-content-center">
        <div class=" col-md-6">
            @foreach (['danger', 'warning', 'success', 'info'] as $key)
                @if (Session::has($key))
                    <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                        {{ Session::get($key) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                @endif
            @endforeach
            <div class="text-center fw-bold fs-1">
                Form Pendaftaran Vendor
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <form method="POST" action="{{ route('vendors.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nama-bada-usaha" class="form-label">Nama Badan Usaha <span
                                    class="text-danger fw-bold">*</span> </label>
                            <input required type="text" class="form-control" id="nama-badan-usaha" name="nama" req>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">NPWP <span
                                    class="text-danger fw-bold">*</span></label>
                            <input required type="text" class="form-control" name="npwp">
                        </div>
                        <div class="mt-4">
                            <h6>Informasi Penanggung Jawab </h6>
                            <div class="mb-3">
                                <label for="" class="form-label">KTP Penanggung Jawab <span
                                        class="text-danger fw-bold">*</span></label>
                                <input required type="file" class="form-control" id=""
                                       aria-describedby="" name="ktp" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">No Telp <span
                                        class="text-danger fw-bold">*</span></label>
                                <input required type="text" class="form-control" id=""
                                       aria-describedby="" name="telp">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Email <span
                                        class="text-danger fw-bold">*</span></label>
                                <input required type="email" class="form-control" id=""
                                       aria-describedby="" name="email">
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6>Rekening Badan Usaha</h6>
                            <div class="mb-3">
                                <label for="" class="form-label">Nama Bank <span
                                        class="text-danger fw-bold">*</span></label>
                                <input required type="text" class="form-control" id=""
                                       aria-describedby="" name="bank_name">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Nomor Rekening <span
                                        class="text-danger fw-bold">*</span></label>
                                <input required type="text" class="form-control" id=""
                                       aria-describedby="" name="account_number">
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Nama Pemilik Rekening <span
                                        class="text-danger fw-bold">*</span></label>
                                <input required type="text" class="form-control" id=""
                                       aria-describedby="" name="bank_owner_name">
                                <div id="" class="form-text">
                                    Nama pemilik rekening harus sesuai dengan nama badan usaha
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Cabang <span
                                        class="text-danger fw-bold">*</span></label>
                                <input required type="text" class="form-control" id=""
                                       aria-describedby="" name="bank_branch">
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6>Payment</h6>
                            <div class="mb-3">
                                <label for="" class="form-label">Term of Payment <span
                                        class="text-danger fw-bold">*</span></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"
                                           name="top">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        30 Days
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"
                                           name="top">
                                    <label class="form-check-label" for="flexCheckChecked">
                                        7 Days
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"
                                           name="top">
                                    <label class="form-check-label" for="flexCheckChecked">
                                        1 Days
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6>Dokumen Usaha Lainnya</h6>
                            <div class="mb-3">
                                <label for="" class="form-label">Dokumen <span
                                        class="text-sm">(Opsional)</span></label>
                                <input type="file" class="form-control" id=""
                                       aria-describedby="" accept="image/*" multiple>
                            </div>
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
