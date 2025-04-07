@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    <a href="{{ route('delivery_services.index') }}" class="third-color-sne"> <I class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="primary-color-sne">Add New Delivery Service</h2>
                </div>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('delivery_services.store') }}" class="mt-5" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card primary-box-shadow ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nama Jasa Pengiriman<span class="text-danger">*</span></strong>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name')
                                    is-invalid
                                @enderror"
                                    placeholder="name">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Ground<span class="text-danger">*</span></strong>
                                <input type="text" name="ground" value="{{ old('ground') }}"
                                    class="form-control @error('ground')
                                    is-invalid
                                @enderror"
                                    placeholder="ground">
                                @error('ground')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Keterangan<span class="text-danger">*</span></strong>
                                <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                                    class="form-control @error('keterangan') is-invalid @enderror" placeholder="Keterangan">
                                @error('keterangan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary ml-3"><i class="fa-solid fa-floppy-disk pe-2"></i>Save</button>
                </div>
            </div>

        </form>
    </div>
@endsection
