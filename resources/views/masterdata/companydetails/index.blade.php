@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>{{ config('app.company', 'SNE') }} - ERP | companydetail Page</h2>
                </div>
                <div class="pull-right mb-2">
                    <a class="btn btn-success" href="{{ route('companydetails.create') }}"> Create companydetail</a>
                    {{-- <a class="btn btn-success" href="{{ route('companydetails.import') }}"> Upload CSV</a> --}}
                    <a class="btn btn-success" href="{{ route('companydetails.export') }}"> Downnload as CSV</a>
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
                <form action="{{ route('companydetails.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="form-control">
                    <br>
                    <button class="btn btn-success">Upload CSV</button>
                </form>

            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <th>companydetail Name</th>
                        <th>PIC</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Province</th>
                        <th>Post Code</th>
                        <th width="280px">Action</th>
                    </tr>
                    @foreach ($companydetails as $companydetail)
                        <tr>
                            <td>{{ $companydetail->id }}</td>
                            <td>{{ $companydetail->name }}</td>
                            <td>{{ $companydetail->pic }}</td>
                            <td>{{ $companydetail->email }}</td>
                            <td>{{ $companydetail->phone }}</td>
                            <td>{{ $companydetail->address }}</td>
                            <td>{{ $companydetail->city }}</td>
                            <td>{{ $companydetail->province }}</td>
                            <td>{{ $companydetail->post_code }}</td>
                            <td>
                                <form action="{{ route('companydetails.destroy', $companydetail->id) }}" method="Post">
                                    <a class="btn btn-primary"
                                        href="{{ route('companydetails.edit', $companydetail->id) }}">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
            <div class="card-footer"></div>
        </div>

        {!! $companydetails->links() !!}
    @endsection
