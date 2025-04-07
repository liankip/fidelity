@extends('layouts.app')

@section('content')
    {{-- <body> --}}
        <div class="container mt-2">
        <div class="row">
        <div class="col-lg-12 margin-tb">
        <div class="pull-left">
        <h2>Edit deliveryorder</h2>
        </div>
        <div class="pull-right">
        <a class="btn btn-primary" href="{{ route('delivery_orders.index') }}" enctype="multipart/form-data"> Back</a>
        </div>
        </div>
        </div>
        @if(session('status'))
        <div class="alert alert-success mb-1 mt-1">
        {{ session('status') }}
        </div>
        @endif
        <form action="{{ route('delivery_orders.update',$deliveryorder->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Nama Jasa Pengiriman</strong>
                    <input type="text" name="name" value="{{ $deliveryorder->name }}" class="form-control" placeholder="name">
                    @error('name')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Ground:</strong>
                    <input type="text" name="ground" value="{{ $deliveryorder->ground }}" class="form-control" placeholder="ground">
                    @error('ground')
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
