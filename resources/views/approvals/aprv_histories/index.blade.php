@extends('layouts.app')

@section('content')
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                </div>
                {{--                @if (auth()->user()->type == 'it' || auth()->user()->type == 'adminlapangan')--}}
                {{--                    <div class="pull-right mb-2">--}}
                {{--                        --}}{{-- <a class="btn btn-success" href="{{ route('itempr.index') }}"> Create purchaserequest</a> --}}
                {{--                    </div>--}}
                {{--                @endif--}}
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h2>{{ config('app.company', 'SNE') }} - ERP | History Approval Test</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="text-align: center">No</th>

                        <th style="text-align: center">PO No/ SPK No</th>
                        {{-- <th>PR Type</th> --}}
                        <th style="text-align: center">Vendor</th>
                        <th style="text-align: center">Project</th>
                        <th style="text-align: center">Warehouse</th>
                        <th style="text-align: center">Jumlah Item</th>
                        <th style="text-align: center">Total Amount</th>
                        <th style="text-align: center">Tgl Request</th>
                        <th style="text-align: center">Status</th>
                        <th style="text-align: center">Approved_by</th>
                        <th style="text-align: center">Notes</th>
                        <th style="text-align: center">Action</th>
                    </tr>
                    @foreach ($purchase_requests as $key => $purchaserequest)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $purchaserequest->po_no }}</td>
                            <td>{{ $purchaserequest->supplier->name }}</td>
                            {{-- <td>{{ $purchaserequest->pr_type }}</td> --}}
                            <td>{{ $purchaserequest->project->name }}</td>
                            <td>
                                @if ($purchaserequest->warehouse)
                                    {{ $purchaserequest->warehouse->name }}
                                @endif
                            </td>
                            <td class="text-center">{{ count($purchaserequest->podetail) }}</td>
                            <td align="right">
                                @php
                                    $totalamount = 0;
                                @endphp
                                @foreach ($purchaserequest->podetail as $sajs)
                                    @php
                                        $totalamount += $sajs->amount;
                                    @endphp
                                @endforeach
                                <div class="d-flex justify-content-between">
                                    <div>Rp.</div>
                                    <div>{{ number_format($totalamount, 0, ',', '.') }}</div>
                                </div>
                            </td>
                            <td>{{ $purchaserequest->created_at }}</td>
                            <td>{{ $purchaserequest->status }}</td>
                            <td>
                                @if ($purchaserequest->approvedby)
                                    {{ $purchaserequest->approvedby->name }}
                                @else
                                    Unknown
                                @endif
                            </td>
                            <td>{{ $purchaserequest->remark }}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ url('po_details', $purchaserequest->id) }}">View</a>
                            </td>

                        </tr>
                    @endforeach
                </table>

            </div>
            <div class="card-footer"></div>
        </div>


        {{-- {!! $purchase_requests->links() !!} --}}
    @endsection
    {{-- </body>
                </html> --}}
