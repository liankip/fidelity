<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="primary-color-sne">Log History Purchase</h2>
            </div>
        </div>
    </div>
    @foreach (['danger', 'warning', 'success', 'info'] as $key)
        @if (Session::has($key))
            <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                {{ Session::get($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @endif
    @endforeach
    <div class="card primary-box-shadow mt-5">

        <div class="card-body">
            <form action="" method="get" class="d-flex">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" wire:model="search" name="search" placeholder="Search"
                        value="" aria-label="Recipient's username" aria-describedby="button-addon2">
                </div>
            </form>
            <table class="table text-sm primary-box-shadow font-bold">
                <tr class="thead-light">
                    <th class="text-center border-top-left">No</th>
                    <th class="text-center">Referensi</th>
                    <th class="text-center">Action Start</th>
                    <th class="text-center">Action End</th>
                    <th class="text-center">Action Date</th>
                    <th class="text-center border-top-right">Action By</th>

                </tr>
                @foreach ($history as $key => $val)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $val->referensi ? $val->referensi : '-' }}</td>
                        <td>{{ $val->action_start }}</td>
                        <td>{{ $val->action_end }}</td>
                        <td>{{ date('d F Y, H:i', strtotime($val->action_date)) }}</td>
                        <td>{{ $val->user->name }}</td>

                    </tr>
                @endforeach
            </table>
            {{ $history->links() }}

        </div>

    </div>
</div>
