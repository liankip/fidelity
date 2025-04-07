{{-- <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add purchaserequestdetail Form - {{ config('app.company', 'SNE') }} - ERP</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
</head>
<body> --}}
    @extends('layouts.app')

@section('content')
<div class="container mt-2">
<div class="row">
<div class="col-lg-12 margin-tb">
<div class="pull-left mb-2">
<h2>Add New Purchase Request</h2>
</div>
<div class="pull-right">
<a class="btn btn-primary" href="{{ route('purchase_request_details.index') }}"> Back</a>
</div>
</div>
</div>
@if(session('status'))
<div class="alert alert-success mb-1 mt-1">
{{ session('status') }}
</div>
@endif
<form action="{{ route('purchase_request_details.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="card">
    <div class="card-header">

    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>PR ID:</strong>
                    {{-- <input type="text" name="pr_id" class="form-control" placeholder="pr id"> --}}
                    <select name="pr_id" id="pr_id"
                    class="js-example-basic-single form-control  @error('purchaserequest') is-invalid @enderror">
                        <option value="">Pilih PR</option>
                        @foreach ($purchaserequest as $purchaserequest)
                            <option value="{{ $purchaserequest->id }}">{{ $purchaserequest->pr_no }}</option>
                        @endforeach
                    </select>
                    @error('pr_id')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Item:</strong>
                    <select name="item_id" id="item_id"
                    class="js-example-basic-single form-control  @error('item_id') is-invalid @enderror">
                        <option value="">Pilih Barang</option>
                        @foreach ($barang as $barang)
                        <option value="{{ $barang->id }}">{{ $barang->name }}</option>
                        {{-- <input type="hidden" name="item_name" value="{{ $barang->name }}" class="form-control" placeholder="item_name" > --}}
                        @endforeach
                    </select>
                    {{-- <input type="text" name="item_id" class="form-control" placeholder="Item id"> --}}
                    @error('item_id')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Item ID:</strong>
                    <input type="text" name="item_id" class="form-control" placeholder="Item id">
                    @error('item_id')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div> --}}

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Nama Barang:</strong>
                    <input type="text" name="item_name" class="form-control" placeholder="Nama Barang">
                    @error('item_name')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Type:</strong>
                    <input type="text" name="type" class="form-control" placeholder="Type">
                    @error('type')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Unit:</strong>
                    <input type="text" name="unit" class="form-control" placeholder="unit">
                    @error('unit')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Jumlah:</strong>
                    <input type="text" name="qty" class="form-control" placeholder="Jumlah">
                    @error('qty')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Notes:</strong>
                    <input type="text" name="remark" class="form-control" placeholder="Notes">
                    @error('remark')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    {{-- <strong>Created By:</strong> --}}
                    <input type="hidden" name="status" value="New" class="form-control" placeholder="status" >

                    <input type="hidden" name="created_by" value="{{ Auth::id()}}" class="form-control" placeholder="created by" >
                    @error('created_by')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>



        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary ml-3">Submit</button>
    </div>
</div>

</form>
@endsection
{{-- </body>
</html> --}}
