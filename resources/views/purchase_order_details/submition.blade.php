@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    <h2></h2>
                </div>

            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif

        <div class="row">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="row">
                    <div class="col-3"><img src="/images/arrived/front/{{ Session::get('image') }}" class="mb-2 w-100"
                            style="width:400px;height:200px;"></div>
                    <div class="col-3"><img src="/images/arrived/left/{{ Session::get('image2') }}" class="mb-2 w-100"
                            style="width:400px;height:200px;"></div>
                    <div class="col-3"><img src="/images/arrived/right/{{ Session::get('image3') }}" class="mb-2 w-100"
                            style="width:400px;height:200px;"></div>
                    <div class="col-3"><img src="/images/arrived/back/{{ Session::get('image4') }}" class="mb-2 w-100"
                            style="width:400px;height:200px;"></div>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>Add New Submission</h4>
                    <hr class="mb-5">
                    <a class="btn btn-primary" href="{{ route('po-detail', $po->id) }}"> Back</a>
                </div>
                <form action="{{ route('submitions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" name="podetail_id" value="{{ $purchaseorderdetail->id }}">

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Purchase Order:</strong>
                                <input readonly type="text" name="po_id" class="form-control"
                                    value="{{ $po->po_no }}">
                                <input hidden type="text" name="po_id" class="form-control"
                                    value="{{ $po->id }}">

                                @error('po_id')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Item:</strong>
                                <input readonly type="text" name="item_id" class="form-control"
                                    value="{{ $item->name }}">
                                <input hidden type="text" name="item_id" class="form-control"
                                    value="{{ $item->id }}">

                                @error('item_id')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Quantity / Jumlah Per Satuan:<span class="text-danger">*</span></strong>
                                <input type="number"
                                    value="{{ old('qty') ?? round(floatval($purchaseorderdetail->qty)) }}" name="qty"
                                    class="form-control @error('qty') is-invalid @enderror"
                                    placeholder="Jumlah barang yang sampai" required>
                                @error('qty')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Delivery Order:<span class="text-danger">*</span></strong>
                                @if ($count_do > 0)
                                    <select name="do_id" id="do_id"
                                        class="js-example-basic-single @error('do_id') is-invalid @enderror form-control"
                                        required>
                                        <option value="">Pilih Surat Jalan</option>
                                        @foreach ($do as $val_do)
                                            <option {{ old('do_id') == $val_do->id ? 'selected' : '' }}
                                                value="{{ $val_do->id }}">
                                                {{ $val_do->do_no }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <b class="text-danger">Silahkan Buat Surat Jalan Terlebih Dahulu.</b>
                                    <select name="do_id" id="do_id"
                                        class="js-example-basic-single @error('do_id') is-invalid @enderror form-control"
                                        required>
                                        <option value="">Pilih Surat Jalan</option>
                                        @foreach ($do as $val_do)
                                            <option value="{{ $val_do->id }}">
                                                {{ $val_do->do_no }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                                @error('do_id')
                                    <div class="text-danger">The Delivery Order field is required.</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nama Pengantar:<span class="text-danger">*</span></strong>
                                <input type="text" name="pic_pengantar"
                                    class="form-control @error('pic_pengantar') is-invalid @enderror"
                                    value="{{ old('pic_pengantar') }}" placeholder="Nama Pengantar" required>
                                @error('pic_pengantar')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nama Penerima:<span class="text-danger">*</span></strong>
                                <input type="text" name="penerima" value="{{ old('penerima') }}"
                                    class="form-control @error('penerima')
                                is-invalid
                            @enderror"
                                    placeholder="Nama Penerima" required>
                                @error('penerima')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="created_by" value="{{ Auth::id() }}"
                                    class="form-control" placeholder="created by" readonly="readonly">
                                @error('created_by')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @csrf
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="inputImage"><strong>Foto Barang:<span
                                            class="text-danger">*</span></strong></label>
                                <input type="file" accept="image/png, image/jpeg, application/pdf" name="foto_barang"
                                    id="inputImage" class="form-control @error('image') is-invalid @enderror" required>

                                @error('foto_barang')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="inputImage"><strong>Tanggal Barang Sampai:<span
                                            class="text-danger">*</span></strong></label>
                                <input type="date" name="actual_date" class="form-control" required>
                                @error('actual_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary ml-3">Submit</button>
                    </div>
                </form>
            </div>


        </div>

        <script>
            $(document).ready(function() {
                $('#po_id').select2({
                    theme: 'bootstrap-5'
                });
                $('#item_id').select2({
                    theme: 'bootstrap-5'
                });
                $('#do_id').select2({
                    theme: 'bootstrap-5'
                });
            });
        </script>
    @endsection
