<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left d-lg-flex justify-content-between">
            <div class="">
                <h2 class="text-black">Master Data <a class="text-decoration-none text-black"
                        href="{{ route('projects.index') }}">BOQ</a></h2>
                <h4 class="text-secondary"><strong>{{ $project->name }}</strong></h4>
            </div>

            <div class="text-center">
                <h4 class="text-secondary"><strong>
                        <span class="badge bg-success">Finished</span>
                    </strong></h4>
                <h6>
                    (Read Only)
                </h6>
            </div>

        </div>
        <hr>

        <div class="d-lg-flex justify-content-between">
            <h5>Project Budget = <strong>{{ rupiah_format($project->value) }}</strong></h5>
            <div class="">
                <select class="w-4 form-select" wire:model="show_version" wire:change="change_version">
                    @foreach ($version as $data)
                        @if ($data == 0)
                            <option value="{{ $data }}">Original</option>
                        @else
                            <option value="{{ $data }}">Version {{ $data }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <x-common.notification-alert />

        <div class="mt-5 d-none d-lg-flex justify-content-between">
            <div class="d-flex">
                <button class="btn btn-success me-1" wire:click="export_boq">Export BOQ</button>
                <a class="btn btn-primary" href="{{ route('printboq', $project->id) }}" target="_blank">
                    <i class="fas fa-print"></i>
                    Print BOQ
                </a>
                <a class="btn btn-info" href="{{ route('approved.index', $project->id) }}" target="_blank">
                    Approved Items
                </a>
            </div>
        </div>
    </div>
</div>

<div class="d-lg-flex justify-content-between mt-3 align-items-center">
    <div class="d-flex gap-3">
        <x-common.select-normal label="Sort By" wire:model="sortBy">
            <option value="name">Item Name</option>
            <option value="created_at">Created At</option>
        </x-common.select-normal>
        <x-common.select-normal label="Filter" id="filter" wire:model="filter">
            <option value="all">All</option>
            <option value="waiting_for_approval">Waiting for approval</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </x-common.select-normal>
    </div>
</div>

<div>
    <div class="mt-3 pull-right">
        <p class="fw-semibold">Total Items : <span class="text-success">{{ $boqList->count() }}</span></p>
    </div>
    <div class="table-responsive-container">
        <table id="boqTable" class="table table-bordered mt-3">
            <thead>
                <tr class="table-secondary">

                    <th class="text-center align-middle" width="5%">No</th>
                    <th class="text-center align-middle" width="15%">Item Name</th>
                    <th class="text-center align-middle" width="10%">Quantity</th>
                    <th class="text-center align-middle" width="5%">Unit</th>
                    <th class="text-center align-middle" width="10%">Price Estimation*</th>
                    <th class="text-center align-middle" width="10%">Shipping Cost Estimation*</th>
                    <th class="text-center align-middle" width="10%">Total Estimation**</th>
                    <th class="text-center align-middle" width="10%">Note</th>
                    <th class="text-center align-middle" width="15%">Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @forelse ($boqList as $boq)
                    @php
                        $isBoqExist = $boqsArray->where('id', $boq->id)->first();
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            @php
                                $po_status = $isBoqExist ? $isBoqExist['po_status'] : null;
                            @endphp
                            @if (
                                $po_status &&
                                    auth()->user()->hasAnyRole('it|top-manager|manager|purchasing'))
                                <div>
                                    <p class="text-success">{{ $boq->item->name }}</p>
                                    <p class="text-success">PO Approved : {{ (int) $po_status['qty_total'] }}
                                        / {{ (int) $boq->qty }}</p>
                                    <p>
                                        <a class="" data-bs-toggle="collapse" href="#po-list-{{ $boq->id }}"
                                            role="button" aria-expanded="false"
                                            aria-controls="po-list-{{ $boq->id }}">
                                            See PO List <i class="fas fa-caret-down"></i>
                                        </a>
                                    </p>
                                    <div class="collapse" id="po-list-{{ $boq->id }}">
                                        <ul>
                                            @foreach ($po_status['list'] as $po)
                                                <li>
                                                    <a href="{{ route('po-detail', $po['po_id']) }}"
                                                        target="_blank">{{ $po['po_no'] }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @else
                                {{ $boq->item->name }}
                            @endif
                        </td>
                        <td class="text-center">
                            <x-boq.quantity :boq="$boq" :boq-verification="$project->boq_verification" :is-multiple-approval="$setting->multiple_approval" :boq-array="$isBoqExist" />
                        </td>
                        <td>{{ $boq->unit->name }}</td>
                        <td class="text-end">
                            @php
                                $price = $boq->price_estimation;
                                $historyPrice = $isBoqExist ? $isBoqExist['history_price'] : null;
                            @endphp

                            @if ($historyPrice)
                                @if ($historyPrice['price'] < $price)
                                    <div class="d-flex flex-column">
                                        @if (auth()->user()->hasTopLevelAccess())
                                            <p>
                                                Expected Price:
                                                <span>
                                                    <span>{{ rupiah_format($price) }}</span>
                                                </span>
                                            </p>
                                            <p>
                                                History Price:
                                                <span>{{ rupiah_format($historyPrice['price']) }}</span>
                                            </p>
                                            @if ($price > 0 && $historyPrice['price'] != $price)
                                                <span class="text-danger">
                                                    {{ ceil((($historyPrice['price'] - $price) / $price) * 100) }}%
                                                </span>
                                            @endif
                                        @else
                                            <p>
                                                Expected Price:
                                                <span>{{ rupiah_format($price) }}</span>
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <div class="d-flex flex-column">
                                        @if (auth()->user()->hasTopLevelAccess())
                                            <p>
                                                Expected Price:
                                                <span>{{ rupiah_format($price) }}</span>
                                            </p>
                                            <p>
                                                History Price:
                                                <span>{{ rupiah_format($historyPrice['price']) }}</span>
                                            </p>
                                            @if ($price > 0 && $historyPrice['price'] != $price)
                                                <span class="text-success">
                                                    +{{ ceil((($historyPrice['price'] - $price) / $price) * 100) }}%
                                                </span>
                                            @endif
                                        @else
                                            <p>
                                                Expected Price:
                                                <span>{{ rupiah_format($price) }}</span>
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            @else
                                {{ rupiah_format($price) }}
                            @endif
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
                        <td class="text-start">
                            <x-boq.status-approval :boq="$boq" :setting="$setting" :max_version="$max_version" />
                        </td>
                    </tr>
                    @php
                        $total += $price === 0 ? 0 : $price * $boq->qty;
                    @endphp
                @empty
                    <tr>
                        @if (
                            $adendum ||
                                auth()->user()->hasTopLevelAccess())
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
    </div>
</div>
