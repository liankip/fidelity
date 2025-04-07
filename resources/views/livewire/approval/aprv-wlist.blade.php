<div>
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2 class="primary-color-sne">PO Waiting Approval</h2>
                </div>
            </div>
        </div>
        <x-common.notification-alert />
        <div class="card primary-box-shadow mt-5">
            <div class="card-header d-flex justify-content-between">

                {{-- @if (auth()->user()->type != 'admin_2')
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Action
                        </button>
                        @hasanyrole('it|top-manager|manager')
                            <ul class="dropdown-menu">
                                <li>
                                    <button class="dropdown-item" wire:click='approve'>Approve</button>
                                </li>
                                <li>
                                    <button class="dropdown-item" wire:click='showconsernmultiple'>Revert</button>
                                </li>
                            </ul>
                        @endhasanyrole
                    </div>
                @endif --}}
            </div>

            <div class="card-body" style="overflow-x: scroll;">
                <table class="table primary-box-shadow">
                    <thead class="thead-light">
                        <tr class="table-secondary">
                            <th class="text-center border-top-left" style="width: 5%"><input class="form-check-input"
                                    style="width: 20px; height: 20px" type="checkbox" wire:model='checkall'
                                    wire:click='allcheck'></th>
                            <th style="text-align: center; width: 25%;" class="border">PO No/ SPK No</th>
                            <th style="text-align: center; width: 20%;" class="border">Warehouse</th>
                            <th style="text-align: center; width: 18%;" class="border">Type</th>
                            <th style="text-align: center; width: 5%;" class="border">Item</th>
                            <th style="text-align: center; width: 17%;" class="border">Total Amount</th>
                            <th style="text-align: center; width: 10%;" class="border border-top-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="border">
                        @if (!count($po))
                            <tr class="text-center">
                                <td colspan="7" class="py-2 fw-bold">
                                    No purchase order need to approve
                                </td>
                            </tr>
                        @endif
                        @foreach ($po as $key => $purchaseorder)
                            @php
                                $apakah_partial = App\Helpers\CheckPartially::get($purchaseorder);
                            @endphp

                            <tr class="">
                                <td class="text-center border-bottom-0 border-top border-start border-end">
                                    @if ($purchaseorder->project?->canApprovePO() || isset($status['isStock']) || isset($status['isRawMaterials']))
                                        <input class="form-check-input" style="width: 20px; height: 20px"
                                            type="checkbox" wire:model='prarray.{{ $key }}.checked'>
                                    @endif
                                </td>
                                <td class="border">
                                    @if ($purchaseorder->po_no)
                                        <div>
                                            <span
                                                style="background-color: #ffc107; padding: 0px 5px; border-radius: 6px;">
                                                {{ $purchaseorder->po_no }}
                                            </span>
                                        </div>
                                    @endif
                                    <span style="font-weight: 900">
                                        Vendor :
                                        {{ $purchaseorder->supplier ? $purchaseorder->supplier->name : 'data supplier terhapus' }}
                                        <br>
                                    </span>
                                    @if ($purchaseorder->project)
                                        <span>
                                            Project :
                                            {{ $purchaseorder->project ? $purchaseorder->project->name : 'data project terhapus' }}
                                            <br>
                                        </span>
                                    @else
                                        <span>Stok persediaan gudang</span>
                                    @endif

                                    {{-- <span style="font-size: 14px; font-style: italic;">
                                        Notes:
                                        @if ($purchaseorder->pr)
                                            {{ $purchaseorder->pr->remark }}
                                        @endif
                                        <br>
                                    </span> --}}

                                    <span
                                        style="font-size: 14px;
                                                font-style: italic;">
                                    </span>
                                </td>

                                <td class="border">
                                    <span>
                                        <span
                                            style="background-color: #198754;
                                                    color: white;
                                                    padding: 0px 10px;
                                                    border-radius: 6px;">Tanggal
                                            Request</span><br>
                                        <div style="font-weight: 900">{{ $purchaseorder->created_at }}</div>
                                    </span>
                                    @if ($purchaseorder->warehouse_id != 0 && $purchaseorder->warehouse)
                                        {{ $purchaseorder->warehouse->name }}
                                    @else
                                        Project
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        @php
                                            $status = $statuses[$purchaseorder->id] ?? [];
                                        @endphp

                                        @if ($purchaseorder->pr == null && isset($status['isBulkPo']))
                                            <span class="badge badge-success">Bulk</span>
                                        @elseif (isset($status['isStock']))
                                            <span class="badge badge-success">Stock</span>
                                        @elseif (isset($status['isRawMaterials']))
                                            <span class="badge badge-success">Raw Materials</span>
                                        @else
                                            <span class="bg-info rounded px-1">Purchase Request</span>
                                        @endif

                                    </div>
                                    {{-- @if (!$purchaseorder->podetail->every(fn($pod) => $pod->is_bulk == 1))
                                    <div class="d-flex">
                                        Bagian: {{ $purchaseorder->pr?->partof }}
                                    </div>
                                    <div class="d-flex fw-bold">
                                        Requester: {{ $purchaseorder->pr?->requester }}
                                    </div>
                                @endif     --}}
                                </td>
                                <td class="border" style="text-align: center">
                                    {{ count($purchaseorder->podetail) }}
                                </td>
                                <td class="border">
                                    @php
                                        $getamount = App\Helpers\GetAmount::get($purchaseorder);
                                    @endphp

                                    <div class="d-flex justify-content-between">
                                        <div>Rp.</div>
                                        <div>{{ number_format($getamount['total'], 0, ',', '.') }}</div>
                                    </div>
                                </td>

                                <td class="border">
                                    <div class="d-flex justify-content-center">
                                        <div class="w-100">
                                            @hasanyrole('it|top-manager|manager')
                                                @if ((bool) $setting->multiple_po_approval)
                                                    @if (
                                                        $purchaseorder->date_approved == null &&
                                                            $purchaseorder->approved_by == null &&
                                                            ($purchaseorder->project?->canApprovePO() || isset($status['isStock']) || isset($status['isRawMaterials'])))
                                                        <span class="w-100">
                                                            {{-- <form action="{{ route('approve', $purchaseorder->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('put') --}}
                                                            <button class="btn btn-success btn-sm w-100" type="button"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#exampleModal-{{ $purchaseorder->id }}">
                                                                Approve
                                                            </button>
                                                            {{-- </form> --}}
                                                            <button type="button"
                                                                style="font-variant-numeric: tabular-nums;"
                                                                wire:click='showconsern({{ $purchaseorder->id }})'
                                                                class="btn btn-danger btn-sm w-100 mt-1"
                                                                data-toggle="modal">Revert</button>
                                                        </span>
                                                    @elseif(
                                                        $purchaseorder->date_approved != null &&
                                                            $purchaseorder->approved_by != null &&
                                                            $purchaseorder->date_approved_2 == null &&
                                                            $purchaseorder->approved_by_2 == null)
                                                        @if (
                                                            $purchaseorder->approved_by != auth()->user()->id &&
                                                                ($purchaseorder->project?->canApprovePO() || isset($status['isStock']) || isset($status['isRawMaterials'])))
                                                            <span class="w-100">
                                                                {{-- <form action="{{ route('approve', $purchaseorder->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('put') --}}
                                                                <button type="button" data-bs-toggle="modal"
                                                                    data-bs-target="#exampleModal-{{ $purchaseorder->id }}"
                                                                    class="btn btn-success btn-sm w-100">Approve</button>
                                                                {{-- </form> --}}
                                                                <button type="button"
                                                                    style="font-variant-numeric: tabular-nums;"
                                                                    wire:click='showconsern({{ $purchaseorder->id }})'
                                                                    class="btn btn-danger btn-sm w-100 mt-1"
                                                                    data-toggle="modal">Revert</button>
                                                            </span>
                                                        @else
                                                            <button disabled class="btn btn-sm btn-success">Approved
                                                            </button>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if (
                                                        $purchaseorder->date_approved == null &&
                                                            $purchaseorder->approved_by == null &&
                                                            ($purchaseorder->project?->canApprovePO() || isset($status['isStock']) || isset($status['isRawMaterials'])))
                                                        <span class="w-100">
                                                            {{-- <form action="{{ route('approve', $purchaseorder->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('put') --}}
                                                            <button type="button" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModal-{{ $purchaseorder->id }}"
                                                                class="btn btn-success btn-sm w-100">Approve</button>
                                                            {{-- </form> --}}
                                                            <button type="button"
                                                                style="font-variant-numeric: tabular-nums;"
                                                                wire:click='showconsern({{ $purchaseorder->id }})'
                                                                class="btn btn-danger btn-sm w-100 mt-1"
                                                                data-toggle="modal">Revert</button>
                                                        </span>
                                                    @endif
                                                @endif
                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal-{{ $purchaseorder->id }}"
                                                    tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="{{ route('approve', $purchaseorder->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('put')
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                                        Approve PO</h1>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="message-text"
                                                                            class="col-form-label">Notes:</label>
                                                                        <textarea class="form-control" id="message-text" name="notes"></textarea>
                                                                    </div>
                                                                    <p>*note yang diberikan akan muncul hanya muncul pada
                                                                        internal sistem</p>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Submit</button>
                                                                    </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endhasanyrole
                                        </div>
                                    </div>

                                </td>
                            </tr>
                            <tr class="">
                                <td class="border-top-0 border-end border-start"></td>
                                <td colspan="10">
                                    <div class="accordian-body">
                                        <table class="table">
                                            <thead class="border">
                                                <tr class="info">
                                                    <th class="border border-top-left" style="text-align: center">No
                                                    </th>
                                                    <th class="border" style="text-align: center">Item Name</th>
                                                    <th class="border" style="text-align: center">Quantity/Unit
                                                    </th>
                                                    <th class="border" style="text-align: center">Pr No
                                                    </th>
                                                    <th class="border" style="text-align: center">Harga</th>
                                                    <th class="border border-top-right" style="text-align: center">
                                                        Jumlah</th>
                                                </tr>
                                            </thead>
                                            @php
                                                $total = 0;
                                                $tax = 0;
                                                $include = 0;
                                            @endphp
                                            <tbody class="bg-white">
                                                @foreach ($purchaseorder->podetail as $keydetail => $detail)
                                                    @if ($keydetail == 0)
                                                        @php
                                                            $include = $detail->tax_status;
                                                            if ($detail->tax_status == 2) {
                                                                $tax = 0;
                                                            } else {
                                                                $tax = 11;
                                                            }
                                                        @endphp
                                                    @endif
                                                    <tr data-toggle="collapse" class="accordion-toggle"
                                                        data-target="#demo10">
                                                        <td class="border" style="text-align: center; width: 5%">
                                                            {{ $keydetail + 1 }}
                                                        </td>
                                                        <td class="border">
                                                            {{ $detail->prdetail ? $detail->prdetail->item_name : $detail->item->name }}
                                                            @if ($detail->supplier_description != null)
                                                                <br>
                                                                <small class="text-muted"
                                                                    style="font-size: 10pt">Product desc :
                                                                    {{ $detail->supplier_description }}</small>
                                                            @endif
                                                        </td>

                                                        <td class="border" align="right">
                                                            {{ (int) $detail->qty }}
                                                            {{ $detail->unit }}
                                                        </td>

                                                        <td class="border">
                                                            @if ($detail->prdetail !== null)
                                                                <a class="fw-bold"
                                                                    href="{{ route('purchase_request_details.show', $detail->prdetail->purchaseRequest->id) }}">
                                                                    {{ $detail->prdetail->purchaseRequest->pr_no }}
                                                                </a>
                                                                <div class="fw-bold">
                                                                    Requester:
                                                                    {{ $detail->prdetail->purchaseRequest->requester }}
                                                                </div>
                                                                <div class="fw-bold">
                                                                    @if($purchaseorder->podetail->every(fn ($pod) => $pod->is_raw_materials == 1))
                                                                        Raw Materials
                                                                    
                                                                    @else
                                                                    WBS: <a
                                                                        href="{{ route('task-monitoring.index', $detail->prdetail->purchaseRequest->task->id) }}">{{ $detail->prdetail->purchaseRequest->partof }}</a>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>

                                                        <td class="border" align="right">
                                                            <div style="display: flex;justify-content: end">
                                                                {{ rupiah_format($harga = $detail->price) }}
                                                            </div>

                                                            @php
                                                                $item = $supplierItemPrice
                                                                    ->where(
                                                                        'item_id',
                                                                        $detail->prdetail
                                                                            ? $detail->prdetail->item_id
                                                                            : $detail->item_id,
                                                                    )
                                                                    ->first();
                                                            @endphp

                                                            @if ($item)
                                                                @if ($item['price'] !== $detail->price)
                                                                    <div class="text-muted mt-2">
                                                                        <span style="font-size: 16px"
                                                                            class="fw-semibold">Other Supplier :</span>
                                                                        <div style="font-size: 14px">
                                                                            <span>{{ $item['vendor'] }}</span> -
                                                                            <span>
                                                                                {{ rupiah_format($item['price']) }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td class="border">
                                                            <div style="display: flex;justify-content: end">
                                                                {{ rupiah_format($detail->amount) }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $total += $detail->amount;
                                                    @endphp
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <div class="d-flex justify-content-between">
                                            <div>
                                                @if ($apakah_partial)
                                                    @if ($purchaseorder->approved_by != null)
                                                        <span
                                                            style="font-weight: bold">{{ $purchaseorder->approvedby->name }}</span>
                                                    @endif
                                                @endif
                                                @php
                                                    if ($purchaseorder->notes != null) {
                                                        $notes = json_decode($purchaseorder->notes);
                                                    } else {
                                                        $notes = [];
                                                    }
                                                @endphp
                                                <span style="font-weight: bold">Note:</span>
                                                @if (isset($notes))
                                                    @foreach ($notes as $note)
                                                        <li>{{ $note->notes }}</li>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="col-3">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td class=" table-bordered">Amount:</td>
                                                        <td>
                                                            <div style="display: flex;justify-content: space-between">
                                                                <span data-prefix>Rp. </span>
                                                                <div>{{ number_format($total, 0, ',', '.') }}</div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class=" table-bordered">PPN:</td>
                                                        <td>
                                                            <div style="display: flex;justify-content: space-between">
                                                                <span data-prefix>Rp. </span>
                                                                <div>
                                                                    {{ str_replace(',00', '', number_format($getamount['ppn'], 2, ',', '.')) }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $tarifds = 0;
                                                    @endphp
                                                    @if ($purchaseorder->deliver_status == 2)
                                                        <tr>
                                                            <td class=" table-bordered">Ongkos kirim:</td>
                                                            <td>
                                                                <div
                                                                    style="display: flex;justify-content: space-between">
                                                                    <span data-prefix>Rp. </span>
                                                                    <div>
                                                                        {{ number_format($getamount['ongkir'], 0, ',', '.') }}
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $tarifds = $purchaseorder->tarif_ds;
                                                        @endphp
                                                    @endif
                                                    <tr style="font-weight: bold">
                                                        <td class="font-bold">TOTAL:</td>
                                                        <td class="font-bold">
                                                            <div style="display: flex;justify-content: space-between">
                                                                <span data-prefix>Rp. </span>
                                                                <div>
                                                                    {{ str_replace(',00', '', number_format($getamount['total'], 2, ',', '.')) }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
                @if ($consernshow)
                    @include('components.appwlist.modalrevert')
                @elseif ($consernshowmultiple)
                    @include('components.appwlist.modalrevertmultiple')
                @endif
            </div>
        </div>

    </div>

</div>
