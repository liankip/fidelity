@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    <h2>
                        Upload Invoice
                    </h2>
                </div>
                <div class="pull-right">
                    {{-- <a class="btn btn-primary" href="{{ route('invoices.index') }}"> Back</a> --}}
                </div>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <img src="/images/invoices/{{ Session::get('image') }}" class="mb-2"
                        style="width:400px;height:200px;">
                @endif
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        @csrf
        <div class="card">
            <form action="{{ route('inv.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>
                                <label for="pr_type">No PO :</label>
                            </strong>
                            <input readonly type="text" name="po_id" class="form-control" value={{ $po->po_no }}>
                            <input hidden type="text" name="po_id" class="form-control" value={{ $po->id }}>
                        </div>
                    </div>
                    {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <strong>
                                Nama Penerima<span class="text-danger">*</span> :
                            </strong>
                            <input type="text" name="penerima"
                                class="form-control @error('penerima') is-invalid @enderror" placeholder="Nama penerima"
                                required>
                            @error('penerima')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> --}}
                    <div class="mb-3">
                        <label class="form-label" for="inputImage">
                            <strong>
                                Foto Invoice<span class="text-danger">*</span> :
                            </strong>
                        </label>
                        <input type="file" name="image" accept="image/png, image/jpeg, application/pdf" id="inputImage"
                            class="form-control @error('image') is-invalid @enderror" required>

                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <div class="mb-3">
                        <label class="form-label" for="tax-invoice">
                            <strong>
                                Foto Faktur Pajak :
                            </strong>
                        </label>
                        <input type="file" name="tax_invoice_photo" accept="image/png, image/jpeg, application/pdf"
                            id="tax-invoice" class="form-control @error('tax_invoice_photo') is-invalid @enderror">

                        @error('tax_invoice_photo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div> --}}

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
