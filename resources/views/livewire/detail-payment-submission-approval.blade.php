<div>
    <h2>
        <a href="{{ route('payment-submission') }}" class=" btn btn-sm btn-danger my-auto">
            <i class="fa-solid fa-angle-left"></i>
        </a>
        Pengajuan Pembayaran {{ $dataVoucher[0]->payment_submission->type }}
        Tanggal {{ Carbon\Carbon::parse($dataVoucher[0]->payment_submission->created_at)->isoFormat(' D MMMM Y') }}
    </h2>
    @if (session('fail'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <p>{{ session('fail') }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body" style="overflow-x: scroll;">
            <h3 class="text-success text-end" wire:ignore>Total: {{ rupiah_format($grandTotal) }}</h3>
            @foreach($dataVoucher as $voucher)
                <table class="table table-bordered text-sm">
                    @php
                        $additionalInformations = json_decode($voucher->additional_informations, true) ?? [];
                    @endphp
                    @if (count($additionalInformations) == 0)
                        <tr class="table-secondary">
                            <th style="width: 5%; vertical-align: middle" class="text-center">#</th>
                            <th style="width: 20%; vertical-align: middle" class="text-center">No. Purchase Order</th>
                            <th class="align-middle">Faktur Pajak</th>
                            <th style="width: 20%; vertical-align: middle" class="text-center">Supplier</th>
                            <th style="width: 5%; vertical-align: middle" class="text-center">Term Of Payment</th>
                            <th style="width: 15%; vertical-align: middle" class="text-center">Project</th>
                            {{-- <th style="width: 10%; vertical-align: middle" class="text-center">Start Date</th>
                            <th style="width: 10%; vertical-align: middle" class="text-center">Due Date</th> --}}
                            <th style="width: 15%; vertical-align: middle" class="text-center">Amount</th>
                        </tr>
                    @endif

                    <h4 class="bg-secondary text-white p-4">Voucher
                        No. {{ $voucher->voucher_no }} {{ count($additionalInformations) == 0 ? '(PO)' : '(Non PO)' }}</h4>
                    @foreach ($voucher->voucher_details as $data)
                        {{-- @php
                            $due_date = Carbon\Carbon::parse($data->date_approved)->addDays(
                                $data->supplier->term_of_payment,
                            );
                        @endphp --}}
                        {{-- @if ($due_date < now()) --}}
                            <tr>
                                <td style="vertical-align: middle"
                                    class="border-bottom-0 border-top border-start border-end text-center">
                                    <input type="checkbox" wire:model="checked.{{ $data->id }}">
                                </td>
                                <td style="vertical-align: middle">
                                    <a href="{{ route('po-detail', $data->purchase_order->id) }}">
                                        {{ $data->purchase_order->po_no }}
                                    </a>
                                </td>
                                <td style="vertical-align: middle">
                                    @if ($data['faktur_pajak'] == 1)
                                        <span class="badge bg-success">Ada</span>
                                    @elseif($data['faktur_pajak'] == 2)
                                        <span class="badge bg-danger">Tidak Ada</span>
                                    @elseif($data['faktur_pajak'] == 3)
                                        <span class="badge bg-secondary">Belum Ada</span>
                                    @endif
                                </td>
                                <td style="vertical-align: middle">{{ $data->supplier->name }}</td>
                                <td style="vertical-align: middle" class="text-center">
                                    {{ $data->purchase_order->term_of_payment }}</td>
                                <td style="vertical-align: middle">{{ $data->project->name ?? '-' }}</td>
                                {{-- <td style="vertical-align: middle">
                                    {{ date('d-m-Y', strtotime($data->date_approved)) }}
                                </td>
                                <td style="vertical-align: middle">

                                {{ date('d-m-Y', strtotime($due_date)) }}
                                </td> --}}
                                <td style="vertical-align: middle">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            {{ rupiah_format($data->amount_to_pay) }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-top-0 border-end border-start"></td>
                                <td class="border" colspan="7">
                                    <div class="d-flex">
                                        @if (!auth()->user()->hasRole('admin_2'))
                                            @if (count($data->purchase_order->submition) !== 0)
                                                <a href="{{ route('viewphoto_submition', $data->purchase_order->id) }}"
                                                   class="btn btn-primary" target="_blank">View Photo
                                                    Barang</a>
                                            @else
                                                <a disabled class="btn btn-secondary">Foto barang belum
                                                    diupload</a>
                                            @endif

                                            <div class="p-2"></div>
                                            @if (count($data->purchase_order->do) !== 0)
                                                <a href="{{ route('viewphoto_do', $data->purchase_order->id) }}"
                                                   class="btn btn-primary" target="_blank">View Photo
                                                    DO</a>
                                            @else
                                                <a disabled class="btn btn-secondary">Foto DO belum
                                                    diupload</a>
                                            @endif

                                            <div class="p-2"></div>
                                            @if (count($data->purchase_order->invoices) !== 0)
                                                <a class="btn btn-primary"
                                                   href="{{ route('viewphoto_inv', $data->purchase_order->id) }}"
                                                   target="_blank">View
                                                    Photo Invoice</a>
                                            @else
                                                <a disabled class="btn btn-secondary">Foto Invoice belum
                                                    diupload</a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        {{-- @endif --}}
                    @endforeach
                </table>

                <table class="table table-bordered text-sm">
                    @if (count($additionalInformations) > 0)
                        <tr class="table-secondary">
                            <th style="width: 5%; vertical-align: middle" class="text-center">#</th>
                            <th class="align-middle">Sudah Diketahui Direksi</th>
                            <th class="align-middle">Faktur Pajak</th>
                            <th style="width: 20%; vertical-align: middle" class="text-center">Keterangan</th>
                            <th class="align-middle">Tanggal PO Diterbitkan</th>
                            <th style="width: 20%; vertical-align: middle" class="text-center">No Rekening dan Nama
                                Penerima
                            </th>
                            <th style="width: 5%; vertical-align: middle" class="text-center">Project</th>
                            <th style="width: 10%; vertical-align: middle" class="text-center">Nama Item</th>
                            <th style="width: 10%; vertical-align: middle" class="text-center">Peminta dan Penerima</th>
                            <th style="width: 15%; vertical-align: middle" class="text-center">Total</th>
                        </tr>
                    @endif

                    @if (isset($voucher->additional_informations))
                        @php
                            $additionalInformations = json_decode($voucher->additional_informations, true) ?? [];
                        @endphp
                        @foreach ($additionalInformations as $index => $data)
                            <tr class="align-middle text-center">
                                <td>
                                    <input type="checkbox"
                                           wire:model="additionalChecked.{{ $voucher->id }}.{{ $index }}">
                                </td>
                                <td>
                                    <i @if ($data['is_confirm']) class="fas fa-check-square text-success fs-5" @endif></i>
                                </td>
                                <td>
                                    @if ($data['faktur_pajak'] == 1)
                                        <span class="badge bg-success">Ada</span>
                                    @elseif($data['faktur_pajak'] == 2)
                                        <span class="badge bg-danger">Tidak Ada</span>
                                    @elseif($data['faktur_pajak'] == 3)
                                        <span class="badge bg-secondary">Belum Ada</span>
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
                    @endif
                </table>
            @endforeach

            <button class="btn btn-primary" wire:loading.attr="disabled" wire:click="save"
                    wire:target="checked, additionalChecked" type="button">
                Approve
            </button>

        </div>
    </div>

    @if(count($uncheckedVoucherNo) > 0 && $showModal)
        <!-- Modal -->
        <div class="fixed-top bg-black h-100 d-flex justify-content-center align-items-center bg-opacity-25">
            <div class="w-25 min-h-25 bg-white rounded p-4">
                <p class="text-center fw-normal">Voucher dengan nomor berikut tidak memiliki data yang di approve dan
                    akan dihapus. Lanjutkan?</p>
                @foreach ($uncheckedVoucherNo as $voucherNo)
                    <li class="fw-semibold">{{ $voucherNo }}</li>
                @endforeach

                <div class="d-flex justify-content-around mt-5">
                    <button class="btn btn-secondary w-50" wire:click="closeModal" type="button">Tidak</button>
                    <button class="btn btn-danger w-50" type="button" wire:click="proceed">Ya</button>
                </div>
            </div>
        </div>
    @endif

</div>
