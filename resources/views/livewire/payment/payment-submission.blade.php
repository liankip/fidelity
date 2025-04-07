@php use Carbon\Carbon; @endphp
<div>
    <h2 class="primary-color-sne">Payment Submission</h2>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <p>{{ session('success') }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('danger'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <p>{{ session('danger') }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mt-5 primary-box-shadow">
        <div class="card mt-2 px-2">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        <i class="fa-solid fa-plus"></i> Create Payment Submission
                    </button>
                </div>
            </div>

            <input type="text" wire:model.debounce.500ms="search" class="form-control mb-3"
                placeholder="Search Item Name">

            <!-- Loading Spinner -->
            <div class="mx-auto d-flex align-items-center justify-content-center gap-3 mb-3 d-none" wire:loading
                wire:target="search" wire:loading.class.remove="d-none">
                <span>Loading...</span>
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <table class="table table-hover primary-box-shadow">
                <thead class="thead-light">
                    <th class="border-top-left text-center">No</th>
                    <th class="text-center">Pengajuan</th>
                    <th class="text-center">Tipe</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Total</th>
                    <th class="border-top-right text-center">Aksi</th>
                </thead>

                <tbody>
                    @forelse($paymentSubmitionList as $data)
                        @php
                            $textColor = $data->status === 'Approved' ? 'text-success fw-semibold' : '';
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">Pengajuan Pembayaran {{ $data->type }}
                                Tanggal {{ Carbon::parse($data->created_at)->isoFormat(' D MMMM Y') }}</td>
                            <td class="text-center">{{ $data->type }}</td>
                            <td class="{{ $textColor }}">{{ $data->status }}</td>
                            <td class="text-center">{{ rupiah_format($data->totalSum) }}</td>
                            <td class="text-center">
                                <button class="btn btn-success btn-sm"
                                    wire:click="detailPaymentSubmission({{ $data->id }})" type="button">Detail
                                </button>
                                @if ($data->status == 'Draft')
                                    <button class="btn btn-primary btn-sm"
                                        {{ $data->vouchers->count() == 0 ? 'disabled' : '' }}
                                        wire:click.prevent="propose({{ $data->id }})">
                                        Ajukan ke direksi
                                    </button>
                                @endif
                                @if ($data->status == 'Approved')
                                    <a href="{{ route('payment-submission.print', $data->id) }}" target="_blank"
                                        class="btn btn-info btn-sm">
                                        Print Pengajuan
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @if ($search !== '')
                            @foreach ($data->vouchers as $voucher)
                                <tr class="bg-light">
                                    <td></td>
                                    <td colspan="5" class="text-center aligh-middle"><strong>Voucher No</strong>:
                                        {{ $voucher->voucher_no }}</td>
                                </tr>
                                @foreach ($voucher->voucher_details as $detail)
                                    <tr class="bg-light">
                                        <td></td>
                                        <td> <strong>Nomor PO</strong>: <a
                                                href="{{ route('po-detail', $detail->purchase_order->id) }}">
                                                {{ $detail->purchase_order->po_no }}</a></td>
                                        <td colspan="4" class="align-middle">
                                            <strong>List Item : </strong>
                                            @php
                                                $poData = $detail->purchase_order;
                                            @endphp

                                            @foreach ($poData->podetail as $podetail)
                                                <li>
                                                    {{ $podetail->item->name }}
                                                </li>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No Data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($search === '')
                <div class="mt-4 d-flex justify-content-end">
                    {{ $paymentSubmitionList->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <form class="modal-content" wire:submit.prevent="submitFunction">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Purchase Submission</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Pilih tipe voucher</h6>
                    <div class="d-flex justify-content-start">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="Project" wire:model="radioValue"
                                name="type">
                            <label class="form-check-label" for="flexRadioDefault1">
                                Project
                            </label>
                        </div>

                        <div class="form-check" style="margin-left: 10px">
                            <input class="form-check-input" type="radio" value="Retail" wire:model="radioValue"
                                name="type">
                            <label class="form-check-label" for="flexRadioDefault2">
                                Retail
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="Submit" class="btn btn-primary" @if ($radioValue === null) disabled @endif
                        wire:loading.attr="disabled" wire:target="submitFunction">Save changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
