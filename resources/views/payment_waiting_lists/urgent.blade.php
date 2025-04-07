@extends('layouts.app')

@section('content')
            <div class="container mt-2">
                <div class="row">
                    <div class="col-lg-12 margin-tb">
                        <div class="pull-left">
                            <h2>{{ config('app.company', 'SNE') }} - ERP | Payment Waiting List</h2>
                        </div>
                        </div>
                </div>
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                <p>{{ $message }}</p>
                </div>
                @endif
                <div class="card">
                    <div class="card-header">

                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th></th>
                                <th>PO NO</th>
                                {{-- <th>PR Type</th> --}}
                                <th>Project</th>
                                <th>Warehouse</th>
                                <th>Tgl Barang Sampai</th>
                                <th>Status</th>
                                <th>ToP</th>
                                <th>Jumlah Item</th>
                                <th>Total Amount</th>
                                <th>Invoice Image</th>
                                <th>Item Image</th>
                                <th>DO Image</th>
                                <th>Action</th>
                            </tr>
                            @foreach ($non_cash as $key => $val_non_cash)
                            {{-- @if ($val_non_cash->status == 'Wait For Approval') --}}
                            <tr>
                                <td>{{ $val_non_cash->id }}</td>
                                <td>{{ $val_non_cash->po_no }}</td>
                                {{-- <td></td> --}}
                                <td>{{ $val_non_cash->project->name }}</td>
                                <td>{{ $val_non_cash->warehouse->name }}</td>
                                <td>{{ $val_non_cash->updated_at }}</td>
                                <td>{{ $val_non_cash->status }}</td>
                                <td>{{ $val_non_cash->term_of_payment }}</td>
                                <td>{{ count($val_non_cash->podetail) }}</td>
                            <td>
                                @php
                                    $totalamount = 0;
                                @endphp
                                @foreach ($val_non_cash->podetail as $sajs)
                                    @php
                                        $totalamount += $sajs->amount;
                                    @endphp
                                @endforeach
                                Rp.{{ number_format($totalamount,0) }}
                            </td>
                                <td>
                                    <form action="{{ route('viewphoto_inv', $val_non_cash->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-primary">View Invoice</button>
                                    </form>
                                </td>
                                <td>
                                    {{-- <form action="{{ route('viewphoto_submition', $val_non_cash->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-primary">View Barang</button>
                                    </form> --}}
                                    <a href="{{ route('viewphoto_submition', $val_non_cash->id) }}"
                                        class="btn btn-primary">View Photo Barang</a>
                                </td>
                                <td>
                                    {{-- <form action="{{ route('viewphoto_do', $val_non_cash->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-primary">View DO</button>
                                    </form> --}}
                                    <a href="{{ route('viewphoto_do', $val_non_cash->id) }}"
                                        class="btn btn-primary">View DO</a>
                                </td>
                                <td>
                                    <form action="{{ route('uppayment', $val_non_cash->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-success">Up Payment</button>
                                    </form>
                                    <a class="btn btn-primary" href="{{ url('po_details', $val_non_cash->id) }}">View</a>
                                </td>

                        </tr>
                        {{-- @endif --}}
                        @endforeach
                        </table>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>

            {{-- {!! $val_cash->links() !!} --}}
            @endsection

