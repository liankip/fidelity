@extends('layouts.app')

@section('content')
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    {{-- <h2>{{ config('app.company', 'SNE') }} - ERP | Master Data Item Page</h2> --}}
                    <h2>{{ config('app.company', 'SNE') }} - ERP | Log History Purchase</h2>
                </div>
                <div class="pull-right mb-2">
                    {{-- <a class="btn btn-success" href="{{ route('items.create') }}"> Create item</a>

                                <a class="btn btn-success" href="{{ route('items.export') }}"> Downnload as CSV</a> --}}

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
                Log History Purchase

            </div>
            <div class="card-body">

                <table class="table table-bordered">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Referensi</th>
                        <th class="text-center">Action Start</th>
                        <th class="text-center">Action End</th>
                        <th class="text-center">Action Date</th>
                        <th class="text-center">Action By</th>

                    </tr>
                    @foreach ($history as $key => $val)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $val->referensi }}</td>
                            <td>{{ $val->action_start }}</td>
                            <td>{{ $val->action_end }}</td>
                            <td class="text-end">{{ $val->action_date }}</td>
                            <td>{{ $val->user->name }}</td>

                        </tr>
                    @endforeach
                </table>
                {{ $history->links() }}

            </div>
            {{-- <div class="card-footer">

                        </div> --}}
        </div>
        <p></p>
        {{-- {{ $history->links() }} --}}
    </div>
@endsection
{{-- </body>
                </html> --}}
