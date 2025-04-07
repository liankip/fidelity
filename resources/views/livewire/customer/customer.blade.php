<div>
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2 class="primary-color-sne">Customer</h2>
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

                <div class="pull-right mb-2 mt-5 d-flex justify-content-between">

                    <div class="d-flex gap-3">
                        <div class="mr-2">
                            <a class="btn btn-success" href="{{ route('customer.insert') }}">
                                <i class="fas fa-plus"></i>
                                Create Customer
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
                                <tr class="text-center align-items-center">
                                    <th class="border-top-left">No</th>
                                    <th>Customer</th>
                                    <th>Total Spending</th>
                                    <th class="border-top-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customer as $index => $c)
                                    <tr class="text-center align-items-center">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $c->name }}</td>
                                    <td></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-12 mb-2">
                                                    <a class="btn btn-outline-success w-25" href="{{ route('customer.history', $c->id) }}">
                                                        Riwayat
                                                    </a>
                                                </div>
                                                <div class="col-12">
                                                    <a class="btn btn-outline-primary w-25" href="{{ route('customer.edit', $c->id) }}">
                                                        Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="deleteModal" tabindex="-1"
                                        aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="deleteModalLabel">Delete Customer
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure want to delete this customer?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-danger"
                                                        wire:click="delete({{ $c->id }})"
                                                        wire:loading.attr="disabled">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $customer->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
