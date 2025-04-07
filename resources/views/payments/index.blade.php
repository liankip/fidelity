@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">

                {{-- <div class="pull-right mb-2">
                    <a class="btn btn-success" href="{{ route('payments.create') }}"> Create payment</a>
                </div> --}}
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
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Payment List Page</h2>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>no</th>
                        <th>Payment Picture</th>
                        <th>PO No</th>
                        <th>Project</th>
                        <th>Warehouse</th>
                        <th>Jumlah Item</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Action</th>
                        {{-- <th width="280px">Action</th> --}}
                    </tr>
                    @foreach ($payments as $key => $payment)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <a href="/{{ $payment->payment_pict }}" target="_blank"> Click Here</a>
                                {{-- <img src={{ url($invoice->foto_invoice) }} alt="" width="225 px"> --}}
                            </td>
                            {{-- <td>{{ $payment->payment_pict }}</td> --}}
                            <td>{{ $payment->purchaseorder->po_no }}</td>
                            <td>{{ $payment->purchaseorder->project->name }}</td>
                            <td>
                                @if ($payment->purchaseorder->warehouse)
                                    {{ $payment->purchaseorder->warehouse->name }}
                                @else
                                    {{ $payment->purchaseorder->project->name }}
                                @endif
                            </td>
                            <td>{{ count($payment->purchaseorder->podetail) }}</td>
                            <td>
                                @php
                                    $totalamount = 0;
                                @endphp
                                @foreach ($payment->purchaseorder->podetail as $sajs)
                                    @php
                                        $totalamount += $sajs->amount;
                                    @endphp
                                @endforeach
                                Rp.{{ number_format($totalamount, 0, ',', '.') }}
                            </td>
                            <td>{{ $payment->status }}</td>
                            <td>{{ $payment->notes }}</td>
                            <td><a class="btn btn-primary"
                                    href="{{ url('po_details', $payment->purchaseorder->id) }}">View</a></td>
                            {{-- <td>
                                <form action="{{ route('payments.destroy', $payment->id) }}" method="Post">
                                    <a class="btn btn-primary" href="{{ route('payments.edit', $payment->id) }}">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td> --}}
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="card-footer">
                {{ $payments->links() }}
            </div>
        </div>
    @endsection
