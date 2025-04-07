{{-- <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add warehouse Form - {{ config('app.company', 'SNE') }} - ERP</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
</head>
<body> --}}
@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <a href="{{ route('warehouses.index') }}" class="third-color-sne"> <i
                            class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="primary-color-sne">Add New warehouse</h2>
                </div>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('warehouses.store') }}" class="mt-5" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card primary-box-shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                            <div class="form-group">
                                <strong>warehouse Name<span class="text-danger">*</span></strong>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name')
                                    is-invalid
                                @enderror"
                                    placeholder="warehouse Name">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                            <div class="form-group">
                                <strong>PIC<span class="text-danger">*</span></strong>
                                <input type="text" name="pic" value="{{ old('PIC') }}"
                                    class="form-control @error('pic')
                                is-invalid
                            @enderror"
                                    placeholder="PIC">
                                @error('pic')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                            <div class="form-group">
                                <strong>Email<span class="text-danger">*</span></strong>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email')
                                is-invalid
                            @enderror"
                                    placeholder="Email">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                            <div class="form-group">
                                <strong>Phone<span class="text-danger">*</span></strong>
                                <input type="number" name="phone" value="{{ old('phone') }}"
                                    class="form-control @error('phone')
                                    is-invalid
                                @enderror"
                                    placeholder="Phone">
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                            <div class="form-group">
                                <strong>Address<span class="text-danger">*</span></strong>
                                <input type="text" name="address" value="{{ old('address') }}"
                                    class="form-control @error('address')
                                is-invalid
                            @enderror"
                                    placeholder="Address">
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                            <div class="form-group">
                                <strong>City<span class="text-danger">*</span></strong>
                                <input type="text" name="city" value="{{ old('city') }}"
                                    class="form-control @error('city')
                                is-invalid
                            @enderror"
                                    placeholder="City">
                                @error('city')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                            <div class="form-group">
                                <strong>Province<span class="text-danger">*</span></strong>
                                <input type="text" name="province" value="{{ old('province') }}"
                                    class="form-control @error('province')
                                is-invalid
                            @enderror"
                                    placeholder="Province">
                                @error('province')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mb-2">
                            <div class="form-group">
                                <strong>Kode Pos<span class="text-danger">*</span></strong>
                                <input type="number" name="post_code" value="{{ old('post_code') }}"
                                    class="form-control @error('post_code')
                                    is-invalid
                                @enderror"
                                    placeholder="Kode Pos">
                                @error('post_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary ml-3"><i class="fa-solid fa-floppy-disk pe-2"></i>Save</button>
                </div>
            </div>
        </form>
    @endsection
    {{-- </body>
</html> --}}
