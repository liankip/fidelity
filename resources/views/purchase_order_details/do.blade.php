@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    <h2>Delivery Order</h2>
                    <hr>
                </div>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <img src="/images/do/{{ Session::get('image') }}" class="mb-2" style="width:400px;height:200px;">
        @endif

        <div class="card shadow-sm mt-5">
            {{-- <div class="card-header">
                <span><strong>Add New Delivery Order</strong></span>
            </div> --}}
            <form action="{{ route('do.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body py-4">

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Nomor Surat Jalan:</strong>
                            <input type="text" name="do_no" class="form-control @error('do_no') is-invalid @enderror" placeholder="Nomor Surat Jalan" value="{{ old('do_no') }}">
                            @error('do_no')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- comment sementara tidak urgent --}}
                    {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>
                                <label for="item_id">Item :</label>
                            </strong>
                            <br>
                            @foreach ($po_detail as $key => $val_detail)
                                <input type="checkbox" id="item_id" name="item_id" value="{{$val_detail->item_id}}">
                                <label for="item_id">{{$val_detail->item->name}} | {{$val_detail->item->type}}</label><br>
                            @endforeach
                    </div> --}}

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                        {{-- <div class="form-group">
                            <strong>
                                <label for="do_type">DO Type:</label>
                            </strong> --}}

                            <input readonly type="hidden" required name="do_type" class="form-control" placeholder="" value="Purchase Order">
                            <input hidden type="hidden" name="do_type" class="form-control" placeholder="" value="PO">

                            {{-- <select name="do_type" id="do_type"
                                class="js-example-basic-single form-control">
                                    @error('do_type') is-invalid @enderror
                                    <option value="">Pilih Type</option>
                                    <option value="PO">Purchase Order</option>
                                    <option value="GT">Gudang Transfer</option>
                                    <option value="IU">Inventory Usage</option>
                                </select> --}}
                        {{-- </div> --}}
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                        <div class="form-group">
                            <strong>
                                <label for="referensi">Nomor Referensi :</label>
                            </strong>
                            <input readonly type="text" name="referensi" class="form-control"
                                placeholder="Nomor Referensi" value="{{ $po->po_no }}">
                            <input hidden type="text" name="referensi" class="form-control"
                                placeholder="Nomor Referensi" value="{{ $po->po_no }}">
                        </div>
                    </div>

                    <input type="hidden" name="created_by" value="{{ Auth::id() }}" class="form-control" placeholder="created by" readonly="readonly">

                    @csrf
                    <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                        <div>
                            <strong><label for="inputImage">Delivery Order Image:</label></strong>
                            <input type="file" name="image" id="inputImage"
                                accept="image/png, image/jpeg, application/pdf"
                                class="form-control @error('image') is-invalid @enderror">

                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <a class="btn btn-danger" href="{{ route('po-detail', ['id' => $po->id])}}">Back</a>
                    <button type="submit" class="btn btn-primary ml-3">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
