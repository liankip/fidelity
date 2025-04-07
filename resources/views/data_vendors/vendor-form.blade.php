@extends('layouts.app')

@section('content')
     @if (session('success'))
          <div class="alert alert-success">
               {{ session('success') }}
          </div>
     @endif

     @if (session('error'))
          <div class="alert alert-danger">
               {{ session('error') }}
          </div>
     @endif
     <div class=" col-md-6 mx-auto">
          <div class="text-center fw-bold fs-1">
               Form Pendaftaran Vendor
          </div>
          <div class="card mt-3">
               <div class="card-body">

                    <form method="POST" action="{{ route('vendors.storeData') }}" enctype="multipart/form-data">
                         @csrf
                         {{-- <input type="hidden" name="_token" value="YhV2OO5HCgo8WGica7Ps2ypj3uqc6NTa4ITPsvncs"> --}}
                         <div>
                              <h6>Informasi Badan Usaha </h6>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             Nama Badan Usaha
                                             <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <input class="form-control" name="name" required="required" value="">
                                   </div>
                              </div>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             Alamat
                                             <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <input class="form-control" name="address" required="required" value="">
                                   </div>
                              </div>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             Company Profile
                                             <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <input class="form-control" type="file" name="company_profile" required="required"
                                             accept=".pdf, .doc, .docx" value="">
                                   </div>
                              </div>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             Link Website Usaha
                                             <span class="text-muted text-sm">(Opsional)</span>
                                        </label>
                                        <input class="form-control" name="website_link" value="">
                                   </div>
                              </div>
                         </div>
                         <div class="mt-4">
                              <h6>Informasi Penanggung Jawab / Direktur </h6>
                              <div id="" class="form-text mb-3">
                                   Request For Quotation (RFQ) akan dikirimkan ke email penanggung jawab /
                                   direktur.
                              </div>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             KTP Penanggung Jawab
                                             <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <input class="form-control" name="ktp" required="required" type="file"
                                             accept="image/*" value="">
                                   </div>
                              </div>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             No Telp
                                             <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <input class="form-control" placeholder="628xxxxxxxx" name="telp"
                                             required="required" value="">
                                   </div>
                              </div>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             Email
                                             <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <input class="form-control" name="email" required="required" type="email"
                                             value="">
                                   </div>
                              </div>
                         </div>
                         <div class="mt-4">
                              <h6>Informasi Sales Person</h6>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             No Telp
                                             <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <input class="form-control" placeholder="628xxxxxxxx" name="sales_phone"
                                             required="required" value="">
                                   </div>
                              </div>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             Email
                                             <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <input class="form-control" name="sales_email" required="required" type="email"
                                             value="">
                                   </div>
                              </div>
                         </div>
                         <div class="mt-4">
                              <h6>Rekening Badan Usaha</h6>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             Nama Bank
                                             <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <input class="form-control" name="bank_name" required="required" value="">
                                   </div>
                              </div>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             Nomor Rekening
                                             <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <input class="form-control" name="account_number" required="required"
                                             value="">
                                   </div>
                              </div>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             Nama Pemilik Rekening
                                             <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <input class="form-control" name="bank_owner_name" required="required"
                                             value="">
                                   </div>
                                   <div id="" class="form-text">
                                        Nama pemilik rekening harus sesuai dengan nama badan usaha atau nama penanggung
                                        jawab.
                                   </div>
                              </div>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             Cabang Bank
                                             <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <input class="form-control" name="bank_branch" required="required"
                                             value="">
                                   </div>
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
                                        <input class="form-check-input" type="checkbox" value="7 Days"
                                             id="flexCheckChecked" name="top[]">
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
                              </div>
                         </div>
                         <div class="mt-4">
                              <h6>Dokumen Lainnya</h6>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             NPWP
                                             <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <input class="form-control" name="npwp_image" required="required" type="file"
                                             accept="image/*" value="">
                                   </div>
                              </div>
                              <div class="mb-3">
                                   <div>
                                        <label for="" class="form-label">
                                             Katalog Produk
                                             <span class="text-danger fw-bold">*</span>
                                        </label>
                                        <input class="form-control" name="product_catalogue" required="required"
                                             type="file" accept=".pdf, .doc, .docx" value="">
                                   </div>
                              </div>
                              <div class="mb-3">
                                   <label for="" class="form-label">Dokumen lainnya <span
                                             class="text-sm">(Opsional)</span></label>
                                   <input type="file" class="form-control" id="" aria-describedby=""
                                        accept="image/*, .pdf, .doc, .docx" name="documents[]" multiple="">
                              </div>
                         </div>
                         <div class="mt-4">
                              <h6>Daftar Barang <small>(opsional)</small></h6>
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
                                                            <div>
                                                                 <label for="" class="form-label">
                                                                      Nama Barang
                                                                 </label>
                                                                 <input class="form-control" name="item_name[]"
                                                                      value="">
                                                            </div>
                                                            <div>
                                                                 <label for="" class="form-label">
                                                                      Harga Barang
                                                                 </label>
                                                                 <input class="form-control" name="item_price[]"
                                                                      type="number" max="99999999" value="">
                                                            </div>
                                                            <div class="mb-4">
                                                                 <label for="" class="form-label">
                                                                      Notes
                                                                 </label>
                                                                 <input class="form-control" name="item_notes[]"
                                                                      value="" placeholder="Contoh : Sudah Termasuk Ongkos Kirim">
                                                            </div>
                                                            <div>
                                                                 <label for="" class="form-label">
                                                                      Sertifikat Barang
                                                                      <span class="text-muted text-sm">(Opsional)</span>
                                                                 </label>
                                                                 <input class="form-control" name="item_certificate[]"
                                                                      type="file" accept="image/*, .pdf, .doc, .docx"
                                                                      value="">
                                                            </div>
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
                                                       price: '',
                                                       notes:''
                                                  }],

                                                  addItem() {
                                                       this.items.push({
                                                            name: '',
                                                            price: '',
                                                            notes:''
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
@endsection
