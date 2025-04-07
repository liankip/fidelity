@php use App\Models\Sku; @endphp
<div>
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2 class="primary-color-sne">Work Order</h2>
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

                <div class="pull-right mt-4 d-flex justify-content-between">
                    <div class="d-flex gap-3">
                        <div class="mr-2">
                            <a class="btn btn-success" href="{{ route('work-order.insert') }}">
                                <i class="fas fa-plus"></i>
                                Create Work Order
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card mt-2 primary-box-shadow">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="w-100">
                                <form action="" method="get" class="d-flex">
                                    <div class="input-group">
                                        <input class="form-control" wire:model="search" type="search"
                                            placeholder="Search">
                                    </div>
                                </form>
                            </div>
                        </div>

                        <table class="table primary-box-shadow mt-3">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center align-middle border-top-left">No. Work Order</th>
                                    <th class="text-center align-middle">Product</th>
                                    <th class="text-center align-middle">Quantity</th>
                                    <th class="text-center align-middle">Deadline</th>
                                    <th class="text-center align-middle">Status</th>
                                    <th class="text-center align-middle border-top-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($workOrder as $index => $w)
                                    <tr class="text-center">
                                        <td>{{ $w->number }}</td>
                                        <td>
                                            <ul class="list-group list-group-flush">
                                                @foreach (json_decode($w->product) as $p)
                                                    @php
                                                        $name = Sku::find($p->product)->name;
                                                    @endphp
                                                    <li class="list-group-item border-0">
                                                        <span class="bullet-point"></span> {{ $name }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <ul class="list-group list-group-flush">
                                                @foreach (json_decode($w->product) as $p)
                                                    <li class="list-group-item border-0">
                                                        {{ $p->qty }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($w->deadline_date)->format('d M Y') }}
                                        </td>
                                        <td>
                                            @if ($w->status == 'PENDING')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($w->status == 'STARTED')
                                                <span class="badge badge-primary">Work Started</span>
                                            @else
                                                <span class="badge badge-success">Finished</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('work-order.monitoring', $w->id) }}"
                                                class="btn btn-outline-success btn-sm">
                                                Monitoring
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center align-middle" colspan="5">No Data Work Order</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $workOrder->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
