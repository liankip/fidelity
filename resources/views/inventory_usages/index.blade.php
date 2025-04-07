@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Inventory Usage Page</h2>
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
                <div class="pull-right mb-2">
                    <a class="btn btn-success" href="{{ route('inventory_usages.create') }}">New Usages</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        </th>
                        <th>IU No</th>
                        <th>From Wrh</th>
                        <th>To Place</th>
                        <th>Requestor</th>
                        <th>Request Date</th>
                        <th>Request Status</th>
                        <th>Notes</th>
                        <th width="280px">Action</th>
                    </tr>
                    @foreach ($inventory_usages as $key => $inventoryusage)
                        <tr>
                            <td>{{ $inventoryusage->id }}</td>
                            <td>{{ $inventoryusage->iu_no }}</td>
                            <td>{{ $inventoryusage->warehousefrom->name }}</td>
                            <td>{{ $inventoryusage->project->name }}</td>
                            <td>{{ $inventoryusage->user->name }}</td>
                            <td>{{ $inventoryusage->created_at }}</td>
                            <td>{{ $inventoryusage->status }}</td>
                            <td>{{ $inventoryusage->notes }}</td>
                            <td>
                                <form action="{{ route('inventory_usages.destroy', $inventoryusage->id) }}" method="Post">
                                    <a class="btn btn-primary"
                                        href="{{ route('inventory_usages.edit', $inventoryusage->id) }}">Edit</a>
                                    <a class="btn btn-primary"
                                        href="{{ route('inventory_usages.edit', $inventoryusage->id) }}">Submition</a>
                                    <a class="btn btn-primary"
                                        href="{{ route('inventory_usages.edit', $inventoryusage->id) }}">Detail</a>
                                    @csrf
                                    {{-- @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button> --}}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
                {!! $inventory_usages->links() !!}
            </div>
            <div class="card-footer">

            </div>
        </div>
    @endsection
    {{-- </body>
                </html> --}}
