@extends('layouts.app')

@section('content')
    {{-- <body> --}}
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <a href="{{ route('delivery_services.index') }}" class="third-color-sne"> <I class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="primary-color-sne">Edit Delivery Service</h2>
                </div>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ url('delivery_services', $deliveryservicedata->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card primary-box-shadow mt-5">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nama Jasa Pengiriman<span class="text-danger">*</span></strong>
                                <input type="text" name="name" value="{{ $deliveryservicedata->name }}"
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
                                <input type="text" name="ground" value="{{ $deliveryservicedata->ground }}"
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
                                <strong>Keterangan:</strong>
                                <input type="text" name="keterangan" value="{{ $deliveryservicedata->keterangan }}"
                                    class="form-control @error('keterangan')
                                        is-invalid
                                    @enderror"
                                    placeholder="Keterangan">
                                @error('keterangan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary ml-3">
                        <i class="fa-solid fa-floppy-disk pe-2"></i>
                        Save
                    </button>

                </div>
            </div>

        </form>
    </div>
@endsection
{{-- </body>
</html> --}}
