@php
    use App\Permissions\Permission;
@endphp

<div>
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <div class="d-lg-flex justify-content-between">
                        <div>
                            <h2 class="primary-color-sne">Purchase Orders</h2>
                        </div>
                        @php
                            $today_po = App\Models\PurchaseOrder::where('status', 'Wait For Approval')->count();
                            $po_setting = App\Models\Setting::pluck('po_limit')->first();

                            if ($po_setting == 0) {
                                $status = 'primary';
                            } else {
                                if ($today_po >= $po_setting) {
                                    $status = 'danger';
                                } else {
                                    $status = 'success';
                                }
                            }
                        @endphp

                        @if ($po_setting != 0)
                            <div class="alert rounded-4 alert-{{ $status }}">
                                <div>Batas Status Wait For Approval PO: <strong>{{ $po_setting }}</strong></div>
                                <div>Sisa Pengajuan PO: <strong>{{ $po_setting - $today_po }}</strong></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <x-common.notification-alert />

        <div class="mt-5 d-flex justify-content-between">
            <div>
                <button type="button" wire:click="modalExportPurchaseOrderPdf()" class="btn btn-success btn-sm">
                    Export Barang Masuk
                </button>
                <button type="button" wire:click="showmodalexportpo()" class="btn btn-success btn-sm"
                        data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Export PO
                </button>
                @hasanyrole('it|top-manager|manager|purchasing')
                    <a class="btn btn-success btn-sm" href="{{ url('purchase_requests') }}"><i class="fa-solid fa-plus"></i>New PO</a>
                @endhasanyrole
            </div>
            <div class="d-flex gap-2">
                @can(Permission::PRINT_LATEST_PO)
                    <form action="{{ route('printpolatest') }}" method="post" target="__blank">
                        @csrf
                        <button class="btn btn-info btn-sm" type="submit"><i class="fa-solid fa-download"></i> Print Last 10 PO</button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="card mt-2 primary-box-shadow">
                <div class="card-body">
                    <div class="input-group mb-3 mt-3">
                        <input type="text" class="form-control" wire:model="search" name="search" placeholder="Search"
                            value="" aria-label="Recipient's username" aria-describedby="button-addon2">
                    </div>

                    <ul class="nav nav-tabs mb-3">
                        @php
                            $filters = ['All', 'New', 'Draft', 'Need to Pay', 'Paid', 'Approved', 'Arrived', 'Cancel'];
                        @endphp

                        @foreach ($filters as $filter)
                            <li class="nav-item">
                                <button class="nav-link @if ($currentFilter === $filter) tabs-link-active @endif"
                                    wire:click="filterHandler('{{ $filter }}')">
                                    {{ $filter }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="overflow-scroll primary-box-shadow">
                        <table class="table table-hover ">
                            <thead class="thead-light text-center">
                                <tr class="">
                                    <th style="width: 3%" class="align-middle border-top-left">No</th>
                                    <th style="width: 17%" class="align-middle">PO No</th>
                                    <th style="width: 15%" class="align-middle">PR No</th>
                                    <th style="width: 5%" class="align-middle">ToP</th>
                                    <th style="width: 13%" class="align-middle">Project Name</th>
                                    <th style="width: 15%" class="align-middle">Vendor Name</th>
                                    <th style="width: 10%" class="align-middle">Date</th>
                                    <th style="width: 10%" class="align-middle">Status</th>
                                    <th style="width: 9%" class="align-middle">Status Barang</th>
                                    <th style="width: 12%" class="align-middle">Notes</th>
                                    <th style="width: 5%" class="align-middle">PO Type</th>
                                    <th style="width: 5%" class="align-middle border-top-right">Action</th>
                                </tr>
                            </thead>
                            @forelse ($purchase_orders as $key => $purchaseorder)
                                @php
                                    $isDueDate = \App\Helpers\TermOfPayment\GenerateEstimate::isNotifDue(
                                        $purchaseorder->top_date,
                                        $purchaseorder->term_of_payment,
                                    );

                                    if ($purchaseorder->isPaid()) {
                                        $isDueDate = false;
                                    }

                                @endphp
                                <tr style="font-size: 13px;" class="{{ $isDueDate ? 'table-warning' : '' }}">
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>
                                        <b>
                                            @if ($purchaseorder->po_no)
                                                <a href="{{ route('po-detail', $purchaseorder->id) }}">{{ $purchaseorder->po_no }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </b>
                                    </td>
                                    <td>
                                        @if (count($purchaseorder->pivotPR) > 0)
                                            <ul>
                                                @foreach ($purchaseorder->pivotPR as $pr)
                                                    <li>{{ $pr->pr_no }}
                                                        @if ($pr->pr_type == 'Barang')
                                                            <span class="badge badge-primary">{{ $pr->pr_type }}</span>
                                                        @elseif($pr->pr_type == 'Jasa')
                                                            <span class="badge badge-success" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Periksa faktur pajak">
                                                                {{ $pr->pr_type }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-warning">{{ $pr->pr_type }}</span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        {{-- @else
                                            {{ $purchaseorder->pr_no }}
                                            @if ($purchaseorder->pr->pr_type == 'Barang')
                                                <span class="badge badge-primary">{{ $purchaseorder->pr->pr_type }}</span>
                                            @elseif($purchaseorder->pr->pr_type == 'Jasa')
                                                <span class="badge badge-success" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Periksa faktur pajak">
                                                    {{ $purchaseorder->pr->pr_type }}
                                                </span>
                                            @else
                                                <span class="badge badge-warning">{{ $purchaseorder->pr->pr_type }}</span>
                                            @endif --}}
                                        @endif
                                        @if($purchaseorder->pr_no == null && $purchaseorder->podetail->every(fn($pod) => $pod->is_bulk == 1))
                                            <span class="badge badge-success">
                                                Bulk
                                            </span>
                                        @endif
                                        @if($purchaseorder->pr_no == null && $purchaseorder->podetail->every(fn($pod) => $pod->is_stock == 1))
                                            <span class="badge badge-success">
                                                Stock
                                            </span>
                                        @endif
                                        @if($condition = $purchaseorder->pr_no == null && $purchaseorder->podetail->every(fn($pod) => $pod->is_raw_materials == 1))
                                            <span class="badge badge-success">
                                                Raw Materials
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $purchaseorder->term_of_payment }}</td>
                                    <td>
                                        @if ($purchaseorder->project)
                                            {{ $purchaseorder->project->name }}
                                        @endif
                                    </td>
                                    <td>{{ $purchaseorder->supplier->name }}</td>
                                    <td>
                                        <div>
                                            <div>Request Date:</div>
                                            <div>{{ $purchaseorder->date_request }}</div>
                                        </div>
                                        @if ($purchaseorder->top_date)
                                            <div>
                                                <div>Due Date:</div>
                                                <div class="{{ $isDueDate ? 'text-danger' : '' }}">
                                                    <b>{{ date('Y-m-d', strtotime($purchaseorder->top_date)) }}</b>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($purchaseorder->isApproved())
                                            <span class="badge badge-success">
                                                {{ $purchaseorder->status }}
                                            </span>
                                        @elseif ($purchaseorder->isCancel() || $purchaseorder->isRejected() || $purchaseorder->isReverted())
                                            <span class="badge badge-danger">
                                                {{ $purchaseorder->status }}
                                            </span>
                                        @elseif ($purchaseorder->isDraft() || $purchaseorder->isDraftWithDS())
                                            <span class="badge badge-warning">
                                                {{ $purchaseorder->status }}
                                            </span>
                                        @elseif ($purchaseorder->isWaitApproval())
                                            <span class="badge badge-staged">
                                                {{ $purchaseorder->status }}
                                            </span>
                                        @else
                                            <span class="badge badge-success">
                                                {{ $purchaseorder->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($purchaseorder->status_barang)
                                            @if ($purchaseorder->status_barang == 'Arrived')
                                                <span class="badge badge-success">
                                                    {{ $purchaseorder->status_barang }}
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    {{ $purchaseorder->status_barang }}
                                                </span>
                                            @endif
                                        @else
                                            <span>Unknown </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($purchaseorder->status == 'Review')
                                            {{ $purchaseorder->remark_review }}
                                        @endif
                                        @if ($purchaseorder->status == 'Rejected')
                                            {{ $purchaseorder->remark_reject }}
                                        @endif
                                        @if ($purchaseorder->status == 'Reverted')
                                            {{ $purchaseorder->remark }}
                                        @endif
                                        {{-- @if ($purchaseorder->status != 'Review' || $purchaseorder->status != 'Review')
                                            @if($purchaseorder->notes != null)
                                                @php
                                                    $notes = json_decode($purchaseorder->notes);
                                                @endphp

                                                @if(isset($notes))
                                                    @foreach ($notes as $note)
                                                        <li>{{ $note->notes }}</li>
                                                    @endforeach
                                                @endif

                                            @endif
                                        @endif --}}
                                    </td>
                                    <td class="text-center">
                                        @if ($purchaseorder->po_type === 'Supply')
                                            <button class="btn btn-success">P</button>
                                        @elseif($purchaseorder->po_type == 'Non supply')
                                            <button class="btn btn-info">NP</button>
                                        @else
                                            <p>-</p>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Option
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @if (auth()->user()->hasGeneralAccess())
                                                    @if (
                                                        $purchaseorder->status != 'Cancel' &&
                                                            $purchaseorder->status != 'Rejected' &&
                                                            $purchaseorder->status != 'Paid' &&
                                                            $purchaseorder->status != 'Completed' &&
                                                            auth()->user()->can(Permission::CANCEL_PO))
                                                        <button class="dropdown-item"
                                                            wire:click="showconfirmcancel({{ $purchaseorder->id }},'{{ $purchaseorder->po_no }}')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                height="14" fill="currentColor" class="bi bi-x"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                            </svg>
                                                            Cancel
                                                        </button>
                                                    @endif
                                                    @hasanyrole('it|top-manager|manager|top-manager|finance|purchasing')
                                                        @if (!$purchaseorder->hasInvoice())
                                                            @if ($purchaseorder->isApproved() || $purchaseorder->isPartiallyPaid())
                                                                <a class="dropdown-item" target="__blank"
                                                                    href="{{ route('create_inv', $purchaseorder->id) }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                        height="14" fill="currentColor"
                                                                        class="bi bi-receipt" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z" />
                                                                        <path
                                                                            d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z" />
                                                                    </svg>
                                                                    Invoice
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endhasanyrole
                                                    @hasanyrole('it|top-manager|manager|top-manager|purchasing|finance')
                                                        @if ($purchaseorder->isProcessed())
                                                            @if ($purchaseorder->deliver_status == 1 || $purchaseorder->deliver_status == 2)
                                                                <form target="_blank"
                                                                    action="{{ route('printpo_ds', $purchaseorder->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('put')
                                                                    <button type="submit" class="dropdown-item">
                                                                        @if ( $purchaseorder->pr == null || $purchaseorder->pr->pr_type == 'Barang')
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="14" height="14"
                                                                                fill="currentColor" class="bi bi-printer"
                                                                                viewBox="0 0 16 16">
                                                                                <path
                                                                                    d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                                                                                <path
                                                                                    d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                                                                            </svg> PO
                                                                        @else
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="14" height="14"
                                                                                fill="currentColor" class="bi bi-printer"
                                                                                viewBox="0 0 16 16">
                                                                                <path
                                                                                    d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                                                                                <path
                                                                                    d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                                                                            </svg> SPK
                                                                        @endif
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            @if ($purchaseorder->deliver_status == 0)
                                                                <form target="_blank"
                                                                    action="{{ route('printpo', $purchaseorder->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('put')
                                                                    <button type="submit" class="dropdown-item">
                                                                        @if ($purchaseorder->pr?->pr_type == 'Barang' || $purchaseorder->pr == null)
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="14" height="14"
                                                                                fill="currentColor" class="bi bi-printer"
                                                                                viewBox="0 0 16 16">
                                                                                <path
                                                                                    d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                                                                                <path
                                                                                    d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                                                                            </svg> PO
                                                                        @else
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="14" height="14"
                                                                                fill="currentColor" class="bi bi-printer"
                                                                                viewBox="0 0 16 16">
                                                                                <path
                                                                                    d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                                                                                <path
                                                                                    d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                                                                            </svg> SPK
                                                                        @endif
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            @if ($purchaseorder->deliver_status == '0')
                                                                <form target="_blank"
                                                                    action="{{ route('printmemo', $purchaseorder->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('put')
                                                                    <button type="submit" class="dropdown-item">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                            height="14" fill="currentColor"
                                                                            class="bi bi-printer" viewBox="0 0 16 16">
                                                                            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                                                                            <path
                                                                                d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                                                                        </svg>
                                                                        Memo
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            @can(Permission::VIEW_SURAT_JALAN)
                                                                <form action="{{ route('create_do', $purchaseorder->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('get')
                                                                    <button type="submit" class="dropdown-item">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                            height="14" fill="currentColor"
                                                                            class="bi bi-truck-flatbed" viewBox="0 0 16 16">
                                                                            <path
                                                                                d="M11.5 4a.5.5 0 0 1 .5.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-4 0 1 1 0 0 1-1-1v-1h11V4.5a.5.5 0 0 1 .5-.5zM3 11a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm1.732 0h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4a2 2 0 0 1 1.732 1z" />
                                                                        </svg>
                                                                        Surat Jalan
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                            @if ($purchaseorder->canPrintReceipt())
                                                                <a class="dropdown-item" target="__blank"
                                                                    href="{{ route('print-receipt', $purchaseorder->id) }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                        height="14" fill="currentColor"
                                                                        class="bi bi-receipt" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z" />
                                                                        <path
                                                                            d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z" />
                                                                    </svg>
                                                                    Tanda Terima
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endhasanyrole
                                                    @if (
                                                        $purchaseorder->isNew() ||
                                                            $purchaseorder->isReview() ||
                                                            $purchaseorder->isDraft() ||
                                                            $purchaseorder->isDraftWithDS() ||
                                                            $purchaseorder->isNewWithDS() ||
                                                            $purchaseorder->isReverted())
                                                        @hasanyrole('it|top-manager|manager|purchasing')
                                                            @if ($purchaseorder->deliver_status == '1')
                                                                <button type="button" class="dropdown-item"
                                                                    wire:click="showmodalpengirman({{ $purchaseorder->id }})">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                        height="14" fill="currentColor"
                                                                        class="bi bi-truck" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                                                    </svg>
                                                                    Jasa Pengiriman
                                                                </button>
                                                                @if ($purchaseorder->isNewWithDS() || $purchaseorder->isDraftWithDS() || $purchaseorder->isReverted())
                                                                    @if ($po_setting == 0)
                                                                        @can(Permission::AJUKAN_PO)
                                                                            <form
                                                                                action="{{ route('ajukan', $purchaseorder->id) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('put')
                                                                                <button type="submit" class="dropdown-item">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="14" height="14"
                                                                                        fill="currentColor"
                                                                                        class="bi bi-box-arrow-in-left"
                                                                                        viewBox="0 0 16 16">
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z" />
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                                                                                    </svg>
                                                                                    @if ($purchaseorder->pr?->pr_type == 'Barang' || $purchaseorder->pr == null)
                                                                                        Ajukan PO
                                                                                    @else
                                                                                        Ajukan SPK
                                                                                    @endif
                                                                                </button>
                                                                            </form>
                                                                        @endcan
                                                                    @else
                                                                        @if ($today_po >= $po_setting)
                                                                            @can(Permission::AJUKAN_PO)
                                                                                <button wire:click="show_modal_po_limit"
                                                                                    class="dropdown-item text-secondary">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="14" height="14"
                                                                                        fill="currentColor"
                                                                                        class="bi bi-box-arrow-in-left"
                                                                                        viewBox="0 0 16 16">
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z" />
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                                                                                    </svg>
                                                                                    @if ($purchaseorder->pr?->pr_type == 'Barang' || $purchaseorder->pr == null)
                                                                                        Ajukan PO
                                                                                    @else
                                                                                        Ajukan SPK
                                                                                    @endif
                                                                                </button>
                                                                            @endcan
                                                                        @else
                                                                            @can(Permission::AJUKAN_PO)
                                                                                <form
                                                                                    action="{{ route('ajukan', $purchaseorder->id) }}"
                                                                                    method="post">
                                                                                    @csrf
                                                                                    @method('put')
                                                                                    <button type="submit" class="dropdown-item">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                                            width="14" height="14"
                                                                                            fill="currentColor"
                                                                                            class="bi bi-box-arrow-in-left"
                                                                                            viewBox="0 0 16 16">
                                                                                            <path fill-rule="evenodd"
                                                                                                d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z" />
                                                                                            <path fill-rule="evenodd"
                                                                                                d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                                                                                        </svg>
                                                                                        @if ($purchaseorder->pr?->pr_type == 'Barang' || $purchaseorder->pr == null)
                                                                                            Ajukan PO
                                                                                        @else
                                                                                            Ajukan SPK
                                                                                        @endif
                                                                                    </button>
                                                                                </form>
                                                                            @endcan
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                                <div>
                                                                    @can(Permission::EDIT_PO)
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('purchase-orders.edit', ['id' => $purchaseorder->id]) }}">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                                height="14" fill="currentColor"
                                                                                class="bi bi-pencil" viewBox="0 0 16 16">
                                                                                <path
                                                                                    d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                                                            </svg>
                                                                            Edit</a>
                                                                    @endcan
                                                                </div>
                                                            @elseif ($purchaseorder->deliver_status == '2')
                                                                <button type="button"
                                                                    wire:click="showmodalpengirman({{ $purchaseorder->id }})"
                                                                    class="dropdown-item">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                        height="14" fill="currentColor" class="bi bi-cash"
                                                                        viewBox="0 0 16 16">
                                                                        <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                                                                        <path
                                                                            d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2H3z" />
                                                                    </svg>
                                                                    Ongkos Kirim
                                                                </button>
                                                                @if ($purchaseorder->isNewWithDS() || $purchaseorder->isDraftWithDS() || $purchaseorder->isReverted())
                                                                    @if ($po_setting == 0)
                                                                        @can(Permission::AJUKAN_PO)
                                                                            <form
                                                                                action="{{ route('ajukan', $purchaseorder->id) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('put')
                                                                                <button type="submit" class="dropdown-item">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="14" height="14"
                                                                                        fill="currentColor"
                                                                                        class="bi bi-box-arrow-in-left"
                                                                                        viewBox="0 0 16 16">
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z" />
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                                                                                    </svg>
                                                                                    @if ($purchaseorder->pr?->pr_type == 'Barang' || $purchaseorder->pr == null)
                                                                                        Ajukan PO
                                                                                    @else
                                                                                        Ajukan SPK
                                                                                    @endif
                                                                                </button>
                                                                            </form>
                                                                        @endcan
                                                                    @else
                                                                        @if ($today_po >= $po_setting)
                                                                            @can(Permission::AJUKAN_PO)
                                                                                <button wire:click="show_modal_po_limit"
                                                                                    class="dropdown-item text-secondary">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="14" height="14"
                                                                                        fill="currentColor"
                                                                                        class="bi bi-box-arrow-in-left"
                                                                                        viewBox="0 0 16 16">
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z" />
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                                                                                    </svg>
                                                                                    @if ($purchaseorder->pr?->pr_type == 'Barang' || $purchaseorder->pr == null)
                                                                                        Ajukan PO
                                                                                    @else
                                                                                        Ajukan SPK
                                                                                    @endif
                                                                                </button>
                                                                            @endcan
                                                                        @else
                                                                            @can(Permission::AJUKAN_PO)
                                                                                <form
                                                                                    action="{{ route('ajukan', $purchaseorder->id) }}"
                                                                                    method="post">
                                                                                    @csrf
                                                                                    @method('put')
                                                                                    <button type="submit" class="dropdown-item">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                                            width="14" height="14"
                                                                                            fill="currentColor"
                                                                                            class="bi bi-box-arrow-in-left"
                                                                                            viewBox="0 0 16 16">
                                                                                            <path fill-rule="evenodd"
                                                                                                d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z" />
                                                                                            <path fill-rule="evenodd"
                                                                                                d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                                                                                        </svg>
                                                                                        @if ($purchaseorder->pr?->pr_type == 'Barang' || $purchaseorder->pr == null)
                                                                                            Ajukan PO
                                                                                        @else
                                                                                            Ajukan SPK
                                                                                        @endif
                                                                                    </button>
                                                                                </form>
                                                                            @endcan
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                                <div>
                                                                    @can(Permission::EDIT_PO)
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('purchase-orders.edit', ['id' => $purchaseorder->id]) }}">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                                height="14" fill="currentColor"
                                                                                class="bi bi-pencil" viewBox="0 0 16 16">
                                                                                <path
                                                                                    d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                                                            </svg>
                                                                            Edit
                                                                        </a>
                                                                    @endcan
                                                                </div>
                                                            @endif
                                                            @if ($purchaseorder->deliver_status == '0')
                                                                @if ($po_setting == 0)
                                                                    @can(Permission::AJUKAN_PO)
                                                                        <form action="{{ route('ajukan', $purchaseorder->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            @method('put')
                                                                            <button type="submit" class="dropdown-item">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="14" height="14"
                                                                                    fill="currentColor"
                                                                                    class="bi bi-box-arrow-in-left"
                                                                                    viewBox="0 0 16 16">
                                                                                    <path fill-rule="evenodd"
                                                                                        d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z" />
                                                                                    <path fill-rule="evenodd"
                                                                                        d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                                                                                </svg>
                                                                                @if ($purchaseorder->pr?->pr_type == 'Barang' || $purchaseorder->pr == null)
                                                                                    Ajukan PO
                                                                                @else
                                                                                    Ajukan SPK
                                                                                @endif
                                                                            </button>
                                                                        </form>
                                                                    @endcan
                                                                @else
                                                                    @if ($today_po >= $po_setting)
                                                                        @can(Permission::AJUKAN_PO)
                                                                            <button wire:click="show_modal_po_limit"
                                                                                class="dropdown-item text-secondary">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="14" height="14"
                                                                                    fill="currentColor"
                                                                                    class="bi bi-box-arrow-in-left"
                                                                                    viewBox="0 0 16 16">
                                                                                    <path fill-rule="evenodd"
                                                                                        d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z" />
                                                                                    <path fill-rule="evenodd"
                                                                                        d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                                                                                </svg>
                                                                                @if ($purchaseorder->pr?->pr_type == 'Barang' || $purchaseorder->pr == null)
                                                                                    Ajukan PO
                                                                                @else
                                                                                    Ajukan SPK
                                                                                @endif
                                                                            </button>
                                                                        @endcan
                                                                    @else
                                                                        @can(Permission::AJUKAN_PO)
                                                                            <form
                                                                                action="{{ route('ajukan', $purchaseorder->id) }}"
                                                                                method="post">
                                                                                @csrf
                                                                                @method('put')
                                                                                <button type="submit" class="dropdown-item">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="14" height="14"
                                                                                        fill="currentColor"
                                                                                        class="bi bi-box-arrow-in-left"
                                                                                        viewBox="0 0 16 16">
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z" />
                                                                                        <path fill-rule="evenodd"
                                                                                            d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                                                                                    </svg>
                                                                                    @if ($purchaseorder->pr?->pr_type == 'Barang' || $purchaseorder->pr == null)
                                                                                        Ajukan PO
                                                                                    @else
                                                                                        Ajukan SPK
                                                                                    @endif
                                                                                </button>
                                                                            </form>
                                                                        @endcan
                                                                    @endif
                                                                @endif
                                                                <div>
                                                                    @can(Permission::EDIT_PO)
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('purchase-orders.edit', ['id' => $purchaseorder->id]) }}">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                                height="14" fill="currentColor"
                                                                                class="bi bi-pencil" viewBox="0 0 16 16">
                                                                                <path
                                                                                    d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                                                            </svg>
                                                                            Edit
                                                                        </a>
                                                                    @endcan
                                                                </div>
                                                            @endif
                                                        @endhasanyrole
                                                    @endif
                                                @endif
                                                <a class="dropdown-item"
                                                    href="{{ url('po_details', $purchaseorder->id) }}" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                        fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                                        <path
                                                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                                    </svg>
                                                    Detail
                                                </a>
                                                @hasanyrole('it|top-manager|manager|top-manager|finance|purchasing')
                                                    @if (!is_null($purchaseorder->completeDocument))
                                                        @php
                                                            $completeDocument = $purchaseorder->completeDocument;
                                                        @endphp

                                                        <a class="dropdown-item"
                                                            href="{{ asset('storage/' . $completeDocument->file_path) }}"
                                                            target="_blank">
                                                            <i class="fas fa-file-pdf"></i>
                                                            Complete Document
                                                        </a>
                                                    @endif
                                                @endhasanyrole
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <span class="badge badge-pill badge-secondary">Data tidak tersedia</span>
                                    </td>
                                </tr>
                            @endforelse
                        </table>
                    </div>

                    @if ($show_modal_po_limit)
                        @include('components.po.request-po-limit')
                    @endif

                    <div class="mt-4 d-flex justify-content-end">
                        {{ $purchase_orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
