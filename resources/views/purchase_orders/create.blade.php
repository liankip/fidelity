@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Purchase Order Create</h2>
                </div>

            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <form action="{{ route('purchase_orders.store') }}" method="POST">
            @csrf
            <div class="card">
                @if ($purchaserequestdetail->count() == 0)
                    @csrf
                    <a class="btn btn-success" href="{{ route('itempr.index') }}">Tambah Barang</a>
                @endif

                @foreach ($statuspr as $statuspr)
                    <div class="card-header">
                        {{ $statuspr->pr_no }} |
                        <a class="btn btn-primary" href="{{ url('purchase_requests') }}">
                            Back
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Item ID</th>
                                <th>Item Name</th>
                                <th>Type</th>
                                <th>Unit</th>
                                <th>Quantity</th>
                                <th>Notes</th>
                                <th>Vendor</th>
                                <th>Harga</th>
                                <th>Tax</th>
                                <th>Jumlah</th>
                                <th>Payment Method</th>

                            </tr>
                            @foreach ($purchaserequestdetail as $val)
                                <tr>
                                    <td>
                                        <input hidden type="text" name="item_id[]" class="form-control" placeholder="item_id" value="{{ $val->item_id }}">
                                        {{ $val->item_id }}</td>
                                    <td>
                                        <input hidden type="text" name="item_name[]" class="form-control" placeholder="item_name" value="{{ $val->item_name }}">
                                        {{ $val->item_name }}</td>
                                    <td>
                                        <input hidden type="text" name="type[]" class="form-control" placeholder="type" value="{{ $val->type }}">
                                        {{ $val->type }}</td>
                                    <td>
                                        <input hidden type="text" name="unit[]" class="form-control" placeholder="unit" value="{{ $val->unit }}">
                                        {{ $val->unit }}</td>
                                    <td>
                                        <input hidden type="text" name="qty[]" class="form-control" placeholder="qty" value="{{ $val->qty }}">
                                        {{ $val->qty }}</td>
                                    <td>
                                        <input hidden type="text" name="notes[]" class="form-control" placeholder="notes" value="{{ $val->notes }}">
                                        {{ $val->notes }}</td>
                                    <td>
                                        <select name="price_id[]" id="price_id"
                                        class="js-example-basic-single form-control">
                                            <option value="">Pilih Supplier</option>
                                            @foreach ($brand_partner as $test)
                                                @if($test->id != "")
                                                    <option value="{{$test->id}}">
                                                        {{$test->supplier_id}}
                                                    </option>
                                                @endif
                                                @if($test->id == null)
                                                    <a class="btn btn-success" href="{{ route('prices.create') }}"> Add Supplier</a>
                                                @endif

                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            {{-- <strong>Event Type Code:</strong> --}}
                                            <input type="text" name="price[]" class="form-control" placeholder="price">
                                            @error('price')
                                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            {{-- <strong>Event Type Code:</strong> --}}
                                            <input type="text" name="tax[]" class="form-control" placeholder="tax">
                                            @error('tax')
                                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            {{-- <strong>Event Type Code:</strong> --}}
                                            <input type="text" name="amount[]" class="form-control" placeholder="amount">
                                            @error('amount')
                                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                    <div class="card-footer">
                        @if ($purchaserequestdetail->count() == 0)
                            @csrf
                            {{-- <a class="btn btn-success" href="{{ route('itempr.index') }}">Tambah Barang</a> --}}
                        @endif

                        {{-- Total = $totalamount | --}}

                        <button type="submit" class="btn btn-success">Save</button>

                        {{-- <a class="btn btn-success" href="#">Save</a> --}}
                        <a class="btn btn-success" href="#">Ajukan</a>
                    </div>
                @endforeach
        </form>

    </div>

    </div>
@endsection
