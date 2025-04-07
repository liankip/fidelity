<div>
    <div class="row justify-content-center">
        @if ($message = Session::get('danger'))
            <div class="alert alert-danger">
                <p>{{ $message }}</p>
            </div>
        @endif
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
        <div>
            <div>
                <a href="{{ route('payment-submission.voucher.index', $submission) }}" class="third-color-sne"> <i
                        class="fa-solid fa-chevron-left fa-xs"></i>
                    Back</a>
                <h2>Voucher {{ $nomorVoucher }}</h2>
                <hr>
            </div>

            <div class="d-flex justify-content-end align-items-center gap-3">
                <button class="btn btn-primary"
                    {{ count($vouchers) > 0 || count($additionalField) > 0 ? '' : 'disabled' }}
                    wire:click.prevent="save" wire:loading.attr="disabled">
                    <span wire:loading.remove>Simpan</span>
                    <span wire:loading>...</span>
                </button>
            </div>
            <div>
                <div class="mt-3 card">
                    <div class="card-body">
                        <table class="table table-bordered table-responsive">
                            <thead class="table-secondary">
                                <tr class="text-center">
                                    <th class="align-middle" style="width: 5%;">Action</th>
                                    <th class="align-middle" style="width: 30%;">Faktur Pajak</th>
                                    <th class="align-middle" style="width: 20%;">Keterangan</th>
                                    <th class="align-middle" style="width: 15%;">No Rekening dan Nama Penerima</th>
                                    <th class="align-middle" style="width: 15%;">Project</th>
                                    <th class="align-middle" style="width: 20%;">Nama Item</th>
                                    <th class="align-middle" style="width: 10%;">Pemohon & Penerima</th>
                                    <th class="align-middle" style="width: 15%;">Total</th>
                                    <th class="align-middle" style="width: 15%;">Telah Dibayar</th>
                                    <th class="align-middle" style="width: 15%;">
                                        Total Bayar
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vouchers as $voucher)
                                    <tr wire:key="{{ $voucher['id'] }}">
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-danger"
                                                wire:click="removeVoucher('{{ $voucher['id'] }}')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="flexRadioDefault{{ $voucher['id'] }}"
                                                    id="flexRadioDefault{{ $voucher['id'] }}" value="1"
                                                    wire:model="faktur_pajak.{{ $voucher['id'] }}">
                                                <label class="form-check-label"
                                                    for="flexRadioDefault{{ $voucher['id'] }}">
                                                    Ada
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="flexRadioDefault{{ $voucher['id'] }}"
                                                    id="flexRadioDefault{{ $voucher['id'] }}" value="2"
                                                    wire:model="faktur_pajak.{{ $voucher['id'] }}">
                                                <label class="form-check-label"
                                                    for="flexRadioDefault{{ $voucher['id'] }}">
                                                    Tidak Ada
                                                </label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                    name="flexRadioDefault{{ $voucher['id'] }}"
                                                    id="flexRadioDefault{{ $voucher['id'] }}" value="3"
                                                    wire:model="faktur_pajak.{{ $voucher['id'] }}">
                                                <label class="form-check-label"
                                                    for="flexRadioDefault{{ $voucher['id'] }}">
                                                    Belum Ada
                                                </label>
                                            </div>
                                            @error('faktur_pajak.' . $voucher['id'])
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            @foreach ($voucher['purchase_orders'] as $po)
                                                <div wire:key="{{ $po['id'] }}" class="mb-2">
                                                    <div class="text-success"><strong>PO: {{ $po['po_no'] }}</strong>
                                                    </div>
                                                    <div>{{ $po['supplier']['name'] }}</div>
                                                    <div><em>({{ $po['supplier']['term_of_payment'] }})</em></div>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($voucher['purchase_orders'] as $po)
                                                <div wire:key="{{ $po['id'] }}" class="mb-1">
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
                                                <p wire:key="{{ $po['id'] }}">
                                                    {{ $po['project']['name'] ?? '-' }}
                                                </p>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($voucher['purchase_orders'] as $po)
                                                <ul wire:key="{{ $po['id'] }}" style="list-style: decimal">
                                                    @foreach ($po['podetail'] as $pod)
                                                        <li wire:key="{{ $pod['id'] }}">
                                                            {{ $pod['item']['name'] }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($voucher['purchase_orders'] as $po)
                                                <div wire:key="{{ $po['id'] }}" class="mb-2">
                                                    <div>
                                                        Peminta: {{ $po['pr']['requester'] ?? '' }}
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
                                                <div wire:key="{{ $po['id'] }}" class="mt-3">
                                                    {{ rupiah_format($po['total_amount']) }}
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($voucher['purchase_orders'] as $po)
                                                <div wire:key="{{ $po['id'] }}" class="mt-3">
                                                    @if (isset($po['total_paid_amount']))
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
                                                @if (isset($isLunas[$voucher['id']]) && $isLunas[$voucher['id']]) disabled @endif />
                                            <br>
                                            <input type="checkbox" wire:model="isLunas.{{ $voucher['id'] }}"> Lunas
                                            <br>
                                            @error('amount.' . $voucher['id'])
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="8">Belum ada voucher yang ditambahkan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-5">
                <h5>Choose Purchase Order</h5>
                <div class="d-flex alert alert-container alert-warning my-2">
                    <p class="ms-4">
                        Purchase Order yang dapat dipilih hanya yang memiliki status barang diterima dan sudah jatuh
                        tempo.
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
                                <th style="width: 20%; vertical-align: middle" class="text-center">No. Purchase Order
                                </th>
                                <th style="width: 20%; vertical-align: middle" class="text-center">Supplier</th>
                                <th style="width: 5%; vertical-align: middle" class="text-center">Term Of Payment</th>
                                <th style="width: 15%; vertical-align: middle" class="text-center">Project</th>
                                <th style="width: 10%; vertical-align: middle" class="text-center">Start Date</th>
                                <th style="width: 10%; vertical-align: middle" class="text-center">Due Date</th>
                                <th style="width: 15%; vertical-align: middle" class="text-center">Amount</th>
                                <th style="width: 15%; vertical-align: middle" class="text-center">Paid Amount</th>
                            </tr>
                            @forelse($data_vouchers as $data)
                                @php
                                    $due_date = Carbon\Carbon::parse($data->date_approved)->addDays(
                                        $data->supplier->term_of_payment,
                                    );

                                    $bulkStatus = $data->podetail->every(fn($item) => $item->is_bulk == 1);
                                @endphp
                                @if ($due_date < now())
                                    <tr
                                        class="{{ $data->hasInvoice() && !$data->incomplete_invoice && !$data->incomplete_approval ? '' : 'table-danger' }} {{ (strtolower($data->term_of_payment) === 'cod' || strtolower($data->term_of_payment) === '30 hari') && $data->status_barang !== 'Arrived' ? 'table-danger' : '' }}">
                                        @if (!$data->hasInvoice())
                                            @if (
                                                (strtolower($data->term_of_payment) == 'cod' || strtolower($data->term_of_payment) == '30 hari') &&
                                                    $data->status_barang != 'Arrived')
                                                <td>Tidak ada invoice dan foto barang</td>
                                            @else
                                                <td>
                                                    Tidak ada invoice
                                                </td>
                                            @endif
                                        @else
                                            @if (
                                                (strtolower($data->term_of_payment) === 'cod' || strtolower($data->term_of_payment) === '30 hari') &&
                                                    $data->status_barang !== 'Arrived')
                                                <td>
                                                    <span>Foto barang tidak ada</span>
                                                </td>
                                            @else
                                                @if ($data->incomplete_invoice)
                                                    <td>{{ $data->incomplete_invoice }}</td>
                                                @elseif($data->incomplete_approval)
                                                    <td>{{ $data->incomplete_approval }}</td>
                                                @else
                                                    <td style="vertical-align: middle" class="text-center">
                                                        <input type="checkbox"
                                                            wire:model="checked.{{ $data->po_no }}">
                                                    </td>
                                                @endif
                                            @endif
                                        @endif
                                        <td style="vertical-align: middle">{{ $data->po_no }}
                                            @if ($bulkStatus)
                                                <span class="badge badge-success">Bulk PO</span>
                                            @endif
                                        </td>
                                        <td style="vertical-align: middle">{{ $data->supplier->name }}</td>
                                        <td style="vertical-align: middle" class="text-center">
                                            {{ $data->term_of_payment }}
                                        </td>
                                        <td style="vertical-align: middle">{{ $data->project?->name }}</td>
                                        <td style="vertical-align: middle">
                                            {{ date('d-m-Y', strtotime($data->date_approved)) }}</td>
                                        <td style="vertical-align: middle">{{ date('d-m-Y', strtotime($due_date)) }}
                                        </td>
                                        <td style="vertical-align: middle">
                                            <div class="d-flex justify-content-between">
                                                <div>Rp.</div>
                                                <div>
                                                    {{ number_format($data->total_amount, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td style="vertical-align: middle">
                                            Rp.
                                            {{ number_format($data->total_paid_amount ? $data->total_paid_amount : 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No data available</td>
                                </tr>
                            @endforelse
                        </table>
                        {{ $data_vouchers->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
