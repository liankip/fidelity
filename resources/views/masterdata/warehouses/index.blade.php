@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2 class="primary-color-sne">
                        Warehouse
                    </h2>
                </div>
                <div class="pull-right mb-2 mt-5">
                    @if (auth()->user()->hasGeneralAccess())
                        <a class="btn btn-success" href="{{ route('warehouses.create') }}"><i class="fa-solid fa-plus pe-2"></i> Create Warehouse</a>
                    @endif
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card primary-box-shadow">
            <div class="card-body">
                <form action="" method="get" class="d-flex">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="search" placeholder=""
                            value="{{ $searchcompact }}" aria-label="Recipient's username" aria-describedby="button-addon2">
                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                    </div>
                </form>
                <table class="table primary-box-shadow">
                    <tr class="thead-light">
                        <th class="align-middle text-center border-top-left">No</th>
                        <th class="align-middle text-center">Name</th>
                        <th class="align-middle text-center">PIC</th>
                        <th class="align-middle text-center">Email</th>
                        <th class="align-middle text-center">Phone</th>
                        <th class="align-middle text-center">Address</th>
                        <th class="align-middle text-center">City</th>
                        <th class="align-middle text-center">Province</th>
                        <th class="align-middle text-center">Post Code</th>
                        <th class="align-middle text-center border-top-right" width="10%">Action</th>
                    </tr>
                    @foreach ($warehouses as $key => $warehouse)
                        <tr>
                            <td class="text-cneter">{{ $key + 1 }}</td>
                            <td>{{ $warehouse->name }}</td>
                            <td>{{ $warehouse->pic }}</td>
                            <td>{{ $warehouse->email }}</td>
                            <td>{{ $warehouse->phone }}</td>
                            <td>{{ $warehouse->address }}</td>
                            <td>{{ $warehouse->city }}</td>
                            <td>{{ $warehouse->province }}</td>
                            <td>{{ $warehouse->post_code }}</td>
                            <td class="text-center">
                                <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="Post">
                                    <a class="btn btn-outline-info"
                                        href="{{ route('warehouses.edit', $warehouse->id) }}">Edit</a>
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

        {!! $warehouses->links() !!}
    @endsection
    {{-- </body>
    </html> --}}
