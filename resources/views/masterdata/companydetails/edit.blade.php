{{-- <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit companydetail Form - {{ config('app.company', 'SNE') }} - ERP</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
    </head>
    <body> --}}
@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Edit companydetail</h2>
                </div>
                <div class="pull-right">
                </div>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('companydetails.update', $companydetail->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="card">
                    <div class="card-header">

                        <a class="btn btn-primary" href="{{ route('companydetails.index') }}" enctype="multipart/form-data">
                            Back</a>
                    </div>
                    <div class="card-body">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>companydetail Name:</strong>
                                <input type="text" name="name" value="{{ $companydetail->name }}" class="form-control"
                                    placeholder="companydetail Name">
                                @error('name')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>PIC:</strong>
                                <input type="text" name="pic" value="{{ $companydetail->pic }}" class="form-control"
                                    placeholder="PIC">
                                @error('pic')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Email:</strong>
                                <input type="email" name="email" class="form-control" placeholder="Email"
                                    value="{{ $companydetail->email }}">
                                @error('email')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Phone:</strong>
                                <input type="number" name="phone" class="form-control" placeholder="Phone"
                                    value="{{ $companydetail->phone }}">
                                @error('phone')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Address:</strong>
                                <input type="text" name="address" value="{{ $companydetail->address }}"
                                    class="form-control" placeholder="Address">
                                @error('address')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>City:</strong>
                                <input type="text" name="city" value="{{ $companydetail->city }}"
                                    class="form-control" placeholder="City">
                                @error('city')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Province:</strong>
                                <input type="text" name="province" value="{{ $companydetail->province }}"
                                    class="form-control" placeholder="Province">
                                @error('province')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Kode Pos:</strong>
                                <input type="number" name="post_code" value="{{ $companydetail->post_code }}"
                                    class="form-control" placeholder="Kode Pos">
                                @error('post_code')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                {{-- <strong>Updated By:</strong> --}}
                                <input type="hidden" name="updated_by" value="{{ Auth::id() }}" class="form-control"
                                    placeholder="updated_by">
                                @error('updated_by')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
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
    </div>
@endsection
{{-- </body>
</html> --}}
