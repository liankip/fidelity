<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <a href="{{ route('order.index', ['id' => $project_id]) }}" class="third-color-sne"> <i
                        class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                <h2 class="primary-color-sne">Monitoring</h2>
            </div>

            <livewire:common.alert />

            <div class="mt-3 mb-3 d-none d-lg-flex justify-content-between">
                <div class="d-lg-flex justify-content-between">
                    <button type="button" class="btn btn-outline-primary ml-3" data-bs-toggle="modal"
                        data-bs-target="#createPurchaseRequestModal">
                        Purchase Request
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
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

            <div style="overflow-x: scroll;" class="table-responsive-container">
                <table id="boqTable" class="table mt-3 primary-box-shadow">
                    <thead class="thead-light">
                        <tr class="table-secondary">
                            <th class="text-center align-middle border-top-left" width="5%">No</th>
                            <th class="text-center align-middle" width="15%">Item Name</th>
                            <th class="text-center align-middle" width="10%">Quantity</th>
                            <th class="text-center align-middle" width="5%">Unit</th>
                            <th class="text-center align-middle" width="10%">Price Estimation*</th>
                            <th class="text-center align-middle" width="10%">Shipping Cost Estimation*</th>
                            <th class="text-center align-middle" width="10%">Total Estimation**</th>
                            <th class="text-center align-middle" width="10%">Note</th>
                            <th class="text-center align-middle border-top-right" width="10%">Komentar</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($boqList as $b)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ $b->item->name }}</td>
                                <td class="text-center">{{ number_format($b->qty) }}</td>
                                <td class="text-center">{{ $b->unit->name }}</td>
                                <td class="text-center">{{ rupiah_format($b->price_estimation) }}</td>
                                <td class="text-end">
                                    {{ rupiah_format($b->shipping_cost) }}
                                </td>
                                <td class="text-end">
                                    {{ rupiah_format($b->price_estimation * $b->qty + $b->shipping_cost) }}
                                </td>
                                <td class="text-center">
                                    <div>
                                        @if ($b->origin)
                                            Kota Asal: {{ $b->origin }}
                                        @endif
                                    </div>
                                    <div>
                                        @if ($b->destination)
                                            Kota Tujuan: {{ $b->destination }}
                                        @endif
                                    </div>
                                    <div>
                                        {{ $b->note }}
                                    </div>
                                </td>
                                <td class="text-center">{{ $b->comment }}</td>
                            </tr>

                            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="deleteModalLabel">Delete Monitoring Order
                                            </h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure want to delete this item {{ $b->item->name }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-danger"
                                                wire:click="delete({{ $b->id }})" wire:loading.attr="disabled">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <td colspan="10" class="text-center">No data found</td>
                        @endforelse
                        <tr class="table-success">
                            <td colspan="6" class="text-end"><strong>Grand Total Estimation</strong></td>
                            <td class="text-end"><strong>{{ rupiah_format($totalPriceEstimation) }}</strong></td>
                            <td colspan="5"></td>
                        </tr>
                    </tbody>
                </table>

                <div wire:loading wire:target="setPage">
                    <div class="d-flex justify-content-center align-items-center min-vh-50 mt-5 mb-5">
                        <div class="spinner-grow text-success" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $boqList->links() }}
                </div>
            </div>
        </div>
    </div>

    <x-common.modal id="createPurchaseRequestModal" title="Purchase Request">
        <x-slot:modal-body>
            <form wire:submit.prevent="createPR">
                <div class="form-group">
                    <label for="pr_type" class="col-form-label">PR Type:<span class="text-danger">*</span></label>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" wire:model="type" value="Barang"
                            id="pr_type_1">
                        <label class="form-check-label" for="pr_type_1">Barang</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" wire:model="type" value="Jasa"
                            id="pr_type_2">
                        <label class="form-check-label" for="pr_type_2">Jasa</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" wire:model="type" value="Sewa Mesin"
                            id="pr_type_3">
                        <label class="form-check-label" for="pr_type_3">Sewa Mesin</label>
                    </div>

                    @error('type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="requester" class="col-form-label">Requester: <span
                            class="text-danger">*</span></label>
                    <input type="text" wire:model='requester' class="form-control" placeholder="Nama" required>
                </div>

                <div class="form-group">
                    <label for="remark">
                        <strong>Notes:</strong>
                    </label>
                    <textarea wire:model='remark' rows="4" class="form-control" placeholder="Keterangan"></textarea>
                </div>
                <x-common.modal.button-cancel />
                <button type="submit" class="btn btn-success">Save</button>
            </form>
        </x-slot:modal-body>
        <x-slot:modal-footer>
        </x-slot:modal-footer>
    </x-common.modal>
</div>
