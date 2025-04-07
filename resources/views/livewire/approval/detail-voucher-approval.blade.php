<div>
    <div class="mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                </div>
            </div>
        </div>
        <x-common.notification-alert/>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2>{{ config('app.company', 'SNE') }} - ERP | Detail Voucher {{ $voucher->voucher_no }}</h2>
            </div>

            <div class="card-body" style="overflow-x: scroll;">
                @if ($errorMessage)
                    <div class="alert alert-danger">
                        {{ $errorMessage }}
                    </div>
                @endif
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
                    </tr>
                    @forelse($voucher->voucher_details as $data)
                        @php
                            $due_date = Carbon\Carbon::parse($data->date_approved)->addDays(
                                $data->supplier->term_of_payment,
                            );
                        @endphp
                        @if ($due_date < now())
                            <tr>
                                <td style="vertical-align: middle"
                                    class="border-bottom-0 border-top border-start border-end text-center"><input
                                            type="checkbox" wire:model="checked.{{ $data->id }}"></td>
                                <td style="vertical-align: middle">
                                    <a href="{{ route('po-detail', $data->purchase_order->id) }}">
                                        {{ $data->purchase_order->po_no }}
                                    </a>
                                </td>
                                <td style="vertical-align: middle">{{ $data->supplier->name }}</td>
                                <td style="vertical-align: middle" class="text-center">
                                    {{ $data->purchase_order->term_of_payment }}</td>
                                <td style="vertical-align: middle">{{ $data->project->name }}</td>
                                <td style="vertical-align: middle">
                                    {{ date('d-m-Y', strtotime($data->date_approved)) }}
                                </td>
                                <td style="vertical-align: middle">

                                {{ date('d-m-Y', strtotime($due_date)) }}
                                <td style="vertical-align: middle">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            @if ($voucher->is_termin)
                                                {{ rupiah_format($data->amount_to_pay) }}
                                            @else
                                                {{ rupiah_format($data->purchase_order->total_amount) }}
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                {{-- <a href="{{ route('po-detail', $data->purchase_order->id) }}">
                                    {{ $data->purchase_order->po_no }}
                                </a> --}}
                                <td class="border-top-0 border-end border-start"></td>
                                <td class="border" colspan="7">
                                    <div class="d-flex">
                                        @if (!auth()->user()->hasRole('admin_2'))
                                            {{-- <form action="{{ route('viewphoto_submition', $data->purchase_order->id) }}"
                                                method="post">
                                                @csrf
                                                @method('put')
                                                <button type="submit" class="btn btn-primary">View Photo Barang</button>
                                            </form> --}}

                                            @if (count($data->purchase_order->submition) !== 0)
                                                <a href="{{ route('viewphoto_submition', $data->purchase_order->id) }}"
                                                   class="btn btn-primary" target="_blank">View Photo Barang</a>
                                            @else
                                                <a disabled class="btn btn-secondary">Foto barang belum diupload</a>
                                            @endif


                                            <div class="p-2"></div>
                                            {{-- <form action="{{ route('viewphoto_do', $data->purchase_order->id) }}"
                                                method="post">
                                                @csrf
                                                @method('put')
                                                <button type="submit" class="btn btn-primary ">View Photo DO</button>
                                            </form> --}}
                                            @if (count($data->purchase_order->do) !== 0)
                                                <a href="{{ route('viewphoto_do', $data->purchase_order->id) }}"
                                                   class="btn btn-primary" target="_blank">View Photo DO</a>
                                            @else
                                                <a disabled class="btn btn-secondary">Foto DO belum diupload</a>
                                            @endif

                                            <div class="p-2"></div>
                                            @if (count($data->purchase_order->invoices) !== 0)
                                                <a class="btn btn-primary"
                                                   href="{{ route('viewphoto_inv', $data->purchase_order->id) }}"
                                                   target="_blank">View
                                                    Photo Invoice</a>
                                            @else
                                                <a disabled class="btn btn-secondary">Foto Invoice belum diupload</a>
                                            @endif
                                        @endif
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
                @if (isset($voucher->additional_informations))
                    @php
                        $additionalInformations = json_decode($voucher->additional_informations, true);
                    @endphp

                    <h5>Additional Payment</h5>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Keterangan</th>
                            <th>Bank Penerima</th>
                            <th>Project</th>
                            <th>Nama Item</th>
                            <th>Peminta dan Penerima</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($additionalInformations as $index => $data)
                            <tr>
                                <td style="vertical-align: middle" class="text-center">
                                    <input type="checkbox" wire:model="additionalChecked.{{ $index }}">
                                </td>
                                <td>
                                    {{ $data['keterangan'] }}
                                </td>
                                <td>
                                    {{ $data['bank_penerima'] }}
                                </td>
                                <td>
                                    {{ $data['project'] }}
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

                {{-- <form action="{{ route('approve_voucher', $voucher->id) }}" method="post">
                    @csrf
                    @method('put')
                    <button type="submit" class="btn btn-success btn-sm w-100">Approve</button>
                    <button type="button" style="font-variant-numeric: tabular-nums;"
                        wire:click='showconsern({{ $voucher->id }})' class="btn btn-danger btn-sm w-100 mt-1"
                        data-toggle="modal">Revert</button>
                </form> --}}
                <button class="btn btn-primary" wire:click="save" wire:loading.attr="disabled"
                        wire:target="checked, additionalChecked">
                    Approve
                </button>

            </div>
        </div>
    </div>
</div>
