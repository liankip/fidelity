            @extends('layouts.app')

            @section('content')
                        <div class="container mt-2">
                            <div class="row">
                                <div class="col-lg-12 margin-tb">
                                    <div class="pull-left">
                                        <h2>{{ config('app.company', 'SNE') }} - ERP | Master Data val_do Page</h2>
                                    </div>
                                    <div class="pull-right mb-2">
                                        <a class="btn btn-success" href="{{ route('do.create') }}"> Create val_do</a>
                                        {{-- <a class="btn btn-success" href="{{ route('val_dos.import') }}"> Upload CSV</a>
                                        <a class="btn btn-success" href="{{ route('val_dos.export') }}"> Downnload as CSV</a> --}}
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
                                    <th>ID</th>
                                    <th>Nama Jasa Pengiriman</th>
                                    <th>Ground</th>

                                    <th width="280px">Action</th>
                                    </tr>
                                    @foreach ($do as $val_do)
                                    <tr>
                                    <td>{{ $val_do->id }}</td>
                                    <td>{{ $val_do->name }}</td>
                                    <td>{{ $val_do->ground }}</td>

                                    <td>
                                    <form action="{{ route('do.destroy',$val_do->id) }}" method="Post">
                                    <a class="btn btn-primary" href="{{ route('do.edit',$val_do->id) }}">Edit</a>
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

                        {{ $do->links() }}
                        @endsection
                    {{-- </body>
                </html> --}}
