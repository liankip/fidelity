@extends('layouts.app')

@section('content')
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Log History Payment</h2>
                    <hr>
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card primary-box-shadow mt-5">
            <div class="card-body">

                <table class="table primary-box-shadow text-sm font-bold">
                    <thead class="thead-light">
                        <tr class="table-secondary">
                            <th class="text-center border-top-left">No</th>
                            <th class="text-center">Referensi</th>
                            <th class="text-center">Action Start</th>
                            <th class="text-center">Action End</th>
                            <th class="text-center">Action Date</th>
                            <th class="text-center border-top-right">Action By</th>
                        </tr>
                    </thead>
                    @foreach ($history as $key => $val)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $val->referensi }}</td>
                            <td>{{ $val->action_start }}</td>
                            <td>{{ $val->action_end }}</td>
                            <td>{{ date('d F Y, H:i', strtotime($val->action_date)) }}</td>
                            <td>{{ $val->user->name }}</td>

                        </tr>
                    @endforeach
                </table>

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
