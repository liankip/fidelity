@php
    use App\Permissions\Permission;
@endphp

<div class="mt-2">
    @if ($project->status === 'Finished')
        @include('masterdata.boqs.read-only-boq')
    @else
        <div>
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left d-lg-flex justify-content-between">
                        <div class="">
                            <h2 class="text-black">Master Data
                                <a class="text-decoration-none text-black" href="{{ route('projects.index') }}">BOQ</a>
                            </h2>
                            <h4 class="text-secondary"><strong>{{ $project->name }} - {{ $task->task_number }}</strong>
                            </h4>
                        </div>
                        <div class="mt-1 d-none d-lg-flex gap-2 ">
                            @if (auth()->user()->hasTopLevelAccess())
                                <div class="position-relative">
                                    <x-common.button-link :color="'primary'" :route="route('boq.access.index', $project->id)" data-bs-toggle="tooltip"
                                        data-bs-title="User Akses">
                                        <i class="fas fa-user-friends"></i>

                                    </x-common.button-link>
                                    @if ($requestAccessCount > 0)
                                        <span class="badge bg-danger position-absolute d-block rounded-circle"
                                            style="font-size: 10px;right: -3px;top:-8px">
                                            {{ $requestAccessCount }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                            <div class="position-relative">
                                <div class="dropdown">
                                    <a href="{{ route('boq.project.index', ['projectId' => $project->id, 'taskId' => $task->id]) }}"
                                       class="btn btn-primary btn-outline"
                                       type="button">
                                        BOQs
                                    </a>
                                    {{--                                    <ul class="dropdown-menu" aria-labelledby="boqDropdown">--}}
                                    {{--                                        <li>--}}
                                    {{--                                            <a class="dropdown-item"--}}
                                    {{--                                               href="{{ route('boq.project.index', ['projectId' => $project->id, 'wbs' => $wbs->id]) }}">BOQs Project</a>--}}
                                    {{--                                        </li>--}}
                                    {{--                                        <li>--}}
                                    {{--                                            <a class="dropdown-item"--}}
                                    {{--                                               href="{{ route('boq.review.index', $project->id) }}">BOQs Retail</a>--}}
                                    {{--                                        </li>--}}
                                    {{--                                    </ul>--}}
                                </div>
                                @if ($boqRequestsCount > 0 && auth()->user()->hasTopManagerAccess())
                                    <span class="badge bg-danger position-absolute d-block rounded-circle"
                                        style="font-size: 10px;right: -3px;top:-8px">
                                        {{ $boqRequestsCount }}
                                    </span>
                                @endif
                            </div>
                            @if (auth()->user()->hasTopManagerAccess())
                                <div>
                                    <x-common.button-link :color="'primary'" class="btn-outline" :route="route('boq.setting.index', $project->id)"
                                        data-bs-toggle="tooltip" data-bs-title="Pengaturan">
                                        <i class="fas fa-wrench"></i>
                                    </x-common.button-link>
                                </div>
                            @endif
                        </div>

                    </div>

                    {{-- Mobile Menu --}}
                    <div class="dropdown d-flex justify-content-end d-block d-lg-none">
                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Menu
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            @if (auth()->user()->hasTopLevelAccess())
                                <li>
                                    <a class="dropdown-item" href="{{ route('boq.access.index', $project->id) }}">
                                        Daftar User Akses
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('boq.review.index', $project->id) }}">
                                    Pengajuan BOQ
                                </a>
                            </li>
                            {{-- @if ($adendum || auth()->user()->hasTopLevelAccess())
                                <li><a class="dropdown-item" href="{{ route('boq.create', $project->id) }}"
                                        target='_BLANK'>Add
                                        Item</a></li>
                                @if (!auth()->user()->hasTopLevelAccess() && !$project->hasBoqAccess())
                                    <button class="dropdown-item" wire:click="request_access"
                                        wire:loading.attr="disabled">
                                        Request Access
                                    </button>
                                @endif
                            @endif --}}
                            <li>
                                <button class="dropdown-item" wire:click="export_boq">Export BOQ</button>
                            </li>
                        </ul>
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
                        {{-- @if ($project->boq_verification && auth()->user()->hasTopLevelAccess())
                            <x-common.button-link :color="'primary'" :route="route('boq.create', $project->id)" target='_BLANK'>
                                <i class="fas fa-plus"></i> Add Item
                            </x-common.button-link>
                        @endif --}}
                        @if ($adendum)
                            @can(Permission::CREATE_ADENDUM)
                                {{-- @if ($project->boq_verification || auth()->user()->hasTopLevelAccess())
                                    <x-common.button-link :color="'primary'" :route="route('boq.create', $project->id)" target='_BLANK'>
                                        <i class="fas fa-plus"></i> Add Item
                                    </x-common.button-link>
                                @else
                                    @if ($project->hasBoqAccess())
                                        <x-common.button-link color="primary" :route="route('boq.create', $project->id)" target='_BLANK'>
                                            <i class="fas fa-plus"></i> Add Item
                                        </x-common.button-link>
                                    @endif
                                @endif --}}

                                @if (!auth()->user()->hasTopLevelAccess() && !$project->hasBoqAccess())
                                    <button class="btn btn-primary" wire:click="request_access"
                                        wire:loading.attr="disabled">
                                        Request Access
                                    </button>
                                @endif
                            @endcan
                        @endif
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
                        <option value="purchased">Purchased</option>
                        <option value="unpurchased">Unpurchased</option>
                    </x-common.select-normal>
                </div>
                <div class="pull-right" x-data="{ loading: @entangle('loading') }">
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
                                                    <button class="btn btn-danger" wire:click="reject_from_manager">Reject
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
                                @if (App\Models\BOQ::where('project_id', $project->id)->count() > 0)
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            {{-- <a href="{{ route('boq.history', $project->id) }}"
                                                class="fs-4 d-none d-lg-flex">
                                                <i class="fas fa-clock-rotate-left"></i>
                                            </a> --}}
                                            @if ($project->boq_verification == 0)
                                                {{-- @can(Permission::EDIT_ITEM)
                                                    <div class="me-1">
                                                        @if (auth()->user()->hasTopLevelAccess())
                                                            <button class="btn btn-success" wire:click="show_adendum(1)"
                                                                wire:loading.attr="disabled">
                                                                Edit
                                                            </button>
                                                        @else
                                                            <button
                                                                class="btn {{ $project->hasBoqAccess() ? 'btn-success' : 'btn-danger' }}"
                                                                wire:click="show_adendum(1)"
                                                                wire:loading.attr="disabled">Edit
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endcan --}}
                                            @else
                                                @if (auth()->user()->hasTopLevelAccess())
                                                    @if ($max_version == (int) $boqs->first()->revision)
                                                        <div>
                                                            <button class="btn btn-primary" wire:click="approve_all"
                                                                wire:loading.attr="disabled">Approve
                                                            </button>
                                                            <button class="btn btn-danger" data-bs-toggle="modal"
                                                                data-bs-target="#rejectModal">Reject
                                                            </button>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    {{-- <button class="btn btn-success" wire:click="show_adendum(1)">Buat BOQ</button> --}}
                                @endif
                            @endif
                        </div>
                    @endif
                </div>

            </div>

            <div class="rounded bg-white p-3">
                <div class="pull-right">
                    <p class="fw-semibold">Total Items : <span class="text-success">{{ $boqList->count() }}</span>
                    </p>
                </div>
                <input wire:model="search" type="text" placeholder="Search by item name"
                    class="form-control border border-black">

                <div class="table-responsive-container">
                    <table id="boqTable" class="table table-bordered mt-3">
                        <thead>
                            <tr class="table-secondary">
                                @if (($adendum && auth()->user()->hasTopLevelAccess()) || (!$adendum && auth()->user()->hasTopLevelAccess()))
                                    <th class="text-center align-middle" width="5%">#</th>
                                    <th class="text-center align-middle" width="5%">No</th>
                                    <th class="text-center align-middle" width="15%">Item Name</th>
                                    <th class="text-center align-middle" width="10%">Quantity</th>
                                    <th class="text-center align-middle" width="5%">Unit</th>
                                    <th class="text-center align-middle" width="10%">Price Estimation*</th>
                                    <th class="text-center align-middle" width="10%">Shipping Cost Estimation*</th>
                                    <th class="text-center align-middle" width="10%">Total Estimation**</th>
                                    <th class="text-center align-middle" width="10%">Note</th>
                                    <th class="text-center align-middle" width="10%">Status</th>
                                    <th class="text-center align-middle" width="10%">
                                        Action <br>
                                        @if ($project->boq_verification == 1 && $needToApprove)
                                            <input type="checkbox" wire:model="select_all" class="form-check-input"
                                                id="select-all" wire:click="checkAll">
                                            <label class="form-check-label text-muted" for="select-all"><small>Select
                                                    All</small></label>
                                        @endif
                                    </th>
                                @elseif($adendum)
                                    <th class="text-center align-middle" width="5%">No</th>
                                    <th class="text-center align-middle" width="15%">Item Name</th>
                                    <th class="text-center align-middle" width="10%">Quantity</th>
                                    <th class="text-center align-middle" width="5%">Unit</th>
                                    <th class="text-center align-middle" width="10%">Price Estimation*</th>
                                    <th class="text-center align-middle" width="10%">Shipping Cost Estimation*</th>
                                    <th class="text-center align-middle" width="10%">Total Estimation**</th>
                                    <th class="text-center align-middle" width="10%">Note</th>
                                    <th class="text-center align-middle" width="15%">Status</th>
                                    <th class="text-center align-middle" width="10%">Action</th>
                                @else
                                    <th class="text-center align-middle" width="5%">No</th>
                                    <th class="text-center align-middle" width="15%">Item Name</th>
                                    <th class="text-center align-middle" width="10%">Quantity</th>
                                    <th class="text-center align-middle" width="5%">Unit</th>
                                    <th class="text-center align-middle" width="10%">Price Estimation*</th>
                                    <th class="text-center align-middle" width="10%">Shipping Cost Estimation*</th>
                                    <th class="text-center align-middle" width="10%">Total Estimation**</th>
                                    <th class="text-center align-middle" width="10%">Note</th>
                                    <th class="text-center align-middle" width="15%">Status</th>
                                    <th class="text-center align-middle" width="10%">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @forelse ($boqList as $wbs => $boq)
                            @php
                                $isBoqExist = $boqsArray->where('id', $boq->id)->first();
                                $canDelete = $isBoqExist && $isBoqExist['canDelete'];
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    @php
                                        $po_status = $isBoqExist ? $isBoqExist['po_status'] : null;
                                    @endphp
                                    @if ($po_status && auth()->user()->hasAnyRole('it|top-manager|manager|purchasing'))
                                        <div>
                                            <div class="mb-3">
                                                <strong class="text-success">{{ $boq->item->name }}</strong>
                                            </div>
                                            <div class="border alert alert-light">
                                                <div class="text-success">
                                                    PO Approved:
                                                </div>
                                                <div>
                                                    {{ (int) $po_status['qty_total'] }}
                                                    / {{ (int) $boq->qty }}
                                                </div>
                                                <p class="mt-2">
                                                    <a class="text-sm text-black" data-bs-toggle="collapse"
                                                       href="#po-list-{{ $boq->id }}" role="button"
                                                       aria-expanded="false"
                                                       aria-controls="po-list-{{ $boq->id }}">
                                                        See PO List <i class="fas fa-caret-down"></i>
                                                    </a>
                                                </p>
                                                <div class="collapse" id="po-list-{{ $boq->id }}">
                                                    <ul>
                                                        @foreach ($po_status['list'] as $po)
                                                            <li>
                                                                <a class="text-sm text-black"
                                                                   href="{{ route('po-detail', $po['po_id']) }}"
                                                                   target="_blank">{{ $po['po_no'] }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <strong class="text-success">{{ $boq->item->name }}</strong>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($dataSpreadsheet->contains('project_id', $project->id))
                                        <div class="w-100 badge text-bg-primary mb-1">
                                            <small class="flex">
                                                Sebelumnya:
                                            </small>
                                            <x-boq.history-quantity :data="$dataSpreadsheet"
                                                                    :itemid="$boq->item->id"
                                                                    :boqdata="$boq" :project_id="$project->id"/>
                                        </div>
                                    @endif

                                    @if (
                                        ($boq->rejected_by == null || $boq->rejected_by == 0) &&
                                            ($boq->approved_by == null ||
                                                $boq->approved_by == 0 ||
                                                ($boq->approved_by_2 == null || $boq->approved_by_2 == 0)))
                                        <x-boq.additional-quantity :data="$dataSpreadsheet" :itemid="$boq->item->id"
                                                                   :boqdata="$boq" :boq="$boq"
                                                                   :boq-verification="$project->boq_verification"
                                                                   :is-multiple-approval="$setting->multiple_approval"
                                                                   :boq-array="$isBoqExist"
                                                                   :project_id="$project->id"/>
                                    @endif

                                    <div class="w-100 badge text-bg-success">
                                        <div>
                                            <small>
                                                Sekarang:
                                            </small>
                                        </div>
                                        <div>
                                            <small>
                                                <x-boq.quantity :boq="$boq"
                                                                :boq-verification="$project->boq_verification"
                                                                :is-multiple-approval="$setting->multiple_approval"
                                                                :boq-array="$isBoqExist"/>
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
                                <td class="text-start">
                                    <x-boq.status-approval :boq="$boq" :setting="$setting"
                                                           :max_version="$max_version"/>
                                </td>
                                @if ($adendum)
                                    <td class="text-center">

                                        @can(Permission::CREATE_ADENDUM)
                                            {{-- @if ($project->hasBoqAccess())
                                                @if ($show_version == $max_version)
                                                    @if ($project->boq_verification)
                                                        @if (auth()->user()->boq_verificator == 1)
                                                            <a href="{{ route('boq.edit', [$boq->id, $boq->project_id]) }}"
                                                                class="btn btn-sm btn-outline-success">Edit</a>
                                                            @if ($canDelete)
                                                                <a href="{{ route('boq.destroy', [$boq->id, $boq->project_id]) }}"
                                                                    class="btn btn-sm btn-outline-danger">Delete</a>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <a href="{{ route('boq.edit', [$boq->id, $boq->project_id]) }}"
                                                            class="btn btn-sm btn-outline-success">Edit</a>
                                                        @if ($canDelete)
                                                            <a href="{{ route('boq.destroy', [$boq->id, $boq->project_id]) }}"
                                                                class="btn btn-sm btn-outline-danger">Delete</a>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif --}}
                                        @endcan
                                    </td>
                                @elseif(auth()->user()->can('approve-boq'))
                                    <x-boq.checklist-approval :setting="$setting" :boq="$boq"
                                                              :max_version="$max_version"
                                                              :boq_verification="$project->boq_verification"/>
                                @elseif($adendum || auth()->user()->hasTopLevelAccess())
                                    <td class="text-center">
                                        {{-- <a href="{{ route('boq.edit', $boq->id) }}"
                                            class="btn btn-sm btn-outline-success">Edit</a> --}}
                                    </td>
                                @else
                                    <td class="text-center"></td>
                                @endif
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
                        @if (($adendum && auth()->user()->hasTopLevelAccess()) || (!$adendum && auth()->user()->hasTopLevelAccess()))
                            <tr class="table-success">
                                <td colspan="7" class="text-end"><strong>Grand Total Estimation</strong></td>
                                <td class="text-end"><strong>{{ rupiah_format($total) }}</strong></td>
                                <td colspan="4"></td>
                            </tr>
                        @elseif($adendum)
                            <tr class="table-success">
                                <td colspan="6" class="text-end"><strong>Grand Total Estimation</strong></td>
                                <td class="text-end"><strong>{{ rupiah_format($total) }}</strong></td>
                                <td colspan="4"></td>
                            </tr>
                        @else
                            <tr class="table-success">
                                <td colspan="6" class="text-end"><strong>Grand Total Estimation</strong></td>
                                <td class="text-end"><strong>{{ rupiah_format($total) }}</strong></td>
                                <td colspan="3"></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Confirmation Modals --}}
            <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="rejectModalLabel">Reject BOQ</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure want to reject this BOQ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" wire:click="reject_all"
                                wire:loading.attr="disabled">
                                Reject
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @if ($showModal)
                @include('components.modaladendum')
            @endif
        </div>
    @endif
</div>
