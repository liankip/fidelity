{{-- <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit purchase_request Form - {{ config('app.company', 'SNE') }} - ERP</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
    </head> --}}
    @extends('layouts.app')

    @section('content')
        {{-- <body> --}}
            <div class="container mt-2">
            <div class="row">
            <div class="col-lg-12 margin-tb">
            <div class="pull-left">
            <h2>Edit purchase_request</h2>
            </div>
                <div class="pull-right">
                {{-- <a class="btn btn-primary" href="{{ route('purchase_requests.index') }}" enctype="multipart/form-data"> Back</a> --}}
                </div>
            </div>
            </div>
            @if(session('status'))
            <div class="alert alert-success mb-1 mt-1">
            {{ session('status') }}
            </div>
            @endif
            <div class="card">
                <div class="card-header"><a class="btn btn-primary" href="{{ route('purchase_requests.index') }}" enctype="multipart/form-data"> Back</a></div>
                <div class="card-body">
                    <form action="{{ route('purchase_requests.update',$purchase_request->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>purchase_request Code</strong>
                                    <input type="text" name="purchase_request_code" value="{{ $purchase_request->purchase_request_code }}" class="form-control" placeholder="purchase_request code">
                                    @error('purchase_request_code')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>purchase_request Name:</strong>
                                    <input type="text" name="name" value="{{ $purchase_request->name }}" class="form-control" placeholder="purchase_request name">
                                    @error('name')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Type:</strong>
                                    <input type="text" name="type" value="{{ $purchase_request->type }}" class="form-control" placeholder="Type">
                                    @error('type')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Unit:</strong>
                                    <input type="text" name="unit" class="form-control" placeholder="Unit" value="{{ $purchase_request->unit }}">
                                    @error('unit')
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
