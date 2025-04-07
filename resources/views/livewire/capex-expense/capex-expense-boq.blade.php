@php
    use Carbon\Carbon;
    use App\Models\PurchaseRequest;
@endphp
<div class="mt-2">
    <style>
        /* Modal backdrop */
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Modal content */
        .custom-modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Close button */
        .custom-modal-close {
            color: #aaa;
            float: right;
            cursor: pointer;
        }

        .custom-modal-close:hover,
        .custom-modal-close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <a href="{{ route('capex-expense.index') }}" class="third-color-sne"> <i
                        class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                <h2 class="primary-color-sne">Capex Expense BOQ</h2>
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

    <div class="col-lg-12 margin-tb">
        <div class="dropdown d-flex d-block d-lg-none">
            <a class="btn btn-primary btn-lg w-100 d-flex justify-content-center align-items-center" href="#"
                role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="text-center">Action</span>
                <i class="fa-solid fa-chevron-down"></i>
            </a>
            <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuLink">
                <li>
                    <a href="{{ route('capex-expense.boq.list', ['project_id' => $project_id]) }}" class="dropdown-item"
                        type="button">
                        <i class="fa-solid fa-plus"></i> Tambah BOQ
                    </a>
                </li>

            </ul>
        </div>

        <hr class="border border-3 border-dark">

        <div class="mt-3 mb-3 d-none d-lg-flex justify-content-between">
            <div class="d-lg-flex justify-content-between">
                <a href="{{ route('capex-expense.boq.list', ['project_id' => $project_id]) }}" class="btn btn-success"
                    type="button">
                    <i class="fa-solid fa-plus"></i> Tambah BOQ
                </a>

                <button class="btn btn-info me-1" wire:click="export_boq"><i class="fa-solid fa-download"></i>
                    Export BOQ
                </button>
                <a href="{{ route('printboq', $project_id) }}" class="btn btn-info" target="_blank">
                    <i class="fas fa-print"></i>
                    Print BOQ
                </a>
                <a href="{{ route('approved.index', $project_id) }}" class="btn btn-success" target="_blank">
                    Approved Items
                </a>
                {{-- @dd($countPurchaseRequestDetail, count($boqList), $countPurchaseRequestPrNo) --}}
                {{-- @if ($countPurchaseRequestDetail != 1 && count(value: $boqList) > 0 && $countPurchaseRequestPrNo != 1) --}}
                    <button type="button" class="btn btn-outline-primary ml-3 btnCreatePR">
                        Purchase Request
                    </button>
                {{-- @else
                    <button type="button" class="btn btn-secondary ml-3" style="cursor: not-allowed">
                        Purchase Request
                    </button>
                @endif --}}

            </div>
        </div>
    </div>

    <div class="d-lg-flex justify-content-between mt-3 align-items-center">
        <div class="d-flex flex-column gap-3">
            <x-common.select-normal label="Sort By" wire:model="sortBy" class="w-100">
                <option value="name">Item Name</option>
                <option value="created_at">Created At</option>
            </x-common.select-normal>
            <x-common.select-normal label="Filter" id="filter" wire:model="filter" class="w-100">
                <option value="all">All</option>
                <option value="waiting_for_approval">Waiting for approval</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="purchased">Purchased</option>
                <option value="unpurchased">Unpurchased</option>
            </x-common.select-normal>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <p class="fw-semibold mb-2 text-left">
                    Total Items: <span class="text-success" id="boqTotal">{{ $countBoqList }}</span>
                </p>

                <div class="position-relative mb-3">
                    <input type="text" placeholder="Search by item name"
                        class="form-control rounded-pill pl-4 pr-5 border border-black shadow-sm" id="boqItemSearch">
                    <span class="position-absolute top-50 end-0 translate-middle-y pr-3">
                    </span>
                </div>

                <div class="" x-data="{ loading: @entangle('loading') }">
                    @if (auth()->user()->hasGeneralAccess())
                        <div class="mr-2">
                            @if ($adendum)
                                @can(Permission::CREATE_ADENDUM)
                                    <div class="d-flex justify-content-between gap-3">
                                        @if ($project->boq_verification)
                                            @if (auth()->user()->boq_verificator == 1)
                                                <div class="mt-3">
                                                    <button class="btn btn-success" wire:click="hide_adendum"
                                                        wire:loading.attr="disabled">Save & Ajukan ke
                                                        Management
                                                    </button>
                                                    <button class="btn btn-danger" wire:click="reject_from_manager">
                                                        Reject
                                                    </button>
                                                </div>
                                            @endif
                                        @else
                                            @if ($project->hasBoqAccess())
                                                <button class="btn btn-success" wire:click="hide_adendum"
                                                    wire:loading.attr="disabled">
                                                    Save & Ajukan ke
                                                    Management
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                @endcan
                            @else
                                @php
                                    $latestBOQ = App\Models\BOQ::where('project_id', $project->id)
                                        ->latest('created_at')
                                        ->first();

                                    $updatedAt = null;
                                    $isOldBOQ = false;
                                    $isNewBOQ = false;
                                    $isSameSection = false;
                                    $needApproval = false;

                                    if ($latestBOQ) {
                                        $updatedAt = Carbon::parse($latestBOQ->updated_at)->format('Y-m-d');

                                        $cutoffDate = Carbon::parse('2025-01-20');
                                        $updatedAtDate = Carbon::parse($latestBOQ->updated_at);

                                        $isOldBOQ =
                                            $updatedAtDate->lessThan($cutoffDate) && is_null($latestBOQ->approved_by_3);
                                        $isNewBOQ = $updatedAtDate->greaterThanOrEqualTo($cutoffDate);

                                        $isSameSection = $latestBOQ->section == $this->section;
                                    }

                                    $needApproval =
                                        $needToApprove &&
                                        ($countBoqIsApprovedFirst != 0 ||
                                            $countBoqIsApprovedSecond != 0 ||
                                            $countBoqIsApprovedThird != 0);
                                @endphp
                                @if ($latestBOQ != null)
                                    <script>
                                        var boqSection = @json($latestBOQ->section)
                                    </script>
                                @endif

                                @if (auth()->user()->hasTopLevelAccess() || auth()->user()->can('approve-boq'))
                                    @if ($project->boq_verification == 1 && $needApproval)
                                        <div class="justify-content-end gap-2" id="actionContainer"
                                            style="display: none">
                                            @if ($isOldBOQ)
                                                <button class="btn btn-secondary pull-right" disabled>BOQ Lama
                                                    (Tidak
                                                    Bisa
                                                    Disetujui Lagi)
                                                </button>
                                            @elseif ($isNewBOQ)
                                                <button class="btn btn-primary btn-approve">
                                                    Approve
                                                </button>
                                                <button class="btn btn-danger btnModal">
                                                    Reject
                                                </button>
                                            @else
                                                <span>BOQ Tidak Valid</span>
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            @endif
                        </div>
                    @endif
                </div>

                <div style="overflow-x: scroll;" class="table-responsive-container">
                    <table id="boqTable" class="table mt-3 primary-box-shadow">
                        <thead class="thead-light">
                            <tr class="table-secondary">
                                @if (
                                    ($adendum && auth()->user()->hasTopLevelAccess()) ||
                                        (!$adendum && auth()->user()->hasTopLevelAccess()) ||
                                        ($adendum && auth()->user()->can('approve-boq')) ||
                                        (!$adendum && auth()->user()->can('approve-boq')))
                                    <th class="text-center align-middle border-top-left" width="5%">No
                                    </th>
                                    <th class="text-center align-middle" width="15%">Item Name</th>
                                    <th class="text-center align-middle" width="10%">Quantity</th>
                                    <th class="text-center align-middle" width="5%">Unit</th>
                                    <th class="text-center align-middle" width="10%">Price Estimation*</th>
                                    <th class="text-center align-middle" width="10%">Shipping Cost
                                        Estimation*
                                    </th>
                                    <th class="text-center align-middle" width="10%">Total Estimation**
                                    </th>
                                    <th class="text-center align-middle" width="10%">Note</th>
                                    <th class="text-center align-middle" width="10%">Komentar</th>
                                    <th class="text-center align-middle" width="10%">Status</th>
                                    <th class="text-center align-middle border-top-right" width="10%">
                                        @if (auth()->user()->hasTopLevelAccess() || auth()->user()->can('approve-boq'))
                                            <div class="actionContainer2">
                                                Action <br>
                                                @if ($isOldBOQ)
                                                    <input type="checkbox" class="form-check-input" id="select-all"
                                                        disabled>
                                                @elseif ($isNewBOQ && $needApproval)
                                                    <input type="checkbox" id="check-all" class="check-all"
                                                        data-section="0">
                                                @else
                                                @endif
                                            </div>
                                        @endif
                                    </th>
                                @elseif($adendum)
                                    <th class="text-center align-middle border-top-left" width="5%">No
                                    </th>
                                    <th class="text-center align-middle" width="15%">Item Name</th>
                                    <th class="text-center align-middle" width="10%">Quantity</th>
                                    <th class="text-center align-middle" width="5%">Unit</th>
                                    <th class="text-center align-middle" width="10%">Price Estimation*</th>
                                    <th class="text-center align-middle" width="10%">Shipping Cost
                                        Estimation*
                                    </th>
                                    <th class="text-center align-middle" width="10%">Total Estimation**
                                    </th>
                                    <th class="text-center align-middle" width="10%">Note</th>
                                    <th class="text-center align-middle" width="10%">Komentar</th>
                                    <th class="text-center align-middle" width="15%">Status</th>
                                    <th class="text-center align-middle border-top-right" width="10%">
                                        Action
                                    </th>
                                @else
                                    <th class="text-center align-middle border-top-left" width="5%">No
                                    </th>
                                    <th class="text-center align-middle" width="15%">Item Name</th>
                                    <th class="text-center align-middle" width="10%">Quantity</th>
                                    <th class="text-center align-middle" width="5%">Unit</th>
                                    <th class="text-center align-middle" width="10%">Price Estimation*</th>
                                    <th class="text-center align-middle" width="10%">Shipping Cost
                                        Estimation*
                                    </th>
                                    <th class="text-center align-middle" width="10%">Total Estimation**
                                    </th>
                                    <th class="text-center align-middle" width="10%">Note</th>
                                    <th class="text-center align-middle" width="10%">Komentar</th>
                                    <th class="text-center align-middle" width="15%">Status</th>
                                    <th class="text-center align-middle border-top-right" width="10%">
                                        Action
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @php
                                $filterBOq = $allBoqList;
                                $grandTotal = 0;
                            @endphp
                            @forelse ($filterBOq as $wbs => $boq)
                                @php
                                    $isBoqExist = $boqsArray->where('id', $boq->id)->first();
                                    $canDelete = $isBoqExist && $isBoqExist['canDelete'];
                                    $createdAt = Carbon::parse($boq->created_at)->format('Y-m-d');
                                    $updatedAt = $boq->updated_at
                                        ? Carbon::parse($boq->updated_at)->format('Y-m-d')
                                        : null;

                                    $currentUserId = auth()->user()->id;
                                    $approvedByIds = [$boq->approved_by, $boq->approved_by_2, $boq->approved_by_3];

                                    $boq_verification = $project->boq_verification;
                                    $hasTopLevelAccess = auth()->user()->hasTopLevelAccess();
                                    $canApprove = auth()->user()->can('approve-boq');

                                    $allApproved = !in_array(null, $approvedByIds, true);
                                    $isAlreadyApproved = in_array($currentUserId, $approvedByIds);

                                    $shouldShowCheckbox = false;

                                    if (!$isAlreadyApproved) {
                                        $shouldShowCheckbox =
                                            $boq_verification && is_null($boq->rejected_by) && !$allApproved;
                                    }
                                    $grandTotal = $this->getGrandTotal($section);
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        @if (auth()->user()->hasAnyRole('it|top-manager|manager|purchasing'))
                                            <div>
                                                <div class="mb-3">
                                                    <strong class="text-success">{{ $boq->item->name }}</strong>
                                                </div>
                                            @else
                                                <strong class="text-success">{{ $boq->item->name }}</strong>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($dataSpreadsheet->contains('project_id', $project_id))
                                            <div class="w-100 badge text-bg-primary mb-1">
                                                <small class="flex">
                                                    Qty PR:
                                                </small>
                                                <x-boq.history-quantity :data="$dataSpreadsheet" :itemid="$boq->item->id"
                                                    :boqdata="$boq" :project_id="$project_id" :task_number="''" />
                                            </div>
                                        @endif

                                        @if (
                                            ($boq->rejected_by == null || $boq->rejected_by == 0) &&
                                                ($boq->approved_by == null ||
                                                    $boq->approved_by == 0 ||
                                                    ($boq->approved_by_2 == null || $boq->approved_by_2 == 0) ||
                                                    ($boq->approved_by_3 == null || $boq->approved_by_3 == 0)))
                                            <x-boq.additional-quantity :data="$dataSpreadsheet" :itemid="$boq->item->id"
                                                :boqdata="$boq" :boq="$boq" :boq-verification="$project->boq_verification" :is-multiple-approval="$setting->multiple_approval"
                                                :boq-array="$isBoqExist" :project_id="$project_id" :task_number="''"
                                                :section="0" :select_section="0" :updated_at="$updatedAt" />
                                        @endif

                                        <div class="w-100 badge text-bg-success">
                                            <div>
                                                <small>
                                                    Total Budgeted:
                                                </small>
                                            </div>
                                            <div>
                                                <small>
                                                    <x-boq.quantity :boq="$boq" :boq-verification="$project->boq_verification"
                                                        :is-multiple-approval="$setting->multiple_approval" :boq-array="$isBoqExist" :allApproved="$allApproved"
                                                        :updatedAt="$updatedAt" :project_id="$project_id" :task_number="''"
                                                        :isSameSection="''" />
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

                                        @if ($historyPrice)
                                            @if ($historyPrice['price'] < $price)
                                                <div class="d-flex flex-column">
                                                    @if (auth()->user()->hasTopLevelAccess())
                                                        <p>
                                                            <span>
                                                                <span>{{ rupiah_format($price) }}</span>
                                                            </span>
                                                        </p>
                                                        <div class="border alert alert-light text-sm">
                                                            <div>
                                                                <strong>History:</strong>
                                                            </div>
                                                            <div>
                                                                <span>{{ rupiah_format($historyPrice['price']) }}</span>
                                                            </div>
                                                            @if ($price > 0 && $historyPrice['price'] != $price)
                                                                <div class="mt-3">
                                                                    <strong class="text-danger">
                                                                        {{ ceil((($historyPrice['price'] - $price) / $price) * 100) }}
                                                                        %
                                                                    </strong>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <p>
                                                            <span>
                                                                <span>{{ rupiah_format($price) }}</span>
                                                            </span>
                                                        </p>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="d-flex flex-column">
                                                    @if (auth()->user()->hasTopLevelAccess())
                                                        <p>
                                                            <span>
                                                                <span>{{ rupiah_format($price) }}</span>
                                                            </span>
                                                        </p>
                                                        <div class="border alert alert-light text-sm">
                                                            <div>
                                                                <strong>History:</strong>
                                                            </div>
                                                            <div>
                                                                <span>{{ rupiah_format($historyPrice['price']) }}</span>
                                                            </div>
                                                            @if ($price > 0 && $historyPrice['price'] != $price)
                                                                <div class="mt-3">
                                                                    <strong class="text-success">
                                                                        +{{ ceil((($historyPrice['price'] - $price) / $price) * 100) }}
                                                                        %
                                                                    </strong>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <p>
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
                                    <td>{{ $boq->comment }}</td>
                                    <td class="text-start">
                                        <x-boq.status-approval :boq="$boq" :setting="$setting" :max_version="$max_version"
                                            :task="''" />
                                    </td>
                                    @if ($adendum)
                                        <td class="text-center"></td>
                                    @elseif(auth()->user()->can('approve-boq'))
                                        @if ($setting->multiple_approval)
                                            @if ($shouldShowCheckbox)
                                                <td class="text-center">
                                                    <input type="checkbox" value="{{ $boq->id }}"
                                                        class="item-checkbox" data-section="{{ $boq->section }}">
                                                </td>
                                            @else
                                                <td class="text-center"></td>
                                            @endif
                                        @else
                                            @if ($max_version == (int) $boq->revision && empty($boq->approved) && $boq_verification && is_null($boq->rejected_by))
                                                <td class="text-center">
                                                    <input type="checkbox" value="{{ $boq->id }}"
                                                        class="item-checkbox" data-section="{{ $boq->section }}">
                                                </td>
                                            @else
                                                <td class="text-center"></td>
                                            @endif
                                        @endif
                                    @elseif($adendum || auth()->user()->hasTopLevelAccess())
                                        <td class="text-center"></td>
                                    @else
                                        <td class="text-center"></td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    @if ($adendum || auth()->user()->hasTopLevelAccess())
                                        <td colspan="10" class="text-center">No data found</td>
                                    @else
                                        <td colspan="9" class="text-center">No data found</td>
                                    @endif
                                </tr>
                            @endforelse
                            @if (($adendum && auth()->user()->hasTopLevelAccess()) || (!$adendum && auth()->user()->hasTopLevelAccess()))
                                <tr class="table-success">
                                    <td colspan="6" class="text-end"><strong>Grand Total
                                            Estimation</strong>
                                    </td>
                                    <td class="text-end"><strong>{{ rupiah_format($grandTotal) }}</strong>
                                    </td>
                                    <td colspan="6"></td>
                                </tr>
                            @elseif($adendum)
                                <tr class="table-success">
                                    <td colspan="6" class="text-end"><strong>Grand Total
                                            Estimation</strong>
                                    </td>
                                    <td class="text-end"><strong>{{ rupiah_format($grandTotal) }}</strong>
                                    </td>
                                    <td colspan="6"></td>
                                </tr>
                            @else
                                <tr class="table-success">
                                    <td colspan="6" class="text-end"><strong>Grand Total
                                            Estimation</strong>
                                    </td>
                                    <td class="text-end"><strong>{{ rupiah_format($grandTotal) }}</strong>
                                    </td>
                                    <td colspan="5"></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="customModal" class="custom-modal" wire:ignore.self>
        <div class="custom-modal-content">
            <span class="custom-modal-close">&times;</span>
            <h5>Reject BOQ</h5>
            <p>Are you sure you want to reject this BOQ?</p>
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button class="btn btn-sm btn-secondary custom-modal-close">Cancel</button>
                <button class="btn btn-sm btn-danger btn-reject d-flex align-items-center gap-2">Reject
                    <div class="spinner-border text-primary" role="status" id="loadingSpinner"
                        style="display: none">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <div id="createPurchaseRequestModal" class="custom-modal" wire:ignore.self>
        <div class="custom-modal-content">
            <span class="custom-modal-close">&times;</span>
            <h5>Purchase Request</h5>

            <form class="create-pr-form" wire:submit.prevent="createPR">
                @csrf
                <input type="hidden" name="capex_expense" value="capex_expense">
                <div class="form-group">
                    <label for="pr_type" class="col-form-label">PR Type:<span class="text-danger">*</span></label>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" wire:model="type"
                            value="Barang" id="pr_type_1">
                        <label class="form-check-label" for="pr_type_1">Barang</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" wire:model="type"
                            value="Jasa" id="pr_type_2">
                        <label class="form-check-label" for="pr_type_2">Jasa</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" wire:model="type"
                            value="Sewa Mesin" id="pr_type_3">
                        <label class="form-check-label" for="pr_type_3">Sewa Mesin</label>
                    </div>

                    @error('type')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="requester" class="col-form-label">Requester: <span
                            class="text-danger">*</span></label>
                    <input type="text" name='requester' wire:model="requester" class="form-control"
                        placeholder="Nama" required>
                </div>

                <div class="form-group">
                    <label for="remark">
                        <strong>Notes:</strong>
                    </label>
                    <textarea name='remark' rows="4" wire:model="remark" class="form-control" placeholder="Keterangan"></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" class="btn btn-sm btn-secondary custom-modal-close">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success d-flex align-items-center gap-2">
                        Save
                        <div class="spinner-border text-primary" role="status" id="loadingSpinner"
                            style="display: none">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('javascript')
        <script>
            let publicSection = 0;

            function onContentLoad() {

                var boqList = @json($boqList);
                var allBoqList = @json($allBoqList);

                function sendCheckedIds(action) {
                    let selectedIds = $(".item-checkbox:checked").map(function() {
                        return $(this).val();
                    }).get();

                    // Ensure unique IDs
                    selectedIds = [...new Set(selectedIds)];
                    Livewire.emit("updateSelectedBoqIds", selectedIds, action);
                }

                function initializeCheck(param = null) {
                    let currentUserId = {{ auth()->user()->id }};
                    let selectedSection = param !== null ? param : 0;


                    let filteredBoqList = boqList.filter(function(item) {
                        return item.section == selectedSection;
                    });

                    let allRejected = filteredBoqList.every(function(item) {
                        return item.rejected_by !== null;
                    });

                    let hasNullApproval = filteredBoqList.filter(item =>
                        item.approved_by === null ||
                        item.approved_by_2 === null ||
                        item.approved_by_3 === null
                    );

                    // Check if every record in hasNullApproval is approved by the current user
                    let userApprovedAllNulls = hasNullApproval.every(item =>
                        item.approved_by === currentUserId ||
                        item.approved_by_2 === currentUserId ||
                        item.approved_by_3 === currentUserId
                    );

                    // Check if the current user has approved any item in this section
                    let userAlreadyApproved = filteredBoqList.every(function(item) {
                        return item.approved_by == currentUserId ||
                            item.approved_by_2 == currentUserId ||
                            item.approved_by_3 == currentUserId;
                    });

                    // Determine if action container should be visible
                    let shouldShowActionContainer = (boqSection == selectedSection) && !userApprovedAllNulls;
                    let checkAllSelector = $(`.check-all[data-section="${selectedSection}"]`);
                    let allApproved = filteredBoqList.every(function(item) {
                        return item.approved_by_2 !== null && item.approved_by_3 !== null;
                    })

                    // Manage visibility of actionContainer
                    if (shouldShowActionContainer) {
                        // Show container if section matches and not all rejected
                        $('#actionContainer').addClass('d-flex').show();
                        $('.actionContainer2').show();

                        // Hide checkboxes for rejected items in the current section
                        filteredBoqList.forEach(function(item) {
                            if (item.rejected_by !== null) {
                                $(`.item-checkbox[data-section="${selectedSection}"][value="${item.id}"]`)
                                    .hide();
                            } else if (item.rejected_by == null && boqSection == selectedSection) {
                                $(`.item-checkbox[data-section="${selectedSection}"][value="${item.id}"]`)
                                    .show();
                            }
                        });
                    } else {
                        // Hide container otherwise
                        $('#actionContainer').removeClass('d-flex').hide();
                        $('.actionContainer2').hide();
                        $(`.item-checkbox[data-section="${selectedSection}"]`).hide();
                    }

                    // Handle user approval visibility
                    if (userAlreadyApproved || allApproved) {
                        $('#actionContainer').removeClass('d-flex').hide();
                        $('.actionContainer2').hide();
                    } else if (shouldShowActionContainer) {
                        // Only show check-all if actionContainer should be visible
                        $('#actionContainer').addClass('d-flex').show();
                        checkAllSelector.show();
                    }

                    let totalItems = filteredBoqList.length;

                    $('#boqTotal').text(totalItems);
                }

                initializeCheck(publicSection);

                window.addEventListener('tabChanged', function(event) {
                    initializeCheck(publicSection)
                });

                // Check/Uncheck all checkboxes within the same section
                $(document).on("change", ".check-all", function() {
                    let section = $(this).data("section");
                    let isChecked = $(this).prop("checked");

                    let checkboxesInSection = $(`.item-checkbox[data-section="${section}"]`);
                    if (checkboxesInSection.length === 0) {
                        section = 'consumables';
                    }

                    // Only check/uncheck checkboxes within the same section
                    $(`.item-checkbox[data-section="${section}"]`).prop("checked", isChecked).trigger("change");
                });

                // Handle individual checkbox changes
                $(document).on("change", ".item-checkbox", function() {
                    let section = $(this).data("section");
                    // Get selected items in the same section
                    let selectedItems = $(`.item-checkbox[data-section="${section}"]:checked`).map(function() {
                        return {
                            id: $(this).val(),
                            section: $(this).data("section"),
                        };
                    }).get();


                    // Check/Uncheck the check-all for this section
                    let totalItems = $(`.item-checkbox[data-section="${section}"]`).length;
                    let checkedItems = $(`.item-checkbox[data-section="${section}"]:checked`).length;

                    if (section == 'consumables') {
                        section = 0;
                    }

                    $(`.check-all[data-section="${section}"]`).prop("checked", totalItems === checkedItems);
                });

                // Approve button
                $(document).on("click", ".btn-approve", function() {
                    $(".btn-approve").prop("disabled", true);
                    sendCheckedIds("approve");
                });
                // Reject button
                $(document).on("click", ".btn-reject", function() {
                    $(".btn-reject").prop("disabled", true);
                    $('#loadingSpinner').show();
                    sendCheckedIds("reject");
                });

                function debounce(func, delay) {
                    let debounceTimer;
                    return function() {
                        const context = this;
                        const args = arguments;
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(() => func.apply(context, args), delay);
                    };
                }

                // Search functionality with debounce
                $(document).on('keyup', '#boqItemSearch', debounce(function() {
                    var value = $(this).val().toLowerCase();
                    var rows = $('tbody tr');

                    rows.each(function() {
                        if ($(this).text().toLowerCase().indexOf(value) > -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }, 300)); // 300ms debounce delay

                $(document).on('click', '.nav-link-child', function() {
                    let selectedSection = $(this).attr('id').replace('tab-', '');
                    publicSection = selectedSection

                    $('.check-all').prop('checked', false).trigger('change');
                    $('.item-checkbox').prop('checked', false).trigger('change');

                    initializeCheck(selectedSection);
                });

                $(document).on('click', '.btnModal', function() {
                    // Instantly show the custom modal
                    $('#customModal').css('display', 'block');
                });

                $(document).on('click', '.custom-modal-close', function() {
                    // Instantly hide the custom modal
                    $('#customModal').css('display', 'none');
                    $('#createPurchaseRequestModal').css('display', 'none');
                });

                $(window).on('click', function(event) {
                    // Close the modal when clicking outside the content
                    if ($(event.target).is('#customModal')) {
                        $('#customModal').css('display', 'none');
                        $('#createPurchaseRequestModal').css('display', 'none');
                    }
                });

                $(document).on('click', '.btnCreatePR', function() {
                    $('#createPurchaseRequestModal').css('display', 'block');
                })

                $(document).ready(function() {
                    // On first load, check all sections
                    let allSections = [...new Set(boqList.map(item => item.section))];

                    allSections.forEach(function(section) {
                        checkSectionBadge(section);
                    });

                    function getSectionFromStorage() {
                        const sectionLength = 0
                        const localActiveSection = localStorage.getItem('activeSection');

                        if (sectionLength === 0) {
                            return 0
                        }

                        if (localActiveSection) {
                            return localActiveSection > sectionLength ? 0 : localActiveSection
                        }

                        return localStorage.getItem('activeSection') || null;
                    }

                    function saveSectionToStorage(section) {
                        if (!section) return;
                        localStorage.setItem('activeSection', section);
                    }

                    function showTabContent(section) {
                        $('#tab-contents .tab-content-item').removeClass('active').hide();
                        $('#content-' + section).addClass('active').show();
                    }

                    function activateTab(section) {
                        $('[data-section]').removeClass('active');
                        $('[data-section="' + section + '"]').addClass('active');
                        showTabContent(section);
                    }

                    let activeSection = getSectionFromStorage();
                    if (activeSection) {
                        activateTab(activeSection);
                        initializeCheck(activeSection);
                    } else {
                        const firstSection = $('[data-section]').first().attr('data-section');
                        activateTab(firstSection);
                        saveSectionToStorage(firstSection);
                        activeSection = firstSection;
                    }

                    $('[data-section]').on('click', function() {
                        activeSection = $(this).attr('data-section');
                        saveSectionToStorage(activeSection);
                        activateTab(activeSection);
                    });

                    $(document).on('click', '#loadMore', function() {
                        activeSection = getSectionFromStorage();
                        saveSectionToStorage(activeSection);
                        activateTab(activeSection);
                    });

                    Livewire.hook('message.processed', (message, component) => {
                        activeSection = getSectionFromStorage();

                        let filteredAllBoqList = allBoqList.filter(function(item) {
                            return `${item.section}` === activeSection;
                        });
                        let filteredBoqList = boqList.filter(function(item) {
                            return `${item.section}` === activeSection;
                        });

                        let totalItems = filteredAllBoqList.length;
                        let totalBoqItems = filteredBoqList.length;

                        $('#boqTotal').text(totalItems);

                        if (activeSection) {
                            activateTab(activeSection);
                        }

                        let allSections = [...new Set(boqList.map(item => item.section))];

                        allSections.forEach(function(section) {
                            checkSectionBadge(section);
                        });
                    });
                });

                function checkSectionBadge(selectedSection) {
                    let currentUserId = {{ auth()->user()->id }};

                    let filteredBoqList = boqList.filter(function(item) {
                        return item.section == selectedSection;
                    });

                    let allRejected = filteredBoqList.every(function(item) {
                        return item.rejected_by !== null;
                    });

                    let userAlreadyApproved = filteredBoqList.every(function(item) {
                        return item.approved_by == currentUserId ||
                            item.approved_by_2 == currentUserId ||
                            item.approved_by_3 == currentUserId;
                    });

                    let allApproved = filteredBoqList.every(function(item) {
                        return item.approved_by_2 !== null && item.approved_by_3 !== null;
                    });

                    if (boqSection == selectedSection && !allRejected && !userAlreadyApproved && !allApproved) {
                        $(`.warningIcon[data-section="${selectedSection}"]`).show();
                    }
                }
            }

            document.addEventListener("DOMContentLoaded", function() {
                onContentLoad();

                Livewire.hook('message.processed', (message, component) => {
                    onContentLoad();
                });
            });
        </script>
    @endpush
</div>
