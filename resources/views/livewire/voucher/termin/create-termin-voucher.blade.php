<div class="row justify-content-center">
    @if ($message = Session::get('danger'))
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
    @endif
    <!-- Modal -->
    <div class="modal fade" id="periodModal" tabindex="-1" aria-labelledby="periodModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="periodModalLabel">Choose Period</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Period Date</label>
                        <input type="date" class="form-control" wire:model="voucherPeriod">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <div>
            <h2>Voucher Termin {{ date('d F Y ') }}</h2>
            <hr>
            <a class="btn btn-primary btn-sm" href="{{ route('vouchers.termin.index') }}">Back</a>
        </div>
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary" {{ count($vouchers) <= 0 ? 'disabled' : '' }} wire:click="save">Ajukan Ke
                Direksi
            </button>
        </div>
        <div>

            <div class="mt-3 card">
                <div class="card-body">
                    <table class="table table-bordered table-responsive">
                        <thead class="table-secondary">
                            <tr class="text-center">
                                <th class="align-middle" style="width: 5%;">Action</th>
                                <th class="align-middle" style="width: 20%;">Keterangan</th>
                                <th class="align-middle" style="width: 15%;">Bank Penerima</th>
                                <th class="align-middle" style="width: 15%;">Project</th>
                                <th class="align-middle" style="width: 20%;">Nama Item</th>
                                <th class="align-middle" style="width: 10%;">Peminta & Penerima</th>
                                <th class="align-middle" style="width: 15%;">Total</th>
                                <th class="align-middle" style="width: 15%;">Total Yang Akan Dibayarkan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vouchers as $voucher)
                                @php
                                    // $voucherTotalAmount = collect($voucher['purchase_orders'])
                                    //     ->pluck('total_amount');
                                    // $this->total[] = $voucherTotalAmount;
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-danger"
                                            wire:click="removeVoucher('{{ $voucher['id'] }}')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                    <td>
                                        @foreach ($voucher['purchase_orders'] as $po)
                                            <div class="mb-2">
                                                <div class="text-success"><strong>PO: {{ $po['po_no'] }}</strong></div>
                                                <div>{{ $po['supplier']['name'] }}</div>
                                                <div><em>({{ $po['supplier']['term_of_payment'] }})</em></div>
                                            </div>
                                        @endforeach
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
                                                {{ $po['project']['name'] }}
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
                                                    Peminta: {{ $po['pr']['requester'] }}
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
                                        <input type="text" wire:model="amount_to_pay.{{ $voucher['id'] }}"
                                            placeholder="Masukkan total bayar" />
                                        <input type="hidden" wire:model.defer="po_total_amount.{{ $voucher['id'] }}"
                                            value="{{ $po['total_amount'] }}" />
                                        @error('amount_to_pay.' . $voucher['id'])
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="8">Belum ada voucher yang ditambahkan</td>
                                </tr>
                            @endforelse
                            {{-- @if (count($vouchers) > 0) --}}
                            @php $additionalFieldCollection = collect($additionalField); @endphp
                            @foreach ($additionalFieldCollection->chunk(6) as $chunk)
                                <tr>
                                    <td></td>
                                    @foreach ($chunk as $index => $field)
                                        <td><input type="text" wire:model="additionalField.{{ $index }}"
                                                placeholder="Masukkan Keterangan" /></td>
                                    @endforeach
                                </tr>
                            @endforeach
                            {{-- @endif --}}
                        </tbody>
                    </table>
                    {{-- @if (count($vouchers) > 0) --}}
                    {{-- <button class="btn btn-success" wire:click="addField">Tambah Pembayaran Lainnya</button> --}}
                    @if (count($additionalField) > 0)
                        <button class="btn btn-danger" wire:click="removeFields">Hapus Pembayaran Lainnya</button>
                    @endif
                    {{-- @endif --}}
                </div>
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
                                class="form-control mb-3" />
                        </div>
                        <tr class="table-secondary">
                            <th style="width: 5%; vertical-align: middle" class="text-center">#</th>
                            <th style="width: 20%; vertical-align: middle" class="text-center">No. Purchase Order</th>
                            <th style="width: 20%; vertical-align: middle" class="text-center">Supplier</th>
                            <th style="width: 5%; vertical-align: middle" class="text-center">Status</th>
                            <th style="width: 5%; vertical-align: middle" class="text-center">Term Of Payment</th>
                            <th style="width: 15%; vertical-align: middle" class="text-center">Project</th>
                            <th style="width: 10%; vertical-align: middle" class="text-center">Start Date</th>
                            <th style="width: 10%; vertical-align: middle" class="text-center">Due Date</th>
                            <th style="width: 15%; vertical-align: middle" class="text-center">Amount</th>
                            <th style="width: 15%; vertical-align: middle" class="text-center">Paid Amount</th>
                        </tr>
                        @forelse($data_vouchers->where('data_term_of_payment', '!=', null) as $data)
                            @php
                                $due_date = Carbon\Carbon::parse($data->date_approved)->addDays(
                                    $data->supplier->term_of_payment,
                                );

                                $status = 'Dapat ditambahkan';
                                $textColor = '';
                                if ($data->invoice_status === null) {
                                    $status = 'Belum ada Invoice';
                                    $textColor = 'text-danger';
                                } elseif($data->approvalStatus !== null && $data->approvalStatus === false){
                                    $status = 'Menunggu approval';
                                    $textColor = 'text-danger';
                                } elseif($data->invoice_status === 'Termin 2 Incomplete' || $data->invoice_status === 'Termin 3 Incomplete' ) {
                                    $status = 'Menunggu barang sampai';
                                    $textColor = 'text-danger';
                                } elseif($data->invoice_status === 'Termin 3 Incomplete Invoice'){
                                    $status = 'Harap upload invoice ke-2';
                                    $textColor = 'text-danger';
                                } else {
                                    $status = 'Dapat ditambahkan';
                                    $textColor = 'text-success';
                                }

                                if($data->terminStatus !== null && $data->terminStatus === 'Lunas'){
                                    $status = 'Lunas';
                                    $textColor = 'text-success';
                                }
                            @endphp

                            @if ($due_date < now())
                                <tr>
                                    <td style="vertical-align: middle" class="text-center">
                                        <input type="checkbox" wire:model="checked.{{ $data->po_no }}" @if($status !== 'Dapat ditambahkan')
                                            disabled
                                        @endif>
                                    </td>
                                    <td style="vertical-align: middle">{{ $data->po_no }}</td>
                                    <td style="vertical-align: middle">{{ $data->supplier->name }}</td>
                                    <td style="vertical-align: middle" class="{{ $textColor }} fw-semibold">{{ $status }}</td>
                                    <td style="vertical-align: middle" class="text-center">
                                        @if (in_array($data->data_term_of_payment, [1]))
                                            Termin 2
                                        @elseif (in_array($data->data_term_of_payment, [2]))
                                            Termin 3
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle">{{ $data->project->name }}</td>
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
                                        <div class="d-flex justify-content-between">
                                            <div>Rp.</div>
                                            <div>{{  $data->paidAmount !== null ? number_format($data->paidAmount, 0, ',', '.') : '-' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
