{{-- <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Vendor Form - {{ config('app.company', 'SNE') }} - ERP</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
    </head>
    <body> --}}
        @extends('layouts.app')

@section('content')
        <div class="container mt-2">
        <div class="row">
        <div class="col-lg-12 margin-tb">
        <div class="pull-left">
        <h2>Edit Vendor</h2>
        </div>
        <div class="pull-right">
        <a class="btn btn-primary" href="{{ route('vendors.index') }}" enctype="multipart/form-data"> Back</a>
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
                <form action="{{ route('vendors.update',$vendor->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Vendor Name:</strong>
                                <input type="text" name="name" value="{{ $vendor->name }}" class="form-control" placeholder="Vendor Name">
                                @error('name')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>PIC:</strong>
                                <input type="text" name="pic" value="{{ $vendor->pic }}" class="form-control" placeholder="PIC">
                                @error('pic')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Term Of Payment:</strong>
                                <input type="text" name="term_of_payment" value="{{ $vendor->term_of_payment }}" class="form-control" placeholder="Term Of Payment">
                                @error('term_of_payment')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Email:</strong>
                                <input type="email" name="email" class="form-control" placeholder="Email" value="{{ $vendor->email }}">
                                @error('email')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Phone:</strong>
                                <input type="number" name="phone" class="form-control" placeholder="Phone" value="{{ $vendor->phone }}">
                                @error('phone')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Address:</strong>
                                <input type="text" name="address" value="{{ $vendor->address }}" class="form-control" placeholder="Address">
                                @error('address')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>City:</strong>
                                <input type="text" name="city" value="{{ $vendor->city }}" class="form-control" placeholder="City">
                                @error('city')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Province:</strong>
                                <input type="text" name="province" value="{{ $vendor->province }}" class="form-control" placeholder="Province">
                                @error('province')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Kode Pos:</strong>
                                <input type="number" name="post_code" value="{{ $vendor->post_code }}" class="form-control" placeholder="Kode Pos">
                                @error('post_code')
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
