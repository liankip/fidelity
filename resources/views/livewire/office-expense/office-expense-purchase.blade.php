<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <a href="{{ route('office-expense.index') }}" class="third-color-sne"> <i
                        class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                <h2 class="primary-color-sne">Office Expense > {{ $office->office }}</h2>
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

            <div class="pull-right mb-2 mt-2 d-flex justify-content-between">
                <div class="d-flex gap-3">
                    <div class="mr-2">
                        <a class="btn btn-success" href="{{ route('office-expense.purchase.insert', $office->id) }}">
                            <i class="fas fa-plus"></i>
                            Create Office Expense Purchase
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card primary-box-shadow mt-3">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr class="table-secondary">
                        <th class="text-center align-items-center border-top-left">No</th>
                        <th class="text-center">Nama Pembelian</th>
                        <th class="text-center">Total</th>
                        <th class="text-center not-export border-top-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $d)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $d->purchase_name }}</td>
                            <td class="text-center">{{ rupiah_format($d->total_expense) }}
                            </td>
                            <td>
                                <div style="text-align: center; display: flex; justify-content: center; gap: 5px;">
                                    <a href="{{ route('office-expense.purchase.edit', ['office' => $office->id, 'id' => $d->id]) }}"
                                        class="btn btn-outline-primary">Edit</a>
                                    <a href="{{ route('office-expense.item', ['office' => $office->id, 'purchase' => $d->id]) }}"
                                        class="btn btn-outline-primary">Detail</a>
                                    <button wire:click="export('{{ $d->id }}')"
                                        class="text-center btn btn-outline-success">
                                        <i class="fa-solid fa-file-excel"></i>
                                        Export
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No Data Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4 d-flex justify-content-end">
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>
