@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">

            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <div class="pull-left">
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Payment List</h2>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="text-align: center">No</th>
                        <th style="text-align: center">PO NO</th>
                        {{-- <th>PR Type</th> --}}
                        <th style="text-align: center">Project</th>
                        <th style="text-align: center">Warehouse</th>
                        <th style="text-align: center">Tgl Barang Sampai</th>
                        <th style="text-align: center">Status</th>
                        <th style="text-align: center">ToP</th>
                        <th style="text-align: center">Item</th>
                        <th style="text-align: center">Total Amount</th>
                        <th style="text-align: center">Note</th>
                        <th style="text-align: center">Image</th>
                        <th style="text-align: center">Action</th>
                    </tr>
                    @foreach ($non_cash as $key => $val_non_cash)
                        {{-- @if ($val_non_cash->status == 'Wait For Approval') --}}
                        <tr style="font-size: 13px">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $val_non_cash->po_no }}</td>
                            <td>{{ $val_non_cash->project->name }}</td>
                            <td>
                                @if ($val_non_cash->warehouse)
                                    {{ $val_non_cash->warehouse->name }}
                                @else
                                    {{ $val_non_cash->project->name }}
                                @endif
                            </td>
                            <td>{{ $val_non_cash->updated_at }}</td>
                            <td>{{ $val_non_cash->status }}</td>
                            <td>{{ $val_non_cash->term_of_payment }}</td>
                            <td class="text-center">{{ count($val_non_cash->podetail) }}</td>
                            <td>
                                @php
                                    $totalamount = 0;
                                @endphp
                                @foreach ($val_non_cash->podetail as $sajs)
                                    @php
                                        $totalamount += $sajs->amount;
                                    @endphp
                                @endforeach
                                @php
                                    $ongkir = 0;
                                @endphp
                                @if ($val_non_cash->deliver_status == 1)
                                    @php
                                        $ongkir = $val_non_cash->tarif_ds;
                                    @endphp
                                @endif

                                @php
                                    $ppn = 0;
                                @endphp
                                @if ($val_non_cash->podetail->first()->tax_status == 2)
                                    @php
                                        $ppn = 0;
                                    @endphp
                                @else
                                    @php
                                        $ppn = round($totalamount * 0.11);
                                    @endphp
                                @endif
                                @if ($val_non_cash->tax_custom)
                                    @php
                                        $ppn = $val_non_cash->tax_custom;
                                    @endphp
                                @endif

                                <div class="d-flex justify-content-between">
                                    <div>Rp.</div>
                                    <div>{{ number_format($totalamount + $ppn + $ongkir, 0, ',', '.') }}</div>
                                </div>

                            </td>
                            <td>{{ $val_non_cash->remark }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle btn-sm"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Image
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <form action="{{ route('viewphoto_inv', $val_non_cash->id) }}" method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="dropdown-item">View Invoice</button>
                                        </form>

                                        {{-- <form action="{{ route('viewphoto_submition', $val_non_cash->id) }}"
                                            method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="dropdown-item">View Barang</button>
                                        </form> --}}
                                        <a href="{{ route('viewphoto_submition', $val_non_cash->id) }}"
                                            class="btn btn-primary">View Photo Barang</a>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a class="btn btn-primary btn-sm me-1"
                                        href="{{ url('po_details', $val_non_cash->id) }}">View</a>
                                    <form action="{{ route('upload-payment', $val_non_cash->id) }}" method="post">
                                        @csrf
                                        @method('get')
                                        <button type="submit" class="btn btn-success btn-sm">Pay</button>
                                    </form>
                                </div>
                                {{-- <form action="{{ route('paydir', $val_non_cash->id) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <button type="submit" class="btn btn-primary">List Pay</button>
                                </form>
                                <form action="{{ route('concern', $val_non_cash->id) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <button type="submit" class="btn btn-warning">Concern</button>
                                </form> --}}
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
