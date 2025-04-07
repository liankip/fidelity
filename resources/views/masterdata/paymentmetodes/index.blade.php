@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Master Data Payment Metode Page</h2>
                </div>
                <div class="pull-right mb-2">
                    @if(auth()->user()->hasGeneralAccess())
                    <a class="btn btn-success" href="{{ route('paymentmetodes.create') }}"> Create paymentmetode</a>
                    @endif
                    {{-- <a class="btn btn-success" href="{{ url('#') }}"> Upload CSV</a> --}}
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>No.</th>
                        <th>Payment Metode Name</th>
                        <th>ToP</th>
                        <th width="280px">Action</th>
                    </tr>
                    @foreach ($paymentmetodes as $key => $paymentmetode)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $paymentmetode->metode }}</td>
                            <td>{{ $paymentmetode->term_of_payment }}</td>
                            <td>
                                <form action="{{ route('paymentmetodes.destroy', $paymentmetode->id) }}" method="Post">
                                    <a class="btn btn-primary"
                                        href="{{ route('paymentmetodes.edit', $paymentmetode->id) }}">Edit</a>
                                    {{-- @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button> --}}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
            <div class="card-footer"></div>
        </div>

        {!! $paymentmetodes->links() !!}
    @endsection
    </body>

    </html>
