@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">

                <div class="pull-right mb-2">
                    <a class="btn btn-success" href="{{ route('delivery_orders.create') }}"> Upload Surat Jalan</a>
                    {{-- <a class="btn btn-success" href="{{ route('deliveryorders.import') }}"> Upload CSV</a>
                                        <a class="btn btn-success" href="{{ route('deliveryorders.export') }}"> Downnload as CSV</a> --}}
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
                <div class="pull-left">
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Surat Jalan</h2>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <th>DO No</th>
                        <th>DO Type</th>
                        <th>Referensi</th>
                        <th>Pictures</th>


                        {{-- <th width="280px">Action</th> --}}
                    </tr>
                    @foreach ($delivery_orders as $deliveryorder)
                        <tr>
                            <td>{{ $deliveryorder->id }}</td>
                            <td>{{ $deliveryorder->do_no }}</td>
                            <td>{{ $deliveryorder->do_type }}</td>
                            <td>{{ $deliveryorder->referensi }}</td>
                            <td>
                                <a href="http://127.0.0.1:8000/{{ $deliveryorder->do_pict }}"> Click Here</a>

                            </td>

                            {{-- <td>
                                    <form action="{{ route('delivery_orders.destroy',$deliveryorder->id) }}" method="Post">
                                    <a class="btn btn-primary" href="{{ route('delivery_orders.edit',$deliveryorder->id) }}">Edit</a>
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
                {{ $delivery_orders->links() }}
            </div>
        </div>
    @endsection
    {{-- </body>
                </html> --}}
