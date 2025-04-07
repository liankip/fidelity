@php use Carbon\Carbon; @endphp
<div>
    <div class="d-flex justify-content-between">
        <div class="d-flex">
            <a href="{{ route('payment-submission.voucher.index', $submission->id) }}"
               class="btn btn-sm btn-danger my-auto">
                <i class="fa-solid fa-angle-left"></i>
            </a>
            <h2 class="my-auto">Voucher NO: {{ $voucher->voucher_no }}</h2>
        </div>
    </div>
    <hr>
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="d-flex justify-content-end align-items-center gap-3 mb-3">
        <div class="d-flex">
            <button class="btn btn-primary" wire:click.prevent="update({{ $voucher->id }})"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>Simpan</span>
                <span wire:loading>...</span>
            </button>
        </div>
    </div>
    <div class="card">
        <div class="card-body" style="overflow-x: scroll;">
            <table class="table table-bordered text-sm">
                <thead class="border table-secondary">
                <tr class="">
                    <th class="align-middle">#</th>
                    <th class="align-middle">Faktur Pajak</th>
                    <th class="align-middle">Keterangan</th>
                    <th class="align-middle">Tanggal PO Diterbitkan</th>
                    <th class="align-middle">No Rekening dan Nama Penerima</th>
                    <th class="align-middle">Project</th>
                    <th class="align-middle">Nama Item</th>
                    <th class="align-middle">Pemohon & Penerima</th>
                    <th class="align-middle">Total Harga</th>
                    <th class="align-middle">Telah Dibayar</th>
                    <th class="align-middle">Total Dibayarkan</th>
                </tr>
                </thead>
                <tbody>
                @forelse($voucherDetails as $key => $item)
                    <tr>
                        <td>
                            <button class="btn btn-sm btn-outline-danger"
                                    wire:click="deleteVoucher('{{ $item['id'] }}')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                        <td wire:ignore>
                            <select style="width: 100px" class="form-control"
                                    wire:model="faktur_pajak.{{ $item->id }}">
                                <option value="1">Ada</option>
                                <option value="2">Tidak Ada</option>
                                <option value="3">Belum Ada</option>
                            </select>
                            @error('faktur_pajak.' . $item->id)
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </td>
                        <td class="border">
                            <div class="mb-2">
                                <div><strong>PO: <a
                                            href="{{ route('po-detail', $item->purchase_order->id) }}">{{ $item->purchase_order->po_no }}</a></strong>
                                </div>
                                <div>{{ $item->purchase_order->supplier->name }}</div>
                                <div>
                                    <em>({{ $item->purchase_order->supplier->term_of_payment }})</em>
                                </div>
                            </div>
                        </td>
                        <td class="border">
                            @if ($item->purchase_order->date_approved_2)
                                {{ date('d F Y', strtotime($item->purchase_order->date_approved_2)) }}
                            @elseif ($item->purchase_order->approved_at)
                                {{ date('d F Y', strtotime($item->purchase_order->approved_at)) }}
                            @endif
                        </td>
                        <td class="border">
                            <div class="mb-1">
                                @isset($item->purchase_order->supplier->bank_name)
                                    <div>Bank: {{ $item->purchase_order->supplier->bank_name }}</div>
                                @else
                                    <div>Bank: -</div>
                                @endisset

                                @isset($item->purchase_order->supplier->norek)
                                    <div>No Rek: {{ $item->purchase_order->supplier->norek }}</div>
                                    <div>{{ $item->purchase_order->supplier->name }}</div>
                                @else
                                    <div>No Rek: -</div>
                                @endisset
                            </div>
                        </td>
                        <td class="border">
                            <p>
                                {{ $item->purchase_order->project->name ?? '-' }}
                            </p>
                        </td>
                        <td>
                            <ul>
                                @foreach ($item->purchase_order->podetail as $pod)
                                    <li>
                                        {{ $pod->item->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="border">
                            <div class="mb-2">
                                <div>
                                    Permintaan: {{ $item->purchase_order->pr->requester ?? '-' }}
                                    ({{ date('d F Y', strtotime($item->purchase_order->date_request)) }})
                                </div>
                                <div style="margin-top: 5px">
                                    Penerima:
                                    @if (count($item->purchase_order->submition) > 0)
                                        {{ $item->purchase_order->submition[0]->penerima }}
                                        ({{ date('d F Y', strtotime($item->purchase_order->submition[0]->created_at)) }}
                                        )
                                    @else
                                        {{ $item->purchase_order->status_barang }}
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border">
                            <div class="mt-3">
                                {{ rupiah_format($item->purchase_order->total_amount) }}
                            </div>
                        </td>
                        <td>
                            <div class="mt-3">
                                @if(isset($item->total_paid_amount))
                                    {{ rupiah_format($item->total_paid_amount) }}
                                @else
                                    {{ rupiah_format(0) }}
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="mt-3">
                                <input type="text" wire:model="amount_to_pay.{{ $item->id }}"
                                       placeholder="Masukkan total bayar"/>
                                <br>
                                @error('amount_to_pay.' . $item->id)
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-top-0 border-end border-start"></td>
                        <td class="border" colspan="9">
                            <div class="d-flex">
                                @if (!auth()->user()->hasRole('admin_2'))
                                    <a href="{{ route('viewphoto_submition', $item->purchase_order->id) }}"
                                       class="btn btn-primary" target="_blank">View Photo Barang</a>
                                    <div class="p-2"></div>
                                    <a href="{{ route('viewphoto_do', $item->purchase_order->id) }}"
                                       class="btn btn-primary" target="_blank">View Photo DO</a>
                                    <div class="p-2"></div>
                                    <a class="btn btn-primary"
                                       href="{{ route('viewphoto_inv', $item->purchase_order->id) }}"
                                       target="_blank">View
                                        Photo Invoice</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="11">Tidak ada voucher PO yang ditambahkan</td>
                    </tr>
                @endforelse

                @foreach ($newVouchers as $voucher)
                    <tr>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-danger"
                                    wire:click="removeVoucher('{{ $voucher['id'] }}')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="1"
                                       wire:model="faktur_pajak_new.{{ $voucher['id'] }}">
                                <label class="form-check-label" for="flexRadioDefault{{ $voucher['id'] }}">
                                    Ada
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="2"
                                       wire:model="faktur_pajak_new.{{ $voucher['id'] }}">
                                <label class="form-check-label" for="flexRadioDefault{{ $voucher['id'] }}">
                                    Tidak Ada
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="3"
                                       wire:model="faktur_pajak_new.{{ $voucher['id'] }}">
                                <label class="form-check-label" for="flexRadioDefault{{ $voucher['id'] }}">
                                    Belum Ada
                                </label>
                            </div>
                            @if ($errors->has("faktur_pajak_new." . $voucher['id']))
                                <span class="text-danger">{{ $errors->first("faktur_pajak_new." . $voucher['id']) }}</span>
                            @endif
                        </td>
                        <td>
                            @foreach ($voucher['purchase_orders'] as $po)
                                <div class="mb-2">
                                    <div>
                                        <strong>PO: <a
                                                href="{{ route('po-detail', $po['po_no']) }}">{{ $po['po_no'] }}</a></strong>
                                    </div>
                                    <div>
                                        {{ $po['supplier']['name'] }}
                                    </div>
                                    <div>
                                        <em>({{ $po['supplier']['term_of_payment'] }})</em>
                                    </div>
                                </div>
                            @endforeach
                        </td>
                        <td class="border">
                            @if ($item->purchase_order->date_approved_2)
                                {{ date('d F Y', strtotime($item->purchase_order->date_approved_2)) }}
                            @elseif ($item->purchase_order->approved_at)
                                {{ date('d F Y', strtotime($item->purchase_order->approved_at)) }}
                            @endif
                        </td>
                        <td>
                            @foreach ($voucher['purchase_orders'] as $po)
                                <div class="mb-1">
                                    @isset($po['supplier']['bank_name'])
                                        <div>Bank: {{ $po['supplier']['bank_name'] }}</div>
                                    @else
                                        <div>Bank: -</div>
                                    @endisset

                                    @isset($po['supplier']['norek'])
                                        <div>No Rek: {{ $po['supplier']['norek'] }}</div>
                                        <div>Name: {{ $po['supplier']['name'] }}</div>
                                    @else
                                        <div>No Rek: -</div>
                                    @endisset
                                </div>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($voucher['purchase_orders'] as $po)
                                <p>
                                    {{ $po['project']['name'] }} ?? '-'
                                </p>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($voucher['purchase_orders'] as $po)
                                <ul style="list-style: decimal">
                                    @foreach ($po['podetail'] as $pod)
                                        <li>
                                            {{ $pod['item']['name'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($voucher['purchase_orders'] as $po)
                                <div class="mb-2">
                                    <div>
                                        Pemohon: {{ $po['pr']['requester'] }}
                                    </div>
                                    <div style="margin-top: 5px">
                                        Penerima:

                                        @if (count($po['submition']) > 0)
                                            {{ $po['submition'][0]['penerima'] }}
                                        @else
                                            {{ $po['status_barang'] }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($voucher['purchase_orders'] as $po)
                                <div class="mt-3">
                                    {{ rupiah_format($po['total_amount']) }}
                                </div>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($voucher['purchase_orders'] as $po)
                                <div>
                                    @if(isset($po['total_paid_amount']))
                                        {{ rupiah_format($po['total_paid_amount']) }}
                                    @else
                                        {{ rupiah_format(0) }}
                                    @endif
                                </div>
                            @endforeach
                        </td>
                        <td>
                            <input type="text" wire:model="amount.{{ $voucher['id'] }}"
                                   placeholder="Masukkan total bayar"
                            />
                            <br>
                            @error('amount.' . $voucher['id'])
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">
        <h5>Choose Purchase Order</h5>
        <div class="d-flex alert alert-container alert-warning my-2">
            <p class="ms-4">
                Purchase Order yang dapat dipilih hanya yang memiliki status barang diterima dan sudah jatuh tempo.
            </p>
        </div>
        <div class="card mt-1">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex mb-2">
                        <div class="me-2">PO Selected :</div>
                        <div class="d-flex-wrap gap-2 ">
                            @forelse($checked as $key => $val)
                                @if ($val)
                                    <div class="badge bg-primary">{{ $key }}</div>
                                @endif
                            @empty
                                -
                            @endforelse
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mb-2">
                        <button class="btn btn-primary" wire:click="addVoucher"
                            {{ count($checked) <= 0 ? 'disabled' : '' }}>
                            Tambah
                        </button>
                    </div>
                </div>

                <table class="table table-bordered text-sm">
                    <div class="">
                        <input type="text" wire:model.debounce.500ms="keyword" placeholder="Search..."
                               class="form-control mb-3"/>
                    </div>
                    <tr class="table-secondary">
                        <th style="width: 5%; vertical-align: middle" class="text-center">#</th>
                        <th style="width: 20%; vertical-align: middle" class="text-center">No. Purchase Order</th>
                        <th style="width: 20%; vertical-align: middle" class="text-center">Supplier</th>
                        <th style="width: 5%; vertical-align: middle" class="text-center">Term Of Payment</th>
                        <th style="width: 15%; vertical-align: middle" class="text-center">Project</th>
                        <th style="width: 10%; vertical-align: middle" class="text-center">Start Date</th>
                        <th style="width: 10%; vertical-align: middle" class="text-center">Due Date</th>
                        <th style="width: 15%; vertical-align: middle" class="text-center">Amount</th>
                        <th style="width: 15%; vertical-align: middle" class="text-center">Paid Amount</th>
                    </tr>
                    @forelse($purchaseOrder as $data)
                        @php
                            $due_date = Carbon::parse($data->date_approved)->addDays($data->supplier->term_of_payment);
                        @endphp
                        @if ($due_date < now())
                            <tr class="{{ $data->hasInvoice() && !$data->incomplete_invoice && !$data->incomplete_approval ? '' : 'table-danger' }}">
                                @if (!$data->hasInvoice())
                                    @if (strtolower($data->term_of_payment) == 'cod' ||
                                            (strtolower($data->term_of_payment) == '30 Hari' && $data->status_barang != 'Arrived'))
                                        <td>Tidak ada invoice dan foto barang</td>
                                    @else
                                        <td>
                                            Tidak ada invoice
                                        </td>
                                    @endif
                                @else
                                    @if ((strtolower($data->term_of_payment) === 'cod' || strtolower($data->term_of_payment) === '30 hari') && $data->status_barang !== 'Arrived')
                                        <td>
                                            <span>Foto barang tidak ada</span>
                                        </td>
                                    @else
                                        @if($data->incomplete_invoice)
                                            <td>{{ $data->incomplete_invoice }}</td>
                                        @elseif($data->incomplete_approval)
                                            <td>{{ $data->incomplete_approval }}</td>
                                        @else
                                            <td style="vertical-align: middle" class="text-center">
                                                <input type="checkbox" wire:model="checked.{{ $data->po_no }}">
                                            </td>
                                        @endif
                                    @endif
                                @endif
                                <td style="vertical-align: middle">{{ $data->po_no }}</td>
                                <td style="vertical-align: middle">{{ $data->supplier->name }}</td>
                                <td style="vertical-align: middle" class="text-center">
                                    {{ $data->term_of_payment }}
                                </td>
                                <td style="vertical-align: middle">{{ $data->project->name ?? '-' }}</td>
                                <td style="vertical-align: middle">
                                    {{ date('d-m-Y', strtotime($data->date_approved)) }}</td>
                                <td style="vertical-align: middle">{{ date('d-m-Y', strtotime($due_date)) }}</td>
                                <td style="vertical-align: middle">
                                    <div class="d-flex justify-content-between">
                                        <div>Rp.</div>
                                        <div>{{ number_format($data->total_amount, 0, ',', '.') }}</div>
                                    </div>
                                </td>
                                <td style="vertical-align: middle">
                                    Rp. {{ number_format($data->total_paid_amount ? $data->total_paid_amount : 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No data available</td>
                        </tr>
                    @endforelse
                </table>
                {{ $purchaseOrder->links() }}
            </div>
        </div>
    </div>
</div>
