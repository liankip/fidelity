@php use Carbon\Carbon; @endphp
<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="primary-color-sne">Purchase Order Detail</h2>
                    </div>
                    <div>
                        <span class="badge badge-primary fs-4">{{ $this->statuspo->status }}</span>
                        <br>
                        {{-- @if ($statuspo->status == 'Approved')
                            <button wire:click="updatePaid" class="btn btn-primary">
                                Update Payment Status
                            </button>
                        @endif --}}

                        @if (isset($voucherData))
                            @if ($statuspo->status == 'Approved' && count($voucherData))
                                <br>
                                <span class="text-success fw-bolder">~Partially Paid</span>
                            @endif
                        @endif
                    </div>
                </div>
                @if (!is_null($statuspo->approvedby) && !is_null($statuspo->approved_at))
                    <div class="text-sm">
                        <div class="mb-2 text-muted"><strong>Approved by:</strong></div>

                        <div class="d-flex gap-2">
                            <div class="card p-2 bg-success text-white">
                                <div><strong>{{ $statuspo->approvedby->name }}</strong></div>
                                <div><em>{{ date('d F Y', strtotime($statuspo->approved_at)) }}</em></div>
                            </div>

                            @if ($statuspo->approved_by_2)
                                <div class="card p-2 bg-success text-white">
                                    <div><strong>{{ $statuspo->approvedby2->name }}</strong></div>
                                    <div><em>{{ date('d F Y', strtotime($statuspo->date_approved_2)) }}</em></div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
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
    <div class="card">
        <div class="card-body">

            <div style="overflow-x: scroll;">
                <div style="display: flex;justify-content: space-between" class="mb-1">
                    <div style="font-weight: bold">
                        <div>{{ $statuspo->supplier->name }}</div>
                        <div>{{ $statuspo->supplier->address }}</div>
                        <div>{{ $statuspo->supplier->city }}, {{ $statuspo->supplier->province }}</div>
                        <div class="mt-3">
                            @if ($voucherData !== null && count($voucherData) > 0)
                                <p class="text-primary">Sudah terdaftar pada voucher: </p>
                                <table class="table table-borderless table-hover" style="margin-right: 10px">
                                    @foreach ($voucherData as $index => $data)
                                        <tr class="list-group-item d-flex gap-3 bg-light" style="width: fit-content">
                                            <td>
                                                <span><a class="text-primary"
                                                        href="{{ route('payment-submission.voucher.detail', ['submission' => $data->voucher->payment_submission_id, 'voucher' => $data->voucher->id]) }}">{{ $data->voucher->voucher_no }}
                                                    </a>
                                                    ({{ Carbon::parse($data->voucher->created_at)->format('d F Y') }})
                                                </span>
                                                <span>
                                                    {{ rupiah_format($data->amount_to_pay) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endif

                            @if (count($submittionHistory))
                                <p class="text-primary">Foto Barang</p>
                                <table class="table table-borderless table-hover">
                                    <tbody>
                                        @foreach ($submittionHistory as $index => $data)
                                            <tr class="list-group-item d-flex gap-3 bg-light"
                                                style="width: fit-content">
                                                <td>
                                                    <p>
                                                        <span class="text-primary">Foto barang periode
                                                            ke-{{ $index + 1 }} diupload tanggal</span>
                                                        ({{ Carbon::parse($data->date)->format('d F Y') }})
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            @if (count($deliverOrder))
                                <p class="text-primary">Foto Surat Jalan</p>
                                <table class="table table-borderless table-hover">
                                    <tbody>
                                        @foreach ($deliverOrder as $index => $data)
                                            <tr class="list-group-item d-flex gap-3 bg-light"
                                                style="width: fit-content">
                                                <td>
                                                    <p>
                                                        <span class="text-primary"> Foto Surat Jalan
                                                            Ke-{{ $index + 1 }} diupload tanggal</span>
                                                        ({{ Carbon::parse($data->date)->format('d F Y') }})
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                            @if (count($invoices))
                                <p class="text-primary">Foto Invoice</p>
                                <table class="table table-borderless table-hover">
                                    <tbody>
                                        @foreach ($invoices as $index => $data)
                                            <tr class="list-group-item d-flex gap-3 bg-light"
                                                style="width: fit-content">
                                                <td>
                                                    <p>
                                                        <span class="text-primary">
                                                            Foto Surat Invoice Ke-{{ $index + 1 }} diupload tanggal
                                                        </span>
                                                        ({{ Carbon::parse($data->date)->format('d F Y') }})
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                    <table class="table table-bordered" style="width: 30%; height: fit-content">
                        <tr>
                            <td colspan="2">
                                <div style="margin-bottom: 3px"><strong>{{ $our_company->name }}</strong></div>
                                <div style="line-height: 18px;margin-bottom: 3px">
                                    {{ $our_company->address }}
                                </div>
                                <div>NPWP : {{ $our_company->npwpd }}</div>
                            </td>
                        </tr>

                        <tr>
                            <th>No.</th>
                            <td>
                                @if ($statuspo)
                                    {{ $statuspo->po_no }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Date</th>
                            <td>{{ $newDate }}</td>
                        </tr>

                        <tr>
                            <th>Project</th>
                            <td>
                                @if ($statuspo)
                                    @if ($statuspo->project)
                                        {{ $statuspo->project->name }}
                                    @endif
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Payment Term</th>
                            <td>
                                @if ($statuspo)
                                    {{ $statuspo->term_of_payment }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Pengiriman</th>
                            <td>
                                @if ($statuspo->deliver_status == 0)
                                    Dijemput
                                @elseif ($statuspo->deliver_status == 1)
                                    {{ $statuspo->ds->name }}
                                @elseif ($statuspo->deliver_status == 2)
                                    Diantar Toko
                                @endif
                            </td>
                        </tr>
                        @if ($statuspo->notes !== null)
                            @php
                                $notes = json_decode($statuspo->notes);
                            @endphp
                            <tr>
                                <th>Notes</th>
                                <td>
                                    @foreach ($notes as $note)
                                        <li>{{ $users[$note->user_id] ?? 'Unknown User' }}: {{ $note->notes }}</li>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                @php
                    if (count($statuspo->do)) {
                        $doexist = true;
                    } else {
                        $doexist = false;
                    }
                @endphp

                <table class="table table-bordered">
                    <tr class="thead-light">
                        <th style="text-align: center" class="border-top-left">No</th>
                        <th style="text-align: center"><span>Item Code</span></th>
                        <th style="text-align: center">Item Name</th>
                        <th style="text-align: center">PR Number</th>
                        <th style="text-align: center; width: 10%">Quantity / Unit</th>
                        @hasanyrole('user|admin|manager|purchasing|finance|it|top-manager|adminlapangan')
                            <th style="text-align: center">Harga</th>
                            <th style="text-align: center">Jumlah</th>
                        @endhasanyrole
                        @if ($statuspo->isProcessed() || $statuspo->pr_no == null)

                            <th style="text-align: center">Status</th>
                            {{-- <th style="text-align: center">Notes</th> --}}
                            @if ($statuspo->percent_complete < 100)
                                <th style="text-align: center;width: 15%">Action</th>
                            @endif
                        @endif
                    </tr>
                    @php
                        $total = 0;
                        $tax = 0;
                        $include = 0;
                    @endphp
                    @foreach ($statuspo->podetail as $key => $val)
                        @if ($key == 0)
                            @php
                                $include = $val->tax_status;
                                if ($val->tax_status == 2) {
                                    $tax = 0;
                                } else {
                                    $tax = 11;
                                }
                            @endphp
                        @endif
                        @php
                            if (empty($val->prdetail) && $val->purchase_request_detail_id != null) {
                                continue;
                            }

                        @endphp
                        <tr>
                            <td class="align-middle" style="text-align: center; width: 5%">{{ $key + 1 }}</td>
                            <td class="align-middle">{{ $val->item->item_code }}</td>
                            <td class="align-middle">{{ $val->item->name }}
                                @if($val->supplier_description != null)
                                    <br>
                                    <small class="text-muted" style="font-size: 10pt">Product desc : {{ $val->supplier_description }}</small>
                                @else
                                <br>
                                    <small class="text-muted" style="font-size: 10pt">Product desc : {{ $val->prdetail ? $val->prdetail->item_name : $val->item->name }}</small>
                                @endif
                            </td>
                            <td class="align-middle">{{ $val->prdetail ? $val->prdetail->purchaseRequest->pr_no : '' }}</td>
                            <td class="align-middle" align="right">
                                {{ str_replace(',00', '', number_format($val->qty, 2, ',', '.')) }}
                                {{ $val->unit }}
                            </td>
                            @hasanyrole('user|admin|manager|purchasing|finance|it|top-manager|adminlapangan')
                                <td class="align-middle">
                                    <div style="display: flex;justify-content: space-between">
                                        <span data-prefix>Rp. </span>
                                        <div>
                                            {{ str_replace(',00', '', number_format($harga = $val->price, 2, ',', '.')) }}
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div style="display: flex;justify-content: space-between">
                                        <span data-prefix>Rp. </span>
                                        <div>{{ str_replace(',00', '', number_format($val->amount, 2, ',', '.')) }}
                                        </div>
                                    </div>
                                </td>
                            @endhasanyrole

                            @if ($statuspo->isProcessed() || $statuspo->pr_no == null)
                                <td class="align-middle">
                                    {{ str_replace(',00', '', number_format($val->total_sampai, 2, ',', '.')) }} /
                                    {{ str_replace(',00', '', number_format($val->qty, 2, ',', '.')) }}
                                </td>
                                {{-- <td class="align-middle">{{ $val->notes }}</td> --}}

                                @if ($statuspo->percent_complete < 100)
                                    <td>
                                        @if ($val->percent_complete < 100)
                                            @if (!auth()->user()->hasRole('admin_2'))
                                                @if ($doexist)
                                                    <div class="mb-2">
                                                        <form action="{{ route('create_submition', $val->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('get')
                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary w-100">Upload
                                                                Foto
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="mb-2">
                                                        <button type="submit" class="btn btn-sm btn-primary w-100"
                                                            disabled>Upload
                                                            Foto
                                                        </button>
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                    </td>
                                @endif
                                {{-- <form action="{{ route('arrivedpo', $val->purchase_order_id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-success">Submition</button>
                                    </form> --}}
                            @endif
                    @endif
                    </tr>
                    @hasanyrole('user|admin|manager|purchasing|finance|it|top-manager|adminlapangan')
                        @php
                            $total += $val->amount;
                        @endphp
                    @endhasanyrole
                    @endforeach
                </table>

                <div class="d-flex justify-content-between">
                    <div>
                        @if (
                            $statuspo->status != 'New' ||
                                $statuspo->status != 'Draft' ||
                                $statuspo->status != 'Rejected' ||
                                $statuspo->status != 'Draft With Delivery Services' ||
                                $statuspo->status != 'New With Delivery Services' ||
                                $statuspo->status != 'Cancel')
                            <div class="d-flex">
                                @if (!auth()->user()->hasRole('admin_2'))
                                    {{-- <form action="{{ route('viewphoto_submition', $statuspo->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-primary">View Photo Barang</button>
                                    </form> --}}
                                    <a href="{{ route('viewphoto_submition', $statuspo->id) }}"
                                        class="btn btn-primary">View Photo Barang
                                        ({{ $statuspo->totalSubmition() }})</a>
                                    <div class="p-2"></div>
                                    {{-- <form action="{{ route('viewphoto_do', $statuspo->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-primary ">View Photo DO</button>
                                    </form> --}}
                                    <a href="{{ route('viewphoto_do', $statuspo->id) }}" class="btn btn-primary">View
                                        Photo Surat Jalan ({{ $statuspo->totalDo() }})</a>

                                    <div class="p-2"></div>
                                    <a class="btn btn-primary" href="{{ route('viewphoto_inv', $statuspo->id) }}">View
                                        Photo Invoice ({{ $statuspo->totalInvoice() }})</a>
                                    {{-- <div>
                                        <form action="{{ route('create_do', $statuspo->id) }}" method="post">
                                            @csrf
                                            @method('get')
                                            <button type="submit" class="btn btn-primary  w-100">
                                                Upload Surat Jalan
                                            </button>
                                        </form>
                                    </div> --}}

                                    {{-- <form action="{{ route('viewphoto_inv', $statuspo->id) }}" method="post">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-primary">View Photo Invoice</button>
                                    </form> --}}
                                @endif
                            </div>
                        @endif
                    </div>


                    <div class="col-3">
                        @hasanyrole('admin|manager|purchasing|finance|it|top-manager|adminlapangan')
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
                                        <div style="display: flex;justify-content: space-between"
                                            class="position-relative">
                                            <span data-prefix>Rp. </span>
                                            <div>
                                                @if ($edittaxmode)
                                                    @php
                                                        $ppn = round($total * ($tax / 100));
                                                    @endphp
                                                    <input type="number" class="form-control"
                                                        wire:model.defer='modeltaxcustom'
                                                        placeholder="{{ $statuspo->tax_custom }}">
                                                    <div>
                                                        <button class="btn btn-sm btn-success"
                                                            wire:click='savetaxcustom'>save
                                                        </button>
                                                        <button class="btn btn-sm btn-success"
                                                            wire:click='canceltaxcustom'>cancel
                                                        </button>
                                                        <button class="btn btn-sm btn-success"
                                                            wire:click='resettaxcutome'>reset
                                                        </button>
                                                    </div>
                                                @else
                                                    @if ($statuspo->tax_custom)
                                                        {{ str_replace(',00', '', number_format($ppn = $statuspo->tax_custom, 2, ',', '.')) }}
                                                        @if (
                                                            $statuspo->status == 'Draft' ||
                                                                $statuspo->status == 'Rejected' ||
                                                                $statuspo->status == 'Draft With Delivery Services' ||
                                                                $statuspo->status == 'New With Delivery Services')
                                                            @hasanyrole('it|top-manager|purchasing|manager')
                                                                <button class="border-0 bg-transparent text-success"
                                                                    wire:key='button-edit' wire:click='activeedittaxmode'>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                        height="13" fill="currentColor"
                                                                        class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                                                                    </svg>
                                                                </button>
                                                            @endhasanyrole
                                                        @endif
                                                    @else
                                                        {{ number_format($ppn = round($total * ($tax / 100)), 0, ',', '.') }}
                                                        @if (
                                                            $statuspo->status == 'Draft' ||
                                                                $statuspo->status == 'Rejected' ||
                                                                $statuspo->status == 'Draft With Delivery Services' ||
                                                                $statuspo->status == 'New With Delivery Services')
                                                            @hasanyrole('it|top-manager|purchasing|manager')
                                                                <button class="border-0 bg-transparent text-success"
                                                                    wire:key='button-edit' wire:click='activeedittaxmode'>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                        height="13" fill="currentColor"
                                                                        class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                                        <path
                                                                            d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                                                                    </svg>
                                                                </button>
                                                            @endhasanyrole
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $tarifds = 0;
                                @endphp
                                @if ($statuspo->deliver_status == 2)
                                    <tr>
                                        <td class=" table-bordered">Ongkos kirim:</td>
                                        <td>
                                            <div style="display: flex;justify-content: space-between">
                                                <span data-prefix>Rp. </span>
                                                <div>{{ number_format($statuspo->tarif_ds, 0, ',', '.') }}</div>
                                            </div>
                                        </td>
                                    </tr>
                                    @php
                                        $tarifds = $statuspo->tarif_ds;
                                    @endphp
                                @endif

                                @if ($statuspo->deliver_status)
                                    <tr style="font-weight: bold">
                                        <td class="font-bold">TOTAL:</td>
                                        <td class="font-bold">
                                            <div style="display: flex;justify-content: space-between">
                                                <span data-prefix>Rp. </span>
                                                <div>{{ number_format($tarifds + $total + $ppn, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <tr style="font-weight: bold">
                                        <td class="font-bold">TOTAL:</td>
                                        <td class="font-bold">
                                            <div style="display: flex;justify-content: space-between">
                                                <span data-prefix>Rp. </span>
                                                <div>{{ number_format($ppn + $total, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        @endhasanyrole
                    </div>
                </div>
                @php
                    $bulkPrItems = $statuspo->pr?->prdetail->where('is_bulk', 1);
                @endphp

                @if ($bulkPrItems && count($bulkPrItems) > 0)
                    <h4>Memo Items</h4>

                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Memo Number</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Quantity/Unit</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bulkPrItems as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->memo_number }}</td>
                                    <td>{{ $item->item->item_code }}</td>
                                    <td>{{ $item->item->name }}</td>
                                    <td>{{ number_format($item->qty, 0, ',', '.') }} {{ $item->unit }}</td>
                                    <td>
                                        @if ($item->pivotBulkPR->isNotEmpty())
                                            Rp.
                                            {{ number_format($item->pivotBulkPR->first()->price ?? 0, 0, ',', '.') }}
                                        @else
                                            {{ 'N/A' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->pivotBulkPR->isNotEmpty())
                                            Rp.
                                            {{ number_format($item->pivotBulkPR->first()->price * $item->qty ?? 0, 0, ',', '.') }}
                                        @else
                                            {{ 'N/A' }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
        <div class="card-footer">
            {{-- <a class="btn btn-warning" href="{{ url('upload-invoice') }}">Upload Invoice</a> --}}
            <a class="btn btn-danger" href="{{ url()->previous() ? url()->previous() : url('purchase-orders') }}"
                enctype="multipart/form-data">
                Back
            </a>

            {{-- @foreach ($statuspo as $val_po)
                @if ($val_po->status == 'New')
                    <a class="btn btn-success" href="#">Ajukan</a>

                @endif
                @if ($val_po->status == 'Draft')
                    <a class="btn btn-success" href="#">Approve</a>
                    <a class="btn btn-success" href="#">Reject</a>
                @endif
                @if ($val_po->status == 'Approved')
                    <a class="btn btn-success" href="#">Payment</a>
                    <a class="btn btn-success" href="#">Print</a>
                @endif
            @endforeach --}}

        </div>
    </div>
</div>
