@php
    use App\Models\Item;
@endphp
<div>
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2 class="primary-color-sne">SKU</h2>
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
                            <a class="btn btn-success" href="{{ route('sku.insert') }}">
                                <i class="fas fa-plus pe-2"></i>
                                Create SKU
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
                                        <input class="form-control" wire:model="search" type="search"
                                            placeholder="Search">
                                    </div>
                                </form>
                            </div>
                        </div>

                        <table class="table mt-3 primary-box-shadow">
                            <thead class="thead-light">
                                <tr class="text-center align-items-center">

                                    <th class="border-top-left">No</th>
                                    <th>Nama</th>
                                    <th>BOQ / Item</th>
                                    <th>Harga Modal</th>
                                    <th class="border-top-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sku as $index => $s)
                                    <tr>
                                        <td class="text-center align-items-center">{{ $index + 1 }}</td>
                                        <td class="text-center align-items-center">{{ $s->name }}
                                            <br>
                                            <small class="badge badge-success">Available: {{ $s['available_qty'] }}</small>
                                        </td>

                                        <td>
                                            <ul class="list-group list-group-flush">
                                                @foreach ($s->boq as $item)
                                                    @php
                                                        $name = Item::where('id', $item[0])->first()->name;
                                                        $stockQty = $this->checkRawMaterialStock($item[0]);
                                                    @endphp
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong class="text-primary">{{ $name }}</strong> <br>
                                                            <span class="text-muted">{{ rupiah_format($item[2]) }} / {{ $item[1] }}</span> <br>
                                                            <span class="text-muted">Quantity: {{ $item[3] }}</span>
                                                        </div>
                                                        <span class="badge bg-success rounded-pill px-3 py-2">Available: {{ $stockQty }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            
                                        </td>
                                        <td class="text-center align-items-center">{{ rupiah_format($s->total_modal_price !== 0 ? $s->total_modal_price : $s->total_items_price) }}</td>

                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a class="btn btn-primary me-2" href="{{ route('sku.edit', $s->id) }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="deleteModal" tabindex="-1"
                                        aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="deleteModalLabel">Delete Product
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure want to delete this product?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-danger"
                                                        wire:click="delete({{ $s->id }})"
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
                            {{ $sku->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
