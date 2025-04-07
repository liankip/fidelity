<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <a href="{{ route('bulk-purchase.index') }}" class="third-color-sne"> <i
                        class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                <h2 class="primary-color-sne">Bulk Purchase {{ $project->name }}</h2>
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
        </div>
    </div>

    @if ($project->status === 'Finished')
        @include('masterdata.boqs.read-only-boq')
    @else
        <div class="card primary-box-shadow mt-3">
            <div class="card-body">
                <div class="rounded bg-white p-3">
                    <div class="d-flex justify-content-between mb-3">
                        <button class="btn btn-info me-1" wire:click="export_boq"><i class="fa-solid fa-download"></i>
                            Export BOQ</button>

                        @if (count($selectedItems) > 0)
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Create Bulk Purchase</button>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between">
                        <p class="fw-semibold">Total Items : <span class="text-success">{{ $boqList->count() }}</span>
                        </p>
                        <div class="input-group mb-3 w-25">
                            <input wire:model="search" type="search" placeholder="Search by item name"
                                class="form-control border border-black rounded-start">
                        </div>
                    </div>

                    <div class="table-responsive-container">
                        <table class="table primary-box-shadow">
                            <thead class="thead-light">
                                <tr class="table-secondary">
                                    <th class="text-center align-items-center border-top-left" width="5%"></th>
                                    <th class="text-center align-items-center" width="5%">No</th>
                                    <th class="text-center align-middle" width="15%">Item Name</th>
                                    <th class="text-center align-middle" width="10%">Quantity</th>
                                    <th class="text-center align-middle" width="5%">Unit</th>
                                    <th class="text-center align-middle" width="10%">Price Estimation*</th>
                                    <th class="text-center align-middle" width="10%">Shipping Cost Estimation*</th>
                                    <th class="text-center align-middle" width="10%">Total Estimation**</th>
                                    <th class="text-center align-items-center not-export border-top-right"
                                        width="10%">
                                        Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                    $offset = ($boqList->currentPage() - 1) * $boqList->perPage();
                                @endphp
                                @forelse ($boqList as $boq)
                                    @php
                                        $isBoqExist = $boqsArray->where('id', $boq->id)->first();
                                        $canDelete = $isBoqExist && $isBoqExist['canDelete'];
                                        $isSelected = array_key_exists($boq->id, $selectedItems);

                                    @endphp
                                    <tr class="{{ $isSelected ? 'table-secondary' : '' }}">
                                        <td><input class="form-check-input" type="checkbox" wire:change="checkItems({{ $boq->id }}, '{{ $boq->item->name }}', '{{ $boq->qty }}')"></td>
                                        <td class="text-center">{{ $loop->iteration + $offset }}
                                        </td>
                                        <td>
                                            @php
                                                $po_status = $isBoqExist ? $isBoqExist['po_status'] : null;
                                            @endphp
                                            @if ($po_status && auth()->user()->hasAnyRole('it|top-manager|manager|purchasing'))
                                                <div>
                                                    <div class="mb-3">
                                                        <strong class="text-success">{{ $boq->item->name }} {{ $boq->item->id }}</strong>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="mb-3">
                                                    <strong class="text-success">{{ $boq->item->name }} {{ $boq->item->id }}</strong>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="w-100 badge text-bg-success">
                                                <div>
                                                    <small>
                                                        Sekarang:
                                                    </small>
                                                </div>
                                                <div>
                                                    <small>
                                                        <x-boq.quantity :boq="$boq" :boq-verification="$project->boq_verification"
                                                            :is-multiple-approval="$setting->multiple_approval" :boq-array="$isBoqExist" />
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $boq->unit->name }}</td>
                                        <td class="">
                                            @php
                                                $price = $boq->price_estimation;
                                                $historyPrice = $isBoqExist ? $isBoqExist['history_price'] : null;
                                            @endphp
                                            {{ rupiah_format($price) }}
                                        </td>
                                        <td class="text-end">
                                            {{ rupiah_format($boq->shipping_cost) }}
                                        </td>
                                        <td class="text-end">
                                            {{ rupiah_format($price * $boq->qty + $boq->shipping_cost) }}
                                        </td>
                                        <td class="text-start">
                                            <div>
                                                @if ($boq->origin)
                                                    Kota Asal: {{ $boq->origin }}
                                                @endif
                                            </div>
                                            <div>
                                                @if ($boq->destination)
                                                    Kota Tujuan: {{ $boq->destination }}
                                                @endif
                                            </div>
                                            <div>
                                                {{ $boq->note }}
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                        $total += $price === 0 ? 0 : $price * $boq->qty;
                                    @endphp
                                @empty
                                    <tr>
                                        @if ($adendum || auth()->user()->hasTopLevelAccess())
                                            <td colspan="10" class="text-center">No data found</td>
                                        @else
                                            <td colspan="9" class="text-center">No data found</td>
                                        @endif
                                    </tr>
                                @endforelse
                                <tr class="table-success">
                                    <td colspan="6" class="text-end"><strong>Grand Total Estimation</strong></td>
                                    <td class="text-end"><strong>{{ rupiah_format($total) }}</strong></td>
                                    <td colspan="3"></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center">
                            @if ($boqList->hasPages())
                                <ul class="pagination">
                                    @if ($boqList->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                    @else
                                        <li class="page-item">
                                            <button wire:click="setPage({{ $boqList->currentPage() - 1 }})"
                                                class="page-link">
                                                Previous
                                            </button>
                                        </li>
                                    @endif

                                    @foreach ($boqList->getUrlRange(1, $boqList->lastPage()) as $page => $url)
                                        <li class="page-item {{ $page == $boqList->currentPage() ? 'active' : '' }}">
                                            <button wire:click="setPage({{ $page }})"
                                                class="page-link">{{ $page }}</button>
                                        </li>
                                    @endforeach

                                    @if ($boqList->hasMorePages())
                                        <li class="page-item">
                                            <button wire:click="setPage({{ $boqList->currentPage() + 1 }})"
                                                class="page-link">
                                                Next
                                            </button>
                                        </li>
                                    @else
                                        <li class="page-item disabled"><span class="page-link">Next</span></li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Bulk Purchase</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            Create Bulk Purchase for {{ count($selectedItems) }} items?
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" wire:click="createBulk">Create</button>
            </div>
        </div>
        </div>
    </div>
</div>
