{{-- <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add paymentmetode Form - {{ config('app.company', 'SNE') }} - ERP</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
</head>
<body> --}}
    @extends('layouts.app')

@section('content')
<div class="container mt-2">
<div class="row">
<div class="col-lg-12 margin-tb">
<div class="pull-left mb-2">
<h2>Add New paymentmetode</h2>
</div>
<div class="pull-right">

</div>
</div>
</div>
@if(session('status'))
<div class="alert alert-success mb-1 mt-1">
{{ session('status') }}
</div>
@endif
<form action="{{ route('paymentmetodes.store') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="card">
    <div class="card-header">
        <a class="btn btn-primary" href="{{ route('paymentmetodes.index') }}"> Back</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>paymentmetode Name:</strong>
                    <input type="text" name="metode" class="form-control" placeholder="paymentmetode Name">
                    @error('metode')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>ToP:</strong>
                    <input type="text" name="term_of_payment" class="form-control" placeholder="term of payment">
                    @error('term_of_payment')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

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
    <div class="card-footer">
        <button type="submit" class="btn btn-primary ml-3">Submit</button>
    </div>
</div>

</form>
@endsection
{{-- </body>
</html> --}}
