{{-- @extends('layouts.app') --}}
{{-- <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>{{ config('app.company', 'SNE') }} - ERP | eventtype Page</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
        </head>
        <body> --}}
            @extends('layouts.app')

            @section('content')
                        <div class="container mt-2">
                            <div class="row">
                                <div class="col-lg-12 margin-tb">
                                    <div class="pull-left">
                                        <h2>{{ config('app.company', 'SNE') }} - ERP | Master Data eventtype Page</h2>
                                    </div>
                                    <div class="pull-right mb-2">
                                        @if(auth()->user()->hasGeneralAccess())
                                        <a class="btn btn-success" href="{{ route('event_types.create') }}"> Create eventtype</a>
                                        @endif
                                        {{-- <a class="btn btn-success" href="{{ route('eventtypes.import') }}"> Upload CSV</a> --}}
                                        {{-- <a class="btn btn-success" href="{{ route('eventtypes.export') }}"> Downnload as CSV</a> --}}
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
                                {{-- <form action="{{ route('eventtypes.import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="file" class="form-control">
                                    <br>
                                    <button class="btn btn-success">Upload CSV</button>
                                </form> --}}
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                    <th>ID</th>
                                    <th>Event Type Code</th>
                                    <th>Remark</th>

                                    <th width="280px">Action</th>
                                    </tr>
                                    @foreach ($event_types as $eventtype)
                                    <tr>
                                    <td>{{ $eventtype->id }}</td>
                                    <td>{{ $eventtype->type }}</td>
                                    <td>{{ $eventtype->remark }}</td>

                                    <td>
                                    <form action="{{ route('event_types.destroy',$eventtype->id) }}" method="Post">
                                    <a class="btn btn-primary" href="{{ route('event_types.edit',$eventtype->id) }}">Edit</a>
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

                        {!! $event_types->links() !!}
                        @endsection
                    {{-- </body>
                </html> --}}
