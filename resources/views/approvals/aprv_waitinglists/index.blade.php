@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
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
                <h2>{{ config('app.company', 'SNE') }} - ERP | Waiting List Approval</h2>
            </div>

            <div class="card-body" style="overflow-x: scroll;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="text-align: center; width: 10%;">No</th>
                            </th>
                            <th style="text-align: center; width: 25%;">PO No/ SPK No</th>
                            <th style="text-align: center; width: 20%;">Warehouse</th>
                            <th style="text-align: center; width: 5%;">Item</th>
                            <th style="text-align: center; width: 15%;">Total Amount</th>
                            <th style="text-align: center; width: 20%;">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($purchase_requests as $key => $purchaserequest)
                            <tr class="accordion-toggle">
                                <td data-toggle="collapse" data-target="#demo{{ $key + 1 }}">
                                    <a class="btn btn-primary">View</a>
                                </td>
                                <td>
                                    <span
                                        style="background-color: #ffc107;
                                    padding: 0px 5px;
                                    border-radius: 6px;">{{ $purchaserequest->po_no }}</span>
                                    <br>
                                    <span style="font-weight: 900">
                                        Vendor :
                                        {{ $purchaserequest->supplier ? $purchaserequest->supplier->name : 'data supplier terhapus' }}
                                        <br>
                                    </span>
                                    <span>
                                        Project :
                                        {{ $purchaserequest->project ? $purchaserequest->project->name : 'data project terhapus' }}
                                        <br>
                                    </span>

                                    <span style="font-size: 14px;
                                    font-style: italic;">
                                        Notes:
                                        @if ($purchaserequest->pr)
                                            {{ $purchaserequest->pr->remark }}
                                        @endif
                                    </span>
                                </td>

                                <td>
                                    <span>
                                        <span
                                            style="background-color: #198754;
                                        color: white;
                                        padding: 0px 10px;
                                        border-radius: 6px;">Tanggal
                                            Request</span><br>
                                        <div style="font-weight: 900">{{ $purchaserequest->created_at }}</div>
                                    </span>
                                    @if ($purchaserequest->warehouse_id != 0 && $purchaserequest->warehouse)
                                        {{ $purchaserequest->warehouse->name }}
                                    @else
                                        Project
                                    @endif
                                </td>
                                <td style="text-align: center">{{ count($purchaserequest->podetail) }}</td>
                                <td>
                                    @php
                                        $totalamount = 0;
                                    @endphp
                                    @foreach ($purchaserequest->podetail as $sajs)
                                        @php
                                            $totalamount += $sajs->amount;
                                        @endphp
                                    @endforeach
                                    @php
                                        $ongkir = 0;
                                    @endphp
                                    @if ($purchaserequest->deliver_status == 1)
                                        @php
                                            $ongkir = $purchaserequest->tarif_ds;
                                        @endphp
                                    @endif

                                    @php
                                        $ppn = 0;
                                    @endphp
                                    @if ($purchaserequest->podetail->first()->tax_status == 2)
                                        @php
                                            $ppn = 0;
                                        @endphp
                                    @else
                                        @php
                                            $ppn = round($totalamount * 0.11);
                                        @endphp
                                    @endif
                                    @if ($purchaserequest->tax_custom)
                                        @php
                                            $ppn = $purchaserequest->tax_custom;
                                        @endphp
                                    @endif

                                    <div class="d-flex justify-content-between">
                                        {{-- {{$totalamount}} | {{$ppn}} | {{$ongkir}} --}}
                                        <div>Rp.</div>
                                        <div>{{ number_format($totalamount + $ppn + $ongkir, 0, ',', '.') }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if (auth()->user()->hasTopLevelAccess())
                                        <span>
                                            <form action="{{ route('approve', $purchaserequest->id) }}" method="post">
                                                @csrf
                                                @method('put')
                                                <button type="submit" class="btn btn-success">Approve</button>
                                            </form>
                                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#remarkreject-{{ $purchaserequest->id }}">Reject</button>
                                        </span>
                                        @csrf
                                        <!-- Modal Example Start-->
                                        <div class="modal fade" id="remarkreject-{{ $purchaserequest->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="demoModalLabel">Reject Reason PO
                                                            {{ $purchaserequest->po_no }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-
                                                            label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('reject', $purchaserequest->id) }}"
                                                            method="post">
                                                            {{-- Welcome, Websolutionstuff !! --}}
                                                            <div class="form-group">
                                                                <strong>Reject Reason:</strong>
                                                                <textarea id="remark_reject" name="remark_reject" rows="4" class="form-control"></textarea>
                                                                @error('remark_reject')
                                                                    <div class="alert alert-danger mt-1 mb-1">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <span>

                                                            @csrf
                                                            @method('put')
                                                            <button type="submit" class="btn btn-danger">Reject</button>
                                                            </form>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="remarkreview-{{ $purchaserequest->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="demoModalLabel">Review Reason PO
                                                            {{ $purchaserequest->po_no }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-
                                                            label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{-- Welcome, Websolutionstuff !! --}}
                                                        <form action="{{ route('review', $purchaserequest->id) }}"
                                                            method="post">
                                                            <strong>Reject Reason:</strong>
                                                            <div class="form-group">
                                                                <textarea id="remark_review" name="remark_review" rows="4" class="form-control"></textarea>
                                                                @error('remark_review')
                                                                    <div class="alert alert-danger mt-1 mb-1">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <span>

                                                            @csrf
                                                            @method('put')
                                                            <button type="submit" class="btn btn-warning">Review</button>
                                                            </form>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Example End-->
                                    @endif

                                </td>
                            </tr>
                            <tr>
                                <td colspan="12" class="hiddenRow">
                                    <div class="accordian-body collapse" id="demo{{ $key + 1 }}">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr class="info">
                                                    <th style="text-align: center">No</th>
                                                    <th style="text-align: center">Item Name</th>
                                                    <th style="text-align: center">Quantity/Unit</th>
                                                    <th style="text-align: center">Harga</th>
                                                    <th style="text-align: center">Jumlah</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $total = 0;
                                                $tax = 0;
                                                $include = 0;
                                            @endphp
                                            <tbody>
                                                @foreach ($purchaserequest->podetail as $keydetail => $detail)
                                                    @if ($keydetail == 0)
                                                        @php
                                                            $include = $detail->tax_status;
                                                            if ($detail->tax_status == 2) {
                                                                $tax = 0;
                                                            } else {
                                                                $tax = 11;
                                                            }
                                                        @endphp
                                                    @endif
                                                    <tr data-toggle="collapse" class="accordion-toggle"
                                                        data-target="#demo10">
                                                        <td style="text-align: center; width: 5%">{{ $keydetail + 1 }}
                                                        </td>
                                                        <td>{{$detail->prdetail->item_name }}
                                                        </td>

                                                        <td align="right">{{ number_format($detail->qty) }}
                                                            {{ $detail->prdetail->unit }}</td>


                                                        <td align="right">
                                                            <div style="display: flex;justify-content: space-between">
                                                                <span data-prefix>Rp. </span>
                                                                <div>
                                                                    {{ number_format($harga = $detail->price, 0, ',', '.') }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="display: flex;justify-content: space-between">
                                                                <span data-prefix>Rp. </span>
                                                                <div>
                                                                    {{ number_format($jumlah = $harga * $detail->qty, 0, ',', '.') }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $total += $jumlah;
                                                    @endphp
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <div class="d-flex justify-content-end">
                                            <div class="col-3">
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
                                                                <div>
                                                                    {{ number_format($ppn = round($total * ($tax / 100)), 0, ',', '.') }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $tarifds = 0;
                                                    @endphp
                                                    @if ($purchaserequest->deliver_status == 2)
                                                        <tr>
                                                            <td class=" table-bordered">Ongkos kirim:</td>
                                                            <td>
                                                                <div style="display: flex;justify-content: space-between">
                                                                    <span data-prefix>Rp. </span>
                                                                    <div>
                                                                        {{ number_format($purchaserequest->tarif_ds, 0, ',', '.') }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $tarifds = $purchaserequest->tarif_ds;
                                                        @endphp
                                                    @endif
                                                    @if ($purchaserequest->deliver_status)
                                                        <tr style="font-weight: bold">
                                                            <td class="font-bold">TOTAL:</td>
                                                            <td class="font-bold">
                                                                <div style="display: flex;justify-content: space-between">
                                                                    <span data-prefix>Rp. </span>
                                                                    <div>
                                                                        {{ number_format($tarifds + $total + $ppn, 0, ',', '.') }}
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
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
        </script>
    </div>
@endsection
