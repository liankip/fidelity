<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pull-left">
                <h2 class="primary-color-sne">Order</h2>
            </div>

            @foreach (['danger', 'warning', 'success', 'info'] as $key)
                @if (Session::has($key))
                    <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                        {{ Session::get($key) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @endforeach

            <div class="pull-right mb-2 mt-5 d-flex justify-content-between">
                <div class="d-flex gap-3">
                    <div class="mr-2">
                        <a class="btn btn-success" href="{{ route('order.insert', $project_id) }}">
                            <i class="fas fa-plus"></i>
                            Create Order
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-2 primary-box-shadow">
                <div class="card-body">
                    <div class="d-flex mt-3">
                        <div class="w-100">
                            <form action="" method="get" class="d-flex">
                                <div class="input-group">
                                    <input class="form-control" wire:model="search" type="search" placeholder="Search">
                                </div>
                            </form>
                        </div>
                    </div>

                    <table class="table primary-box-shadow mt-3">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-top-left">No</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th class="border-top-right"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse ($order as $index => $o)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $o->product ? $o->product->nama : 'No Product' }}</td>
                                    <td>{{ $o->quantity }}</td>
                                    <td>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ route('order.monitoring', ['id' => $project_id, 'order' => $o->id]) }}"
                                                class="btn btn-outline-success">
                                                Monitoring
                                            </a>
                                            <a class="btn btn-outline-info"
                                                href="{{ route('order.edit', ['id' => $project_id, 'order' => $o->id]) }}">Edit</a>
                                            <button class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="deleteModal" tabindex="-1"
                                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="deleteModalLabel">Delete order</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure want to delete this order?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-danger"
                                                    wire:click="delete({{ $o->id }})"
                                                    wire:loading.attr="disabled">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No Data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $order->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
