@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left mb-2">
                    {{-- <h2>Upload Payment Transfer Picture</h2> --}}
                </div>
                <div class="pull-right">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
                            {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <img src="/images/payment/{{ Session::get('image') }}" class="mb-2"
                            style="width:400px;height:200px;">
                    @endif
                    {{-- <a class="btn btn-primary" href="{{ route('payments.index') }}"> Back</a> --}}
                </div>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif

        <div class="row">
            <div class="card">
                <div class="card-header">
                    <h4>Upload Bukti Pembayaran</h4>
                </div>
                <form action="{{ route('payment-store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Payment Pict:</strong>
                                <input type="file" name="payment_pict"
                                    class="form-control @error('payment_pict') is-invalid @enderror" accept="image/png, image/jpeg, application/pdf"
                                    placeholder="payment pict">
                                @error('payment_pict')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>
                                    <label for="po_id">No PO :</label>
                                </strong>
                                <input readonly type="text" class="form-control" placeholder="created by"
                                    value="{{ $po->po_no }}">
                                <input hidden type="text" name="po_id" class="form-control" placeholder="created by"
                                    value="{{ $po->id }}">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>
                                    <label for="status">Status Pembayaran :</label>
                                </strong>

                                <select name="status" id="status"
                                    class="js-example-basic-single form-control @error('status') is-invalid @enderror">
                                    <option value="">Pilih Status Pembayaran</option>
                                    <option {{ old('status') == 'Part' ? 'selected' : '' }} value="Part">Part</option>
                                    <option {{ old('status') == 'Lunas' ? 'selected' : '' }} value="Lunas">Lunas</option>
                                </select>
                                @error('status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>
                                    <label for="notes">Catatan :</label>
                                </strong>
                                <input type="text" name="notes" class="form-control" value="{{old('notes')}}" placeholder="Catatan">
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
