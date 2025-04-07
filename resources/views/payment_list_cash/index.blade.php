@extends('layouts.app')

@section('content')
    <div class="mt-2">
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
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Payment List Cash</h2>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="text-align: center">No.</th>
                        <th style="text-align: center">PO NO</th>
                        {{-- <th>PR Type</th> --}}
                        <th style="text-align: center">Project</th>
                        <th style="text-align: center">Warehouse</th>
                        <th style="text-align: center">Tgl Barang Sampai</th>
                        <th style="text-align: center">Status</th>
                        <th style="text-align: center">ToP</th>
                        <th style="text-align: center">Jumlah Item</th>
                        <th style="text-align: center">Total Amount</th>
                        <th>Action</th>
                    </tr>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($cash as $val_cash)
                        {{-- @if ($val_cash->status == 'Wait For Approval') --}}
                        <tr>
                            <td style="text-align: center">{{ $no }}</td>
                            <td>{{ $val_cash->po_no }}</td>
                            {{-- <td></td> --}}
                            <td>{{ $val_cash->project->name }}</td>
                            <td>
                                @if ($val_cash->warehouse)
                                    {{ $val_cash->warehouse->name }}
                                @else
                                    {{ $val_cash->project->name }}
                                @endif
                            </td>
                            <td>{{ $val_cash->updated_at }}</td>
                            <td>{{ $val_cash->status }}</td>
                            <td>{{ $val_cash->term_of_payment }}</td>
                            <td style="text-align: center">{{ count($val_cash->podetail) }}</td>
                            <td>
                                @php
                                    $totalamount = 0;
                                @endphp
                                @foreach ($val_cash->podetail as $sajs)
                                    @php
                                        $totalamount += $sajs->amount;
                                    @endphp
                                @endforeach
                                <div class="d-flex justify-content-between">
                                    <div>Rp.</div>
                                    <div>{{ number_format($totalamount, 0, ',', '.') }}</div>
                                </div>

                            </td>

                            <td>
                                <div class="d-flex ">
                                    <form action="{{ route('paydir', $val_cash->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-success btn-sm">List Pay</button>
                                    </form>
                                    <a class="btn btn-primary btn-sm mx-1"
                                        href="{{ url('po_details', $val_cash->id) }}">View</a>
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                        data-target="#remarkconcern-{{ $val_cash->id }}">Concern
                                    </button>
                                </div>
                                <div class="modal fade" id="remarkconcern-{{ $val_cash->id }}" tabindex="-1"
                                    role="dialog" aria- labelledby="demoModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="demoModalLabel">Concern Reason PO
                                                    {{ $val_cash->po_no }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-
                                                    label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                {{-- Welcome, Websolutionstuff !! --}}
                                                <form action="{{ route('concern', $val_cash->id) }}" method="post">
                                                    <strong>Concern Reason:</strong>
                                                    <div class="form-group">
                                                        <textarea id="remark_concern" name="remark_concern" rows="4" class="form-control"></textarea>
                                                        @error('remark_concern')
                                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                {{-- <button type="button" class="btn btn-secondary" data-
                                                        dismiss="modal">Close</button> --}}
                                                {{-- <button type="button" class="btn btn-primary">Reject</button> --}}

                                                <span>

                                                    @csrf
                                                    @method('put')
                                                    <button type="submit" class="btn btn-warning">Concern</button>
                                                    </form>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <a class="btn btn-success" href="{{ url('upload-payment') }}"> Pay</a> --}}

                                {{-- <form action="{{ route('payments.create') }}" method="post">
                                    @csrf
                                    @method('put')
                                    <button type="submit" class="btn btn-success">Pay</button>
                                </form> --}}
                            </td>

                        </tr>
                        {{-- @endif --}}
                        @php
                            $no++;
                        @endphp
                    @endforeach
                </table>
            </div>
            <div class="card-footer">

            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
        </script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
        </script>


        {{-- {!! $val_cash->links() !!} --}}
    @endsection
