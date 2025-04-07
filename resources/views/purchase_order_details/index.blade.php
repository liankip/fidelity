@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Purchase Order Detail</h2>
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

                <div style="overflow-x: scroll;">
                    <div style="display: flex;justify-content: space-between" class="mb-1">
                        <div style="font-weight: bold">
                            <div>{{ $statuspo->supplier->name }} </div>
                            <div>{{ $statuspo->supplier->address }}</div>
                            <div>{{ $statuspo->supplier->city }}, {{ $statuspo->supplier->province }}</div>
                        </div>
                        <table class="table table-bordered" style="width: 30%">
                            <tr>
                                <td colspan="2">
                                    <div style="margin-bottom: 3px">{{ $our_company->name }}</div>
                                    <div style="line-height: 18px;margin-bottom: 3px">
                                        {{ $our_company->address }}
                                    </div>
                                    <div>NPWP : {{ $our_company->npwpd }}</div>
                                </td>
                            </tr>

                            <tr>
                                <th>No.</th>
                                <td>
                                    @if ($statuspo)
                                        {{ $statuspo->po_no }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Date</th>
                                <td>{{ $newDate }}</td>
                            </tr>

                            <tr>
                                <th>Project</th>
                                <td>
                                    @if ($statuspo)
                                        @if ($statuspo->project)
                                            {{ $statuspo->project->name }}
                                        @endif
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th>Payment Term</th>
                                <td>
                                    @if ($statuspo)
                                        {{ $statuspo->term_of_payment }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Pengiriman</th>
                                <td>
                                    @if ($statuspo->deliver_status == 0)
                                        Dijemput
                                    @elseif ($statuspo->deliver_status == 1)
                                        {{ $statuspo->ds->name }}
                                    @elseif ($statuspo->deliver_status == 2)
                                        Diantar Toko
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <table class="table table-bordered">
                        <tr>
                            <th style="text-align: center">No</th>
                            <th style="text-align: center">Item Name</th>
                            <th style="text-align: center; width: 10%">Quantity/Unit</th>
                            @if (auth()->user()->type == 'user' ||
                                    auth()->user()->type == 'admin' ||
                                    auth()->user()->type == 'manager' ||
                                    auth()->user()->type == 'purchasing' ||
                                    auth()->user()->type == 'finance' ||
                                    auth()->user()->type == 'it' ||
                                    auth()->user()->type == 'adminlapangan')
                                <th style="text-align: center">Harga</th>
                                <th style="text-align: center">Jumlah</th>
                            @endif
                            @if ($statuspo->status == 'Approved' || $statuspo->status == 'Completed' || $statuspo->status == 'Partially Arrived')
                                <th style="text-align: center">Status</th>
                                <th style="text-align: center">Notes</th>
                                <th style="text-align: center">Action</th>
                            @endif
                        </tr>
                        @php
                            $total = 0;
                            $tax = 0;
                            $include = 0;
                        @endphp
                        @foreach ($statuspo->podetail as $key => $val)
                            @if ($key == 0)
                                @php
                                    $include = $val->tax_status;
                                    if ($val->tax_status == 2) {
                                        $tax = 0;
                                    } else {
                                        $tax = 11;
                                    }
                                @endphp
                            @endif
                            <tr>
                                <td style="text-align: center; width: 5%">{{ $key + 1 }}</td>
                                <td>{{ $val->prdetail->item_name }}</td>
                                <td align="right">
                                    {{ str_replace(',00', '', number_format($val->qty, 2, ',', '.')) }}
                                    {{ $val->prdetail->unit }}
                                </td>

                                @if (auth()->user()->type == 'user' ||
                                        auth()->user()->type == 'admin' ||
                                        auth()->user()->type == 'manager' ||
                                        auth()->user()->type == 'purchasing' ||
                                        auth()->user()->type == 'finance' ||
                                        auth()->user()->type == 'it' ||
                                        auth()->user()->type == 'adminlapangan')
                                    <td align="right">
                                        <div style="display: flex;justify-content: space-between">
                                            <span data-prefix>Rp. </span>
                                            <div>
                                                {{ str_replace(',00', '', number_format($harga = $val->price, 2, ',', '.')) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex;justify-content: space-between">
                                            <span data-prefix>Rp. </span>
                                            <div>{{ str_replace(',00', '', number_format($val->amount, 2, ',', '.')) }}
                                            </div>
                                        </div>
                                    </td>
                                @endif

                                @if ($statuspo->status == 'Approved' || $statuspo->status == 'Partially Arrived' || $statuspo->status == 'Completed')
                                    <td align="center">
                                        {{ str_replace(',00', '', number_format($val->total_sampai, 2, ',', '.')) }} /
                                        {{ str_replace(',00', '', number_format($val->qty, 2, ',', '.')) }}</td>
                                    <td>{{ $val->notes }}</td>

                                    <td>
                                        @if ($val->percent_complete < 100)
                                            @if (auth()->user()->type != 'admin_2')
                                                <form action="{{ route('create_submition', $val->id) }}" method="post">
                                                    @csrf
                                                    @method('get')
                                                    <button type="submit" class="btn btn-warning">Upload Foto</button>
                                                </form>

                                                <form action="{{ route('create_do', $statuspo->id) }}" method="post">
                                                    @csrf
                                                    @method('get')
                                                    <button type="submit" class="btn btn-warning">Upload Surat
                                                        Jalan</button>
                                                </form>
                                            @endif
                                        @else
                                            {{-- <form action="{{ route('arrivedpo', $val->purchase_order_id) }}" method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-success">Submition</button>
                                        </form> --}}
                                        @endif
                                    </td>
                                @endif
                            </tr>
                            @if (auth()->user()->type == 'user' ||
                                    auth()->user()->type == 'admin' ||
                                    auth()->user()->type == 'manager' ||
                                    auth()->user()->type == 'purchasing' ||
                                    auth()->user()->type == 'finance' ||
                                    auth()->user()->type == 'it' ||
                                    auth()->user()->type == 'adminlapangan')
                                @php
                                    $total += $val->amount;
                                @endphp
                            @endif
                        @endforeach
                    </table>
                    <div class="d-flex justify-content-between">
                        <div>
                            @if (
                                $statuspo->status == 'Approved' ||
                                    $statuspo->status == 'Partially Arrived' ||
                                    $statuspo->status == 'Completed' ||
                                    $statuspo->status == 'Arrived')
                                <div class="d-flex">
                                    @if (auth()->user()->type != 'admin_2')
                                        {{-- <form action="{{ route('viewphoto_submition', $statuspo->id) }}" method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-primary">View Photo Barang</button>
                                        </form> --}}
                                        <a href="{{ route('viewphoto_submition', $statuspo->id) }}"
                                            class="btn btn-primary">View Photo Barang</a>
                                        <div class="p-2"></div>
                                        {{-- <form action="{{ route('viewphoto_do', $statuspo->id) }}" method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-primary ">View Photo DO</button>
                                        </form> --}}
                                        <a href="{{ route('viewphoto_do', $statuspo->id) }}"
                                            class="btn btn-primary">View Photo DO</a>
                                        <div class="p-2"></div>
                                        <form action="{{ route('viewphoto_inv', $statuspo->id) }}" method="post">
                                            @csrf
                                            @method('put')
                                            <button type="submit" class="btn btn-primary">View Photo Invoice</button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="col-3">

                            @if (auth()->user()->type == 'user' ||
                                    auth()->user()->type == 'admin' ||
                                    auth()->user()->type == 'manager' ||
                                    auth()->user()->type == 'purchasing' ||
                                    auth()->user()->type == 'finance' ||
                                    auth()->user()->type == 'it' ||
                                    auth()->user()->type == 'adminlapangan')
                                <table class="table table-bordered">
                                    <tr>
                                        <td class=" table-bordered">Amount:</td>
                                        <td>
                                            <div style="display: flex;justify-content: space-between">
                                                <span data-prefix>Rp. </span>
                                                <div>{{ number_format($total, 0, ',', '.') }}</div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class=" table-bordered">PPN:</td>
                                        <td>
                                            <div style="display: flex;justify-content: space-between">
                                                <span data-prefix>Rp. </span>
                                                <div>{{ number_format($ppn = round($total * ($tax / 100)), 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                        $tarifds = 0;
                                    @endphp
                                    @if ($statuspo->deliver_status == 2)
                                        <tr>
                                            <td class=" table-bordered">Ongkos kirim:</td>
                                            <td>
                                                <div style="display: flex;justify-content: space-between">
                                                    <span data-prefix>Rp. </span>
                                                    <div>{{ number_format($statuspo->tarif_ds, 0, ',', '.') }}</div>
                                                </div>
                                            </td>
                                        </tr>
                                        @php
                                            $tarifds = $statuspo->tarif_ds;
                                        @endphp
                                    @endif

                                    @if ($statuspo->deliver_status)
                                        <tr style="font-weight: bold">
                                            <td class="font-bold">TOTAL:</td>
                                            <td class="font-bold">
                                                <div style="display: flex;justify-content: space-between">
                                                    <span data-prefix>Rp. </span>
                                                    <div>{{ number_format($tarifds + $total + $ppn, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                    @else
                                        <tr style="font-weight: bold">
                                            <td class="font-bold">TOTAL:</td>
                                            <td class="font-bold">
                                                <div style="display: flex;justify-content: space-between">
                                                    <span data-prefix>Rp. </span>
                                                    <div>{{ number_format($ppn + $total, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                </table>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer">
                {{-- <a class="btn btn-warning" href="{{ url('upload-invoice') }}">Upload Invoice</a> --}}
                <a class="btn btn-primary" href="{{ url()->previous() ? url()->previous() : url('purchase-orders') }}"
                    enctype="multipart/form-data">
                    Back
                </a>

                {{-- @foreach ($statuspo as $val_po)
                    @if ($val_po->status == 'New')
                        <a class="btn btn-success" href="#">Ajukan</a>

                    @endif
                    @if ($val_po->status == 'Draft')
                        <a class="btn btn-success" href="#">Approve</a>
                        <a class="btn btn-success" href="#">Reject</a>
                    @endif
                    @if ($val_po->status == 'Approved')
                        <a class="btn btn-success" href="#">Payment</a>
                        <a class="btn btn-success" href="#">Print</a>
                    @endif
                @endforeach --}}

            </div>
        </div>
    </div>
@endsection
