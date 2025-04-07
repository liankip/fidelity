            @extends('layouts.app')

            @section('content')
                <div class="container mt-2">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                                <h2>{{ config('app.company', 'SNE') }} - ERP | Gudang Transfer Page</h2>
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
                                <a class="btn btn-success" href="{{ route('gudang_transfers.create') }}"> New GT</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID</th>
                                    </th>
                                    <th>GT No</th>
                                    <th>From Wrh</th>
                                    <th>To Wrh</th>
                                    <th>Requestor</th>
                                    <th>Request Date</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th width="280px">Action</th>
                                </tr>
                                @foreach ($gudang_transfers as $key => $gudangtransfer)
                                    <tr>
                                        <td>{{ $gudangtransfer->id }}</td>
                                        <td>{{ $gudangtransfer->gt_no }}</td>
                                        {{-- @dd($gudangtransfer->warehouse) --}}
                                        <td>{{ $gudangtransfer->warehousefrom->name }}</td>
                                        <td>{{ $gudangtransfer->warehouseto->name }}</td>
                                        <td>{{ $gudangtransfer->user->name }}</td>
                                        <td>{{ $gudangtransfer->created_at }}</td>
                                        <td>{{ $gudangtransfer->status }}</td>
                                        <td></td>
                                        <td>
                                            <form action="{{ route('gudang_transfers.destroy', $gudangtransfer->id) }}"
                                                method="Post">
                                                <a class="btn btn-primary"
                                                    href="{{ route('gudang_transfers.edit', $gudangtransfer->id) }}">Edit</a>
                                                <a class="btn btn-primary"
                                                    href="{{ route('gudang_transfers.edit', $gudangtransfer->id) }}">Submition</a>
                                                <a class="btn btn-primary"
                                                    href="{{ route('gudang_transfers.edit', $gudangtransfer->id) }}">Detail</a>
                                                @csrf
                                                {{-- @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button> --}}
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            {!! $gudang_transfers->links() !!}
                        </div>
                        <div class="card-footer">

                        </div>
                    </div>
                @endsection
                {{-- </body>
                </html> --}}
