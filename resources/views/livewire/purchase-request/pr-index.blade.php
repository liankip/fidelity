<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="primary-color-sne">Purchase Requests</h2>
            </div>
            @hasanyrole('it|top-manager|adminlapangan|manager')
                <div class="pull-right mb-2">
                    {{-- <a class="btn btn-success" href="{{ route('itempr.index') }}"> Create purchaserequest</a> --}}
                </div>
            @endhasanyrole
        </div>
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

    <div class="card mt-5 primary-box-shadow">
        <div class="card-body">
            <form action="" method="get" class="d-flex">
                <div class="input-group mb-3 mt-3">
                    <input type="text" class="form-control" name="search" placeholder="Search"
                        wire:model="searchcompact" value="">
                </div>
            </form>

            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <button class="nav-link @if ($filter == 0) tabs-link-active @endif"
                        wire:click='filterHandler(0)'>All
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link @if ($filter == 1) tabs-link-active @endif"
                        wire:click='filterHandler(1)'>New
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link @if ($filter == 2) tabs-link-active @endif"
                        wire:click='filterHandler(2)'>Draft
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link @if ($filter == 3) tabs-link-active @endif"
                        wire:click='filterHandler(3)'>Partially
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link @if ($filter == 4) tabs-link-active @endif"
                        wire:click='filterHandler(4)'>Processed
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link @if ($filter == 5) tabs-link-active @endif"
                        wire:click='filterHandler(5)'>PO Approved
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link @if ($filter == 6) tabs-link-active @endif"
                        wire:click='filterHandler(6)'>Canceled
                    </button>
                </li>
            </ul>

            <div class="overflow-scroll primary-box-shadow mt-4">
                <table class="table fs-6">
                    <thead class="thead-light text-center">
                        <tr class="text-center table-secondary">
                            <th style="width: 3%" class="align-middle border-top-left">No</th>
                            <th style="width: 13%" class="align-middle">PR No</th>
                            <th style="width: 6%" class="align-middle">PR Type</th>
                            <th style="width: 10%" class="align-middle">Project</th>
                            <th style="width: 5%" class="align-middle">Requester</th>
                            <th style="width: 10%" class="align-middle">Bagian</th>
                            <th style="width: 8%" class="align-middle">Tgl Request</th>
                            <th style="width: 7%" class="align-middle">Status</th>
                            <th style="width: 10%" class="align-middle">Notes</th>
                            <th style="width: 20%" class="align-middle">Status PO</th>
                            <th style="width: 5%" class="align-middle border-top-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchaserequests as $key => $purchaserequest)
                            <tr style="font-size: 13px; background-color: white"
                                onmouseover="this.style.backgroundColor='#F4F6F6'"
                                onmouseout="this.style.backgroundColor='white'">
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>
                                    @if ($purchaserequest->pr_no)
                                        <b>{{ $purchaserequest->pr_no }}</b>
                                    @else
                                        <b>-</b>
                                    @endif
                                </td>
                                <td>{{ $purchaserequest->pr_type }}</td>
                                <td>
                                    @if ($purchaserequest->project)
                                        {{ $purchaserequest->project->name }}
                                    @elseif($purchaserequest->prdetail->every(fn ($pod) => $pod->is_raw_materials == 1))
                                        Manufacture
                                    @endif
                                </td>
                                <td>{{ $purchaserequest->requester }}</td>
                                <td>
                                    @if ($purchaserequest->partof)
                                        {{ $purchaserequest->partof }}
                                    @else
                                        @php
                                            $isRawMaterials = $purchaserequest->prdetail->every(
                                                fn($pod) => $pod->is_raw_materials == 1,
                                            );
                                        @endphp
                                        {{ $isRawMaterials ? 'Raw Materials' : '-' }}
                                    @endif
                                </td>
                                <td>{{ date('d/m/Y', strtotime($purchaserequest->created_at)) }}</td>
                                <td>
                                    @if ($purchaserequest->status == 'Processed')
                                        <span class="badge badge-success">
                                            {{ $purchaserequest->status }}
                                        </span>
                                    @elseif($purchaserequest->status == 'Partially')
                                        <span class="badge badge-primary">
                                            {{ $purchaserequest->status }}
                                        </span>
                                    @elseif ($purchaserequest->status == 'Draft')
                                        <span class="badge badge-warning">
                                            {{ $purchaserequest->status }}
                                        </span>
                                    @elseif ($purchaserequest->status == 'Cancel' || $purchaserequest->status == 'Duplicated')
                                        <span class="badge badge-danger">
                                            {{ $purchaserequest->status }}
                                        </span>
                                    @elseif ($purchaserequest->status == 'New')
                                        <span class="badge badge-primary">

                                            {{ $purchaserequest->status }}
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            {{ $purchaserequest->status }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($purchaserequest->remark)
                                        {{ $purchaserequest->remark }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if (count($purchaserequest->po))
                                        @foreach ($purchaserequest->po as $podata)
                                            <div class="d-flex" style="font-size: 11px">
                                                <a class=""
                                                    href="{{ url('/po_details', ['id' => $podata->id]) }}"
                                                    target="__blank">
                                                    {{ $podata->po_no }} ({{ $podata->status }})
                                                </a>&nbsp;
                                                @if ($podata->deliver_status != 0)
                                                @elseif ($podata->status == 'New' || $podata->status == 'Cancel' || $podata->status == 'Wait For Approval')
                                                @else
                                                    <form class="border-start"
                                                        action="{{ route('printmemo', $podata->id) }}" target="__blank"
                                                        method="post">
                                                        @method('PUT')
                                                        @csrf
                                                        <button type="submit"
                                                            class="border-0 justify-content-center bg-transparent underline text-success font-bold">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-printer"
                                                                viewBox="0 0 16 16">
                                                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                                                                <path
                                                                    d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
                                                            </svg>
                                                            <span>
                                                                Memo
                                                            </span>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="">
                                    <div class="btn-group">
                                        <button class="btn btn-outline-info btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Option
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if ($purchaserequest->status == 'Cancel')
                                                @if (auth()->user()->hasGeneralAccess())
                                                    @can(\App\Permissions\Permission::DUPLICATE_PR)
                                                        <form action="{{ route('duplicate_pr', $purchaserequest->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('put')
                                                            <button type="submit" class="dropdown-item">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                    height="14" fill="currentColor" class="bi bi-back"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M0 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2H2a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2z" />
                                                                </svg>
                                                                Duplicate
                                                            </button>
                                                        </form>
                                                    @endcan
                                                @endif
                                            @else
                                                @if (auth()->user()->hasGeneralAccess() && $purchaserequest->status != 'Duplicated')
                                                    @hasanyrole('it|top-manager|adminlapangan|manager')
                                                        @if (
                                                            $purchaserequest->status == 'New' ||
                                                                $purchaserequest->status == 'Review' ||
                                                                $purchaserequest->status == 'New Duplicate' ||
                                                                $purchaserequest->status == 'Draft')
                                                            @if ($purchaserequest->pr_type != 'Barang')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('itempr.edit', $purchaserequest->id) }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                        height="14" fill="currentColor"
                                                                        class="bi bi-pencil" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                                                                    </svg>
                                                                    Edit
                                                                    Item
                                                                    Load</a>
                                                            @endif
                                                        @endif
                                                        @if ($purchaserequest->status == 'Draft' && count($purchaserequest->prdetail))
                                                            @can(\App\Permissions\Permission::AJUKAN_PR)
                                                                <form
                                                                    action="{{ route('ajukan.purchase-request', ['id' => $purchaserequest->id]) }}"
                                                                    method="POST" class="mb-2">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button class="dropdown-item">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                            height="14" fill="currentColor"
                                                                            class="bi bi-box-arrow-in-left"
                                                                            viewBox="0 0 16 16">
                                                                            <path fill-rule="evenodd"
                                                                                d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z" />
                                                                            <path fill-rule="evenodd"
                                                                                d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                                                                        </svg>
                                                                        Ajukan PR
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        @endif
                                                    @endhasanyrole
                                                    @if (count($purchaserequest->podetail))
                                                        @if ($purchaserequest->status == 'New' || $purchaserequest->status == 'New Duplicate')
                                                            @if (auth()->user()->can(\App\Permissions\Permission::CREATE_PO) && count($purchaserequest->prdetail))
                                                                @if ($purchaserequest->pr_type == 'Barang')
                                                                    <a class="dropdown-item"
                                                                        href="/purchase_order/chooceitempr/{{ $purchaserequest->id }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="14" height="14"
                                                                            fill="currentColor"
                                                                            class="bi bi-clipboard2-plus"
                                                                            viewBox="0 0 16 16">
                                                                            <path
                                                                                d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z" />
                                                                            <path
                                                                                d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-12Z" />
                                                                            <path
                                                                                d="M8.5 6.5a.5.5 0 0 0-1 0V8H6a.5.5 0 0 0 0 1h1.5v1.5a.5.5 0 0 0 1 0V9H10a.5.5 0 0 0 0-1H8.5V6.5Z" />
                                                                        </svg>
                                                                        Create PO
                                                                    </a>
                                                                @endif
                                                                @if ($purchaserequest->pr_type != 'Barang')
                                                                    <a class="dropdown-item"
                                                                        href="/spk/{{ $purchaserequest->id }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="14" height="14"
                                                                            fill="currentColor"
                                                                            class="bi bi-clipboard2-plus"
                                                                            viewBox="0 0 16 16">
                                                                            <path
                                                                                d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z" />
                                                                            <path
                                                                                d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-12Z" />
                                                                            <path
                                                                                d="M8.5 6.5a.5.5 0 0 0-1 0V8H6a.5.5 0 0 0 0 1h1.5v1.5a.5.5 0 0 0 1 0V9H10a.5.5 0 0 0 0-1H8.5V6.5Z" />
                                                                        </svg>
                                                                        Create
                                                                        SPK New</a>
                                                                @endif
                                                            @endif
                                                        @else
                                                            {{-- @php
                                                    $count = 0;
                                                    $false = 0;
                                                @endphp
                                                @foreach ($purchaserequest->podetail as $item)
                                                    @php
                                                        $data_qty = DB::table('purchase_order_details')
                                                            ->where('purchase_request_detail_id', $item->id)
                                                            ->where('item_id', $item->item_id)
                                                            ->sum('qty');
                                                    @endphp
                                                    @if ($item->podetail)
                                                        @php
                                                            $count++;
                                                        @endphp
                                                    @endif
                                                    @if ($item->qty > $data_qty)
                                                        @php
                                                            $false++;
                                                        @endphp
                                                    @endif
                                                @endforeach --}}

                                                            @if (
                                                                $purchaserequest->status != 'Processed' &&
                                                                    $purchaserequest->status != 'Wait for approval' &&
                                                                    ($purchaserequest->status != 'Cancel' && $purchaserequest->status != 'Draft'))
                                                                @if (auth()->user()->hasGeneralAccess())
                                                                    @if ($purchaserequest->pr_type == 'Barang')
                                                                        @can(\App\Permissions\Permission::CREATE_PO)
                                                                            <a class="dropdown-item"
                                                                                href="/purchase_order/chooceitempr/{{ $purchaserequest->id }}">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="14" height="14"
                                                                                    fill="currentColor"
                                                                                    class="bi bi-clipboard2-plus"
                                                                                    viewBox="0 0 16 16">
                                                                                    <path
                                                                                        d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z" />
                                                                                    <path
                                                                                        d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-12Z" />
                                                                                    <path
                                                                                        d="M8.5 6.5a.5.5 0 0 0-1 0V8H6a.5.5 0 0 0 0 1h1.5v1.5a.5.5 0 0 0 1 0V9H10a.5.5 0 0 0 0-1H8.5V6.5Z" />
                                                                                </svg>
                                                                                Create PO
                                                                            </a>
                                                                        @endcan
                                                                    @endif
                                                                    @if ($purchaserequest->pr_type != 'Barang')
                                                                        <a class="dropdown-item"
                                                                            href="/spk/{{ $purchaserequest->id }}">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="14" height="14"
                                                                                fill="currentColor"
                                                                                class="bi bi-clipboard2-plus"
                                                                                viewBox="0 0 16 16">
                                                                                <path
                                                                                    d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z" />
                                                                                <path
                                                                                    d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-12Z" />
                                                                                <path
                                                                                    d="M8.5 6.5a.5.5 0 0 0-1 0V8H6a.5.5 0 0 0 0 1h1.5v1.5a.5.5 0 0 0 1 0V9H10a.5.5 0 0 0 0-1H8.5V6.5Z" />
                                                                            </svg>
                                                                            Create SPK
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                    @if ($purchaserequest->status == 'Draft' || $purchaserequest->status == 'New')
                                                        @can(\App\Permissions\Permission::CANCEL_PR)
                                                            <button class="dropdown-item"
                                                                wire:click="showconfirmcancel({{ $purchaserequest->id }} ,'{{ $purchaserequest->pr_no }}' )">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                                    height="14" fill="currentColor" class="bi bi-x"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                                </svg>
                                                                Cancel
                                                            </button>
                                                        @endcan
                                                    @else
                                                        {{-- @if (auth()->user()->type == 'purchasing' || auth()->user()->type == 'it' || auth()->user()->type == 'manager')
                                                <button class="dropdown-item"
                                                    wire:click="showconfirmcancel({{ $purchaserequest->id }} ,'{{ $purchaserequest->pr_no }}' )"><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="14"
                                                        height="14" fill="currentColor" class="bi bi-x"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                                    </svg> Cancel</button>
                                            @endif --}}
                                                    @endif
                                                @endif
                                            @endif
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('purchase_request_details.show', $purchaserequest->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                        height="14" fill="currentColor" class="bi bi-eye"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                                        <path
                                                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                                    </svg>
                                                    Detail
                                                </a>
                                            </li>
                                        </ul>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">
                                    <h5 class="my-2">Data Not Found</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
            <div class="mt-4 d-flex justify-content-end">
                {{ $purchaserequests->links() }}
            </div>
        </div>

        @if ($conformdeletemodal)
            @include('components.pr.cancelconfirm')
        @endif

    </div>
</div>
