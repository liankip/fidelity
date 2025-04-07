@extends('layouts.guest')

{{-- @section('content') --}}
<!-- Content -->
@section('content')
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
                        <form method="POST" action="{{ route('vendors.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <h6>Informasi Badan Usaha </h6>
                                <div class="mb-3">
                                    <x-common.input label="Nama Badan Usaha" name="name" required />
                                </div>
                                <div class="mb-3">
                                    <x-common.input label="Alamat" name="address" required />
                                </div>
                                <div class="mb-3">
                                    <x-common.input type="file" label="Company Profile" name="company_profile" required
                                        accept=".pdf, .doc, .docx" />
                                </div>
                                <div class="mb-3">
                                    <x-common.input label="Link Website Usaha" name="website_link" optional="true" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <h6>Informasi Penanggung Jawab / Direktur </h6>
                                <div id="" class="form-text mb-3">
                                    Request For Quotation (RFQ) akan dikirimkan ke email penanggung jawab /
                                    direktur.
                                </div>
                                <div class="mb-3">
                                    <x-common.input label="KTP Penanggung Jawab" name="ktp" required type="file"
                                        accept="image/*" />
                                </div>
                                <div class="mb-3">
                                    <x-common.input placeholder="628xxxxxxxx" label="No Telp" name="telp" required />
                                </div>
                                <div class="mb-3">
                                    <x-common.input label="Email" name="email" required type="email" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <h6>Informasi Sales Person</h6>
                                <div class="mb-3">
                                    <x-common.input placeholder="628xxxxxxxx" label="No Telp" name="sales_phone" required />
                                </div>
                                <div class="mb-3">
                                    <x-common.input label="Email" name="sales_email" required type="email" />
                                </div>
                            </div>
                            <div class="mt-4">
                                <h6>Rekening Badan Usaha</h6>
                                <div class="mb-3">
                                    <x-common.input label="Nama Bank" name="bank_name" required />
                                </div>
                                <div class="mb-3">
                                    <x-common.input label="Nomor Rekening" name="account_number" required />
                                </div>
                                <div class="mb-3">
                                    <x-common.input label="Nama Pemilik Rekening" name="bank_owner_name" required />
                                    <div id="" class="form-text">
                                        Nama pemilik rekening harus sesuai dengan nama badan usaha atau nama penanggung
                                        jawab.
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <x-common.input label="Cabang Bank" name="bank_branch" required />
                                </div>
                            </div>
                            <div class="mt-4">
                                <h6>Payment</h6>
                                <div class="mb-3">
                                    <label for="" class="form-label">Term of Payment <span
                                            class="text-danger fw-bold">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="30 Days"
                                            id="flexCheckDefault" name="top[]">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            30 Days
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="7 Days" id="flexCheckChecked"
                                            name="top[]">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            7 Days
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Cash on Delivery (COD)"
                                            id="flexCheckChecked" name="top[]">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Cash on Delivery (COD)
                                        </label>
                                    </div>
                                    @error('top')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-4">
                                <h6>Dokumen Lainnya</h6>
                                <div class="mb-3">
                                    <x-common.input label="NPWP" name="npwp_image" required type="file"
                                        accept="image/*" />
                                </div>
                                <div class="mb-3">
                                    <x-common.input label="Katalog Produk" name="product_catalogue" required
                                        type="file" accept=".pdf, .doc, .docx" />
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Dokumen lainnya <span
                                            class="text-sm">(Opsional)</span></label>
                                    <input type="file" class="form-control" id="" aria-describedby=""
                                        accept="image/*, .pdf, .doc, .docx" name="documents[]" multiple>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h6>Daftar Barang</h6>
                                <div id="" class="form-text">
                                    Silahkan masukkan daftar barang yang ingin anda tawarkan.
                                </div>
                                <div class="mt-3" x-data="formData()">
                                    <table class="table table-auto table-borderless">
                                        <tbody>
                                            <template x-for="(item, index) in items" :key="index">
                                                <tr>
                                                    <td class="p-0 pt-3">
                                                        <template x-if="index != 0">
                                                            <div class="d-flex justify-content-end">
                                                                <button type="button" class="btn btn-danger btn-sm "
                                                                    @click="items.splice(index, 1)">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </template>
                                                        <x-common.input label="Nama Barang" name="item_name[]" required />
                                                        <x-common.input label="Notes" name="item_notes[]" type="text" required />
                                                        <x-common.input label="Harga Barang" name="item_price[]" required
                                                            type="number" max="99999999" />
                                                        <x-common.input label="Sertifikat Barang" optional="true"
                                                            name="item_certificate[]" type="file"
                                                            accept="image/*, .pdf, .doc, .docx" />
                                                    </td>

                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-success" id="btnAddItem"
                                            @click="addItem">
                                            Tambah Barang
                                        </button>
                                    </div>
                                    <script>
                                        function formData() {
                                            return {
                                                items: [{
                                                    name: '',
                                                    price: ''
                                                }],

                                                addItem() {
                                                    this.items.push({
                                                        name: '',
                                                        price: ''
                                                    })
                                                }
                                            }
                                        }
                                    </script>
                                </div>

                            </div>
                            <div>
                                <div id="" class="form-text">
                                    <i>
                                        NB: Dari dokumen penagihan diterima lengkap
                                    </i>
                                </div>
                            </div>
                            <div class="mt-3">
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
@endsection
