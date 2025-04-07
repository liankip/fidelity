<div>
    <a href="{{ route('payment-submission') }}" class="third-color-sne"> <i class="fa-solid fa-chevron-left fa-xs"></i>
        Back</a>
    <h2 class="primary-color-sne">Voucher NO: {{ $voucher->voucher_no }}</h2>
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
    <div class="card mt-5 primary-box-shadow">
        <div class="card-body" style="overflow-x: scroll;">
            <table class="table table-bordered text-sm">
                <thead class="thead-light">
                    <tr class="">
                        <th class="align-middle border-top-left">No</th>
                        <th class="align-middle">Faktur Pajak</th>
                        <th class="align-middle">Keterangan</th>
                        <th class="align-middle">Tanggal PO Diterbitkan</th>
                        <th class="align-middle">No Rekening dan Nama Penerima</th>
                        <th class="align-middle">Project</th>
                        <th class="align-middle">Nama Item</th>
                        <th class="align-middle">Pemohon & Penerima</th>
                        <th class="align-middle">Total Harga</th>
                        <th class="align-middle border-top-right">Total Dibayarkan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($voucher->voucher_details as $key => $item)
                        <tr>
                            <td class="text-center border-bottom-0 border-top border-start border-end">
                                <div class="mb-2">
                                    {{ $key + 1 }}
                                </div>
                            </td>
                            <td>
                                @if ($item['faktur_pajak'] == 1)
                                    <span class="badge bg-success">Ada</span>
                                @elseif($item['faktur_pajak'] == 2)
                                    <span class="badge bg-danger">Tidak Ada</span>
                                @elseif($item['faktur_pajak'] == 3)
                                    <span class="badge bg-secondary">Belum Ada</span>
                                @endif
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
                                    {{ rupiah_format($item->amount_to_pay) }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="border-top-0 border-end border-start"></td>
                            <td class="border" colspan="8">
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
                            <td colspan="8">Tidak ada voucher PO yang ditambahkan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
