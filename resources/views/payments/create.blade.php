{{-- <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add payment Form - {{ config('app.company', 'SNE') }} - ERP</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
</head>
<body> --}}
    @extends('layouts.app')

@section('content')
<div class="container mt-2">
<div class="row">
<div class="col-lg-12 margin-tb">
<div class="pull-left mb-2">
{{-- <h2>Upload Payment Transfer Picture</h2> --}}
</div>
<div class="pull-right">
{{-- <a class="btn btn-primary" href="{{ route('payments.index') }}"> Back</a> --}}
</div>
</div>
</div>
@if(session('status'))
<div class="alert alert-success mb-1 mt-1">
{{ session('status') }}
</div>
@endif
<form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="row">
    <div class="card">
        <div class="card-header">
            <h4>Upload Bukti Pembayaran</h4>
        </div>
        <div class="card-body">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Payment Pict:</strong>
                    <input type="file" name="payment_pict" class="form-control" placeholder="payment pict">
                    @error('payment_pict')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>PO ID:</strong>
                    <input type="text" name="po_id" class="form-control" placeholder="po id">
                    @error('po_id')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Status:</strong>
                    <input type="text" name="status" class="form-control" placeholder="status">
                    @error('status')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div> --}}

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Note:</strong>
                    <input type="text" name="notes" class="form-control" placeholder="notes">
                    @error('notes')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    {{-- <strong>Created By:</strong> --}}
                    <input type="hidden" name="created_by" value="{{ Auth::id()}}" class="form-control" placeholder="created by" readonly="readonly">
                    @error('created_by')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>
    </div>



<button type="submit" class="btn btn-primary ml-3">Submit</button>
</div>
</form>
@endsection
{{-- </body>
</html> --}}
