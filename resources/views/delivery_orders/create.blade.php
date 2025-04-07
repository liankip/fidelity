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
                    <h4>Add New Delivery Order | <a class="btn btn-primary" href="{{ route('delivery_orders.index') }}">
                            Back</a></h4>
                </div>
                <form action="{{ route('do.store') }}" method="POST" enctype="multipart/form-data">

                    <div class="card-body">

                        @csrf
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nomor Surat Jalan:</strong>
                                <input type="text" name="do_no" class="form-control" placeholder="Nomor Surat Jalan">
                                @error('do_no')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>
                                    <label for="do_type">DO Type :</label>
                                </strong>

                                <select name="do_type" id="do_type" class="js-example-basic-single form-control">
                                    @error('do_type')
                                        is-invalid
                                    @enderror
                                    <option value="">Pilih Type</option>
                                    <option value="PO">Purchase Order</option>
                                    <option value="GT">Gudang Transfer</option>
                                    <option value="IU">Inventory Usage</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>
                                    <label for="referensi">Nomor Referensi :</label>
                                </strong>
                                <input type="text" name="referensi" class="form-control" placeholder="Nomor Referensi">

                                {{-- <select name="referensi" id="referensi"
                    class="js-example-basic-single form-control">
                        @error('referensi') is-invalid @enderror
                        <option value="">Pilih Nomor Referensi</option>

                    </select> --}}
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
                                <label class="form-label" for="inputImage">Delivery Order Image:</label>
                                <input type="file" name="image" id="inputImage"
                                    class="form-control @error('image') is-invalid @enderror">

                                @error('image')
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
    @endsection
