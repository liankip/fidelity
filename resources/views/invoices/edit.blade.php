@extends('layouts.app')

@section('content')
    {{-- <body> --}}
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Edit invoice</h2>
                </div>
                <div class="pull-right">
                    {{-- <a class="btn btn-primary" href="{{ route('invoices.index') }}" enctype="multipart/form-data"> Back</a> --}}
                </div>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header"><a class="btn btn-primary" href="{{ route('invoices.index') }}"
                    enctype="multipart/form-data"> Back</a></div>
            <div class="card-body">
                <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
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
                                <strong>Foto Invoice:</strong>
                                <input type="file" name="foto_invoice" value="{{ $invoice->foto_invoice }}"
                                    class="form-control" placeholder="foto invoice">
                                @error('foto_invoice')
                                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nama Penerima:</strong>
                                <input type="text" name="penerima" value="{{ $invoice->penerima }}" class="form-control"
                                    placeholder="Nama Penerima">
                                @error('penerima')
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
