@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    <h2>Add New invoice</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('invoices.index') }}"> Back</a>
                </div>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <img src="images/{{ Session::get('image') }}" class="mb-2" style="width:400px;height:200px;">
                @endif
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('invoices.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    Upload Foto Invoice
                </div>
                <div class="card-body">
                    {{-- @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <img src="images/{{ Session::get('image') }}" class="mb-2" style="width:400px;height:200px;">
            @endif --}}
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>
                                <label for="pr_type">No PO :</label>
                            </strong>

                            <select name="po_id" id="po_id"
                                class="js-example-basic-single form-control  @error('project') is-invalid @enderror">
                                <option value="">Pilih PO</option>
                                @foreach ($po as $po)
                                    <option value="{{ $po->id }}">{{ $po->po_no }}</option>
                                @endforeach
                            </select>


                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>Nama Penerima:</strong>
                            <input type="text" name="penerima" class="form-control" placeholder="nama penerima">
                            @error('penerima')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <form action="{{ route('invoices.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="inputImage">Select Invoice Image:</label>
                            <input type="file" name="image" id="inputImage"
                                class="form-control @error('image') is-invalid @enderror">

                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-success">Upload</button>
                        </div>

                    </form>
                    {{-- <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>
                        <label for="pr_type">No PO :</label>
                    </strong>

                    <select name="po_id" id="po_id"
                        class="form-control  @error('project') is-invalid @enderror">
                        <option value="">Pilih PO</option>
                        @foreach ($po as $po)
                            <option value="{{ $po->id }}">{{ $po->po_no }}</option>
                        @endforeach
                    </select>


                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Foto Invoice:</strong>
                    <input type="file" name="foto_invoice" class="form-control" placeholder="foto invoice">
                    @error('foto_invoice')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Nama Penerima:</strong>
                    <input type="text" name="penerima" class="form-control" placeholder="nama penerima">
                    @error('penerima')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                    @enderror
                </div>
            </div> --}}



                </div>

            </div>
            <div class="card-footer">
                {{-- <button type="submit" class="btn btn-primary ml-3">Submit</button> --}}

            </div>
    </div>

    {{-- </form> --}}
@endsection
{{-- </body>
</html> --}}
