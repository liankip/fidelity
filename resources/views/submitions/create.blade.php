@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    <h2></h2>
                </div>
                {{-- <div class="pull-right">
    <a class="btn btn-primary" href="{{ route('delivery_orders.index') }}"> Back</a>
    </div> --}}
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('submitions.store') }}" method="POST" enctype="multipart/form-data">

            @csrf
            <div class="row">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <img src="images/do/{{ Session::get('image') }}" class="mb-2" style="width:400px;height:200px;">
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4>Add New Submission | <a class="btn btn-primary" href="{{ route('submitions.index') }}"> Back</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Purchase Order:</strong>
                                {{-- <input type="text" name="po_id" class="form-control" placeholder="Purchase Order"> --}}
                                <select name="po_id" id="po_id" class="js-example-basic-single form-control">
                                    <option value="">Pilih PO</option>
                                    @foreach ($po as $val_po)
                                        <option value="{{ $val_po->id }}">
                                            {{ $val_po->po_no }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('po_id')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Item:</strong>
                                {{-- <input type="text" name="item_id" class="form-control" placeholder="Item" > --}}
                                <select name="item_id" id="item_id" class="js-example-basic-single form-control">
                                    <option value="">Pilih Item</option>
                                    @foreach ($item as $val_item)
                                        <option value="{{ $val_item->id }}">
                                            {{ $val_item->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_id')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Quantity / Jumlah Per Satuan:</strong>
                                <input type="number" name="qty" class="form-control" placeholder="Quantity / Jumlah">
                                @error('qty')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Delivery Order:</strong>
                                {{-- <input type="text" name="do_id" class="form-control" placeholder="Surat Jalan"> --}}
                                <select name="do_id" id="do_id" class="js-example-basic-single form-control">
                                    <option value="">Pilih Surat Jalan</option>
                                    @foreach ($do as $val_do)
                                        <option value="{{ $val_do->id }}">
                                            {{ $val_do->do_no }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('do_id')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nama Pengantar:</strong>
                                <input type="text" name="pic_pengantar" class="form-control"
                                    placeholder="Nama Pengantar">
                                @error('pic_pengantar')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Penerima:</strong>
                                <input type="text" name="penerima" class="form-control" placeholder="Nama Penerima">
                                @error('penerima')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                {{-- <strong>Created By:</strong> --}}
                                <input type="hidden" name="created_by" value="{{ Auth::id() }}" class="form-control"
                                    placeholder="created by" readonly="readonly">
                                @error('created_by')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @csrf
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="foto_barang">Arrived Image:</label>
                                <input type="file" id="foto_barang" name="foto_barang"
                                    class="form-control @error('foto_barang') is-invalid @enderror">

                                @error('foto_barang')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>


                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary ml-3">Submit</button>
                    </div>
                </div>




            </div>
        </form>
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
