@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    <h2>Upload Invoice</h2>
                    <hr>
                </div>
                <div class="pull-right">
                </div>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <img src="images/invoices/{{ Session::get('image') }}" class="mb-2" style="width:400px;height:200px;">
                @endif
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        @csrf
        <div class="card mt-5">
            <form action="{{ route('inv.store') }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>
                                <label for="pr_type">No PO :</label>
                            </strong>

                            <select name="po_id" id="po_id" class="js-example-basic-single form-control">

                                <option value="">Pilih PO</option>
                                @foreach ($po as $po)
                                    <option value="{{ $po->id }}">{{ $po->po_no }}</option>
                                @endforeach
                            </select>
                            @error('po_id')
                                <span class="text-danger">This field is required.</span>
                            @enderror


                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>
                                Recipient Name :
                            </strong>
                            <input type="text" name="penerima" class="form-control" placeholder="Nama Penerima">
                            @error('penerima')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @csrf

                    <div class="mb-3">
                        <label class="form-label" for="inputImage">
                            <strong>
                                Select Invoice Image:
                            </strong>
                        </label>
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
        </div>

    </div>
    <div class="card-footer">

    </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#po_id').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
@endsection
