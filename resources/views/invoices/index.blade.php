@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-right ">
                    <h2 class="primary-color-sne">Invoice List</h2>
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card mt-5 primary-box-shadow">
            <div class="card-body">
                {{-- <a class="btn btn-success" href="{{ url('upload-invoice') }}">Upload Invoice</a> --}}

                <table class="table primary-box-shadow">
                    <tr class="thead-light">
                        <th class="text-center border-top-left">No</th>
                        <th class="text-center">PO No</th>
                        <th class="text-center">Invoice Image</th>
                        <th class="text-center border-top-right">Uploaded At</th>
                    </tr>
                    @foreach ($invoices as $key => $invoice)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td class="text-center">
                                @if ($invoice->purchaseorder)
                                    {{ $invoice->purchaseorder->po_no }}
                                @else
                                    @dd($invoice)
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="/{{ $invoice->foto_invoice }}" target="_blank"> Click Here</a>
                            </td>
                            <td class="text-center">
                                {{ $invoice->created_at ? date('d F Y', strtotime($invoice->created_at)) : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
            <div class="card-footer">
                {{ $invoices->links() }}
            </div>
        </div>
        <p></p>

    </div>
@endsection
{{-- </body>
                </html> --}}
