{{-- <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit payment Form - {{ config('app.company', 'SNE') }} - ERP</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
    </head> --}}
    @extends('layouts.app')

@section('content')
    {{-- <body> --}}
        <div class="container mt-2">
        <div class="row">
        <div class="col-lg-12 margin-tb">
        <div class="pull-left">
        <h2>Edit Payment</h2>
        </div>
        <div class="pull-right">
        <a class="btn btn-primary" href="{{ route('payments.index') }}" enctype="multipart/form-data"> Back</a>
        </div>
        </div>
        </div>
        @if(session('status'))
        <div class="alert alert-success mb-1 mt-1">
        {{ session('status') }}
        </div>
        @endif
        <form action="{{ route('payments.update',$payment->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Payment Picture</strong>
                    <input type="file" name="payment_pict" value="{{ $payment->payment_pict }}" class="form-control" placeholder="payment pict">
                    @error('payment_pict')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>PO ID:</strong>
                    <input type="text" name="po_id" value="{{ $payment->po_id }}" class="form-control" placeholder="po id">
                    @error('po_id')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Status:</strong>
                    <input type="text" name="status" value="{{ $payment->status }}" class="form-control" placeholder="Status">
                    @error('status')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Notes:</strong>
                    <input type="text" name="notes" class="form-control" placeholder="notes" value="{{ $payment->notes }}">
                    @error('notes')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    {{-- <strong>Updated By:</strong> --}}
                    <input type="hidden" name="updated_by" value="{{ Auth::id() }}" class="form-control" placeholder="updated_by" >
                    @error('updated_by')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>


        <button type="submit" class="btn btn-primary ml-3">Submit</button>
        </div>
        </form>
        </div>
        @endsection
    {{-- </body>
</html> --}}
