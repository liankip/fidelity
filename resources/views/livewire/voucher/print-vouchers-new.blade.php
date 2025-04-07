@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Voucher</title>
    <style>
        body {
            font-size: 10pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            thead {
                display: table-row-group
            }

            table,
            #signature {
                page-break-inside: auto
            }

            tr,
            #signature {
                page-break-inside: avoid;
                page-break-after: auto
            }
        }
    </style>
</head>

<body>

<div>
    <div class="d-flex justify-content-between">
        <div class="d-flex" style="border-bottom: 1px solid black; margin-bottom: 20px">
            <h2 style="display: flex; justify-content: space-between">
                <p> Pengajuan Pembayaran {{ $submission->type }} Tanggal
                    {{ Carbon::parse($submission->created_at)->isoFormat(' D MMMM Y') }}</p>

                <p>Total: {{ rupiah_format($grandTotal) }}</p>
            </h2>
        </div>
        @php
            $sortedVouchers = $submission->vouchers->sortByDesc('created_at');
        @endphp
        @foreach ($sortedVouchers as $voucher)
            @php
                $additionalInformations = json_decode($voucher->additional_informations) ?? []
            @endphp
            @if ($voucher->rejected_by != null && $voucher->rejected_by != 0)
                <div class="card mt-2">
                    <div class="card-body">
                        <h5 class="text-danger">Alasan Reject</h5>
                        {{ $voucher->reason }}
                    </div>
                </div>
            @endif
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show mb-2" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <h2>Nomor
                Voucher: {{ $voucher->voucher_no }} {{ count($additionalInformations) == 0 ? '(PO)' : '(Non PO)' }}</h2>
            <div class="card mt-2 overflow-auto">
                <div class="card-body">
                    <table class="table table-borderless table-striped">
                        <thead>
                        @if (count($additionalInformations) == 0)

                            <tr class="table-primary">
                                <th class="align-middle">No</th>
                                <th class="align-middle">Faktur Pajak</th>
                                <th class="align-middle">Keterangan</th>
                                <th class="align-middle">Bank Penerima</th>
                                <th class="align-middle">Project</th>
                                <th class="align-middle">Nama Item</th>
                                <th class="align-middle">Pemohon & Penerima</th>
                                <th class="align-middle">Total Harga</th>
                            </tr>
                        @endif
                        </thead>
                        <tbody>
                        @php
                            $groupedVouchers = $voucher->voucher_details->groupBy('supplier_id');
                        @endphp

                        @foreach($groupedVouchers as $supplierId => $voucherDetails)
                            @php
                                $supplier = $voucherDetails->first()->purchase_order->supplier;
                                $totalAmount = $voucherDetails->sum('amount_to_pay');
                            @endphp
                            <tr>
                                <td colspan="8">
                                    <strong>Supplier: {{ $supplier->name }}</strong> - Total Amount:
                                    <strong style="font-size: 1.5em">
                                        {{ rupiah_format($totalAmount) }}
                                    </strong>
                                </td>
                            </tr>
                            @foreach ($voucherDetails as $key => $item)
                                <tr>
                                    <td>
                                        <div class="mb-2">
                                            {{ $key + 1 }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item['faktur_pajak'] == 1)
                                            <span>Ada</span>
                                        @elseif($item['faktur_pajak'] == 2)
                                            <span>Tidak Ada</span>
                                        @elseif($item['faktur_pajak'] == 3)
                                            <span>Belum Ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="mb-2">
                                            <div><strong>PO: {{ $item->purchase_order->po_no }}</strong></div>
                                            <div>{{ $item->purchase_order->supplier->name }}</div>
                                            <div>
                                                <em>({{ $item->purchase_order->supplier->term_of_payment }})</em>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            @isset($item->purchase_order->supplier->bank_name)
                                                <div>Bank
                                                    Penerima: {{ $item->purchase_order->supplier->bank_name }}</div>
                                            @else
                                                <div>Bank Penerima: -</div>
                                            @endisset

                                            @isset($item->purchase_order->supplier->norek)
                                                <div>No Rek: {{ $item->purchase_order->supplier->norek }}</div>
                                            @else
                                                <div>No Rek: -</div>
                                            @endisset

                                        </div>
                                    </td>
                                    <td>
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
                                    <td>
                                        <div class="mb-2">
                                            <div>
                                                Pemohon: {{ $item->purchase_order->pr->requester ?? '-' }}
                                            </div>
                                            <div style="margin-top: 5px">
                                                Penerima:

                                                @if (count($item->purchase_order->submition) > 0)
                                                    {{ $item->purchase_order->submition[0]->penerima }}
                                                @else
                                                    {{ $item->purchase_order->status_barang }}
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mt-3">
                                            {{ rupiah_format($item->amount_to_pay) }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                    @if (isset($voucher->additional_informations))
                        <table class="table table-bordered text-sm">
                            <thead class="border table-secondary">
                            <tr class="">
                                <th class="align-middle">No</th>
                                <th class="align-middle">Sudah Diketahui Direksi</th>
                                <th class="align-middle">Faktur Pajak</th>
                                <th class="align-middle">Keterangan</th>
                                <th class="align-middle">Tanggal PO Diterbitkan</th>
                                <th class="align-middle">Bank Penerima</th>
                                <th class="align-middle">Project</th>
                                <th class="align-middle">Nama Item</th>
                                <th class="align-middle">Pemohon & Penerima</th>
                                <th class="align-middle">Total Harga</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach (json_decode($voucher->additional_informations, true) as $index => $data)
                                <tr>
                                    <td>
                                        <div class="mb-2">
                                            {{ $index + 1 }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($data['is_confirm']) &check; @endif
                                    </td>
                                    <td>
                                        @if ($data['faktur_pajak'] == 1)
                                            <span>Ada</span>
                                        @elseif($data['faktur_pajak'] == 2)
                                            <span>Tidak Ada</span>
                                        @elseif($data['faktur_pajak'] == 3)
                                            <span>Belum Ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $data['keterangan'] }}
                                    </td>
                                    <td>
                                        {{ date('d F Y', strtotime($voucher->created_at)) }}
                                    </td>
                                    <td>
                                        No Rekening: {{ $data['no_rekening'] }} <br>
                                        Nama Penerima: {{ $data['bank_penerima'] }}
                                    </td>
                                    <td>
                                        {{ $data['project'] ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $data['nama_item'] }}
                                    </td>
                                    <td>
                                        {{ $data['peminta_penerima'] }}
                                    </td>
                                    <td>
                                        {{ rupiah_format($data['total']) }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
            <hr>
        @endforeach
    </div>
</div>
</body>

</html>
