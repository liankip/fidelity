@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    {{-- <h2>{{ config('app.company', 'SNE') }} - ERP | Invoice List</h2> --}}
                </div>
                <div class="pull-right mb-2">
                    <a class="btn btn-success" href="{{ route('create_inv', ['id' => $po->id]) }}"> Upload Invoice</a>
                    {{-- <form action="{{ route('invoices.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control">
                        <br>
                        <button class="btn btn-success">Upload CSV</button>
                    </form> --}}
                    {{-- <a class="btn btn-success" href="{{ route('invoices.export') }}"> Downnload as CSV</a> --}}

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
                {{ config('app.company', 'SNE') }} - ERP | Invoice List | PO No: {{ $po->po_no }} <a
                    href="{{ url()->previous() }}" class="btn btn-success btn-sm">Back</a>
                {{-- <form action="{{ route('invoices.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="form-control">
                    <br>
                    <button class="btn btn-success">Upload CSV</button>
                </form> --}}

            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>No</th>
                        <th>PO No</th>
                        <th>Invoice Image</th>
                        {{-- <th>
                            Recip
                        </th> --}}
                        {{-- <th>Tanggal</th> --}}
                        {{-- <th width="280px">Action</th> --}}
                    </tr>
                    @foreach ($invoices as $key => $invoice)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $invoice->purchaseorder->po_no }}</td>
                            <td>
                                {{-- <a href="/{{ $invoice->foto_invoice }}" target="_blank"> Click Here</a> --}}
                                {{-- <img src={{ url($invoice->foto_invoice) }} alt="" width="225 px"> --}}
                                <a class="dropdown-item" href="/{{ $invoice->foto_invoice }}" target="_blank">
                                    {{-- <img src="{{ asset($invoice->foto_invoice) }}" class="w-100" style="max-height: 200px" alt=""> --}}
                                    <a href="{{ asset($invoice->foto_invoice) }}" target="_blank" class="btn btn-primary">Click here</a>
                                </a>
                            </td>
                            {{-- <td>{{ $invoice->penerima }}</td> --}}
                            {{-- <td>{{ $invoice->date_received }}</td> --}}
                            {{-- <td> --}}
                            {{-- <form action="{{ route('invoices.destroy',$invoice->id) }}" method="Post">
                            {{-- @if (auth()->user()->type == 'it' || auth()->user()->type == 'adminlapangan')
                            <a class="btn btn-primary" href="{{ route('invoices.edit',$invoice->id) }}">Add to PR</a>
                            @endif --}}
                            {{-- <a class="btn btn-primary" href="{{ route('invoices.edit',$invoice->id) }}">Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                            </form>  --}}
                            {{-- </td> --}}
                        </tr>
                    @endforeach
                </table>

            </div>
            <div class="card-footer"></div>
        </div>

        {!! $invoices->links() !!}
    @endsection
    {{-- </body>
</html> --}}
