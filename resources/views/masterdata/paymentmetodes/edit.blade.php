{{-- <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Master Data Payment Metode Form - {{ config('app.company', 'SNE') }} - ERP</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
    </head>
    <body> --}}
        @extends('layouts.app')

@section('content')
        <div class="container mt-2">
        <div class="row">
        <div class="col-lg-12 margin-tb">
        <div class="pull-left">
        <h2>Edit Master Data Payment Metode</h2>
        </div>
        <div class="pull-right">
        <a class="btn btn-primary" href="{{ route('paymentmetodes.index') }}" enctype="multipart/form-data"> Back</a>
        </div>
        </div>
        </div>
        @if(session('status'))
        <div class="alert alert-success mb-1 mt-1">
        {{ session('status') }}
        </div>
        @endif
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                <form action="{{ route('paymentmetodes.update',$paymentmetode->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Payment Metode Name:</strong>
                                <input type="text" name="metode" value="{{ $paymentmetode->metode }}" class="form-control" placeholder="payment metode name">
                                @error('metode')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>ToP:</strong>
                                <input type="text" name="term_of_payment" value="{{ $paymentmetode->term_of_payment }}" class="form-control" placeholder="term of payment">
                                @error('term_of_payment')
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
            <div class="card-footer"></div>
        </div>

                </div>
        @endsection
    {{-- </body>
</html> --}}
