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
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Concern List</h2>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        </th>
                        <th>PO NO</th>
                        {{-- <th>PR Type</th> --}}
                        <th>Project</th>
                        <th>Warehouse</th>
                        <th>Tgl Barang Sampai</th>
                        <th>Status</th>
                        <th>ToP</th>
                        <th>Note</th>
                        <th>Invoice Image</th>
                        <th>Item Image</th>

                        <th>Action</th>
                    </tr>
                    @foreach ($non_cash as $key => $val_non_cash)
                        {{-- @if ($val_non_cash->status == 'Wait For Approval') --}}
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $val_non_cash->po_no }}</td>
                            {{-- <td></td> --}}
                            <td>{{ $val_non_cash->project->name }}</td>
                            <td>{{ $val_non_cash->warehouse->name }}</td>
                            <td>{{ $val_non_cash->updated_at }}</td>
                            <td>{{ $val_non_cash->status }}</td>
                            <td>{{ $val_non_cash->term_of_payment }}</td>
                            <td>{{ $val_non_cash->remark_concern }}</td>
                            <td>
                                @if($val_non_cash->term_of_payment == 'Cash')
                                    -
                                @endif
                                @if($val_non_cash->term_of_payment != 'Cash')
                                <form action="{{ route('viewphoto_inv', $val_non_cash->id) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <button type="submit" class="btn btn-primary">View Invoice</button>
                                </form>
                                @endif

                            </td>
                            <td>
                                @if($val_non_cash->term_of_payment == 'Cash')
                                    -
                                @endif
                                @if($val_non_cash->term_of_payment != 'Cash')
                                {{-- <form action="{{ route('viewphoto_submition', $val_non_cash->id) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <button type="submit" class="btn btn-primary">View Barang</button>
                                </form> --}}
                                <a href="{{ route('viewphoto_submition', $val_non_cash->id) }}" class="btn btn-primary">View Photo Barang</a>
                                @endif

                            </td>
                            <td>
                                <a class="btn btn-primary" href="{{ url('po_details', $val_non_cash->id) }}">View</a>
                                <form action="{{ route('upload-payment', $val_non_cash->id) }}" method="post">
                                    @csrf
                                    @method('get')
                                    <button type="submit" class="btn btn-success">Pay</button>
                                </form>
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
