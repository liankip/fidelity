@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Retur Request Page</h2>
                </div>
                <div class="pull-right mb-2">
                    <a class="btn btn-success" href="{{ route('returs.create') }}"> Create retur</a>
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>retur Code</th>
                <th>retur Name</th>
                <th>Type</th>
                <th>Unit</th>
                <th width="280px">Action</th>
            </tr>
            @foreach ($returs as $retur)
                <tr>
                    <td>{{ $retur->id }}</td>
                    <td>{{ $retur->retur_code }}</td>
                    <td>{{ $retur->name }}</td>
                    <td>{{ $retur->type }}</td>
                    <td>{{ $retur->unit }}</td>
                    <td>
                        <form action="{{ route('returs.destroy', $retur->id) }}" method="Post">
                            <a class="btn btn-primary" href="{{ route('returs.edit', $retur->id) }}">Edit</a>
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        {!! $returs->links() !!}
    @endsection
    {{-- </body>
                </html> --}}
