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
                <h2>{{ config('app.company', 'SNE') }} - ERP | Voucher Waiting List Approval</h2>
            </div>

            <div class="card-body" style="overflow-x: scroll;">
                <table class="table">
                    <thead class="border">
                    <tr class="table-secondary">
                        <th class="text-center" style="width: 5%">No</th>
                        <th style="text-align: center; width: 25%;" class="border">Voucher No</th>
                        <th style="text-align: center; width: 5%;" class="border">Item</th>
                        <th style="text-align: center; width: 17%;" class="border">Total Amount</th>
                        <th style="text-align: center; width: 17%;" class="border">Tipe Voucher</th>
                        <th style="text-align: center; width: 10%;" class="border">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($vouchers as $key => $voucher)
                        <tr>
                            <td class="text-center border">
                                {{$key+1}}
                            </td>
                            <td class="border">
                                    <span style="background-color: #ffc107; padding: 0px 5px; border-radius: 6px;">
                                        {{ $voucher->voucher_no }}
                                    </span>
                            </td>
                            <td class="border">
                                @php
                                    $additionals = json_decode($voucher->additional_informations, true)
                                @endphp
                                {{ count($voucher->voucher_details) + count($additionals) }}
                            </td>
                            <td class="border">
                                @php
                                    $getamount = 0;
                                @endphp

                                <div class="d-flex justify-content-between">
                                    @php
                                        $totalSum = 0;
                                    @endphp

                                    @foreach ($voucher->voucher_details as $key => $voucher_detail)
                                        @php
                                            $totalSum += $voucher_detail->total;
                                        @endphp
                                    @endforeach
                                    @foreach ($additionals as $key => $additional)
                                        @php
                                            $totalSum += $additional['total'];
                                        @endphp
                                    @endforeach
                                    <div>{{ rupiah_format($totalSum) }}</div>
                                </div>
                            </td>
                            <td class="border">
                                {{$voucher->type }}
                            </td>
                            <td class="border">
                                <div class="d-flex justify-content-center">
                                    <div class="w-100">
                                        <a href="{{ route('detailApproval.index', $voucher->id) }}"
                                           class="btn btn-success btn-sm">
                                            Detail Voucher
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No Data</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                @if ($consernshow)
                    @include('components.appwlist_voucher.modalrevert')
                @elseif ($consernshowmultiple)
                    @include('components.appwlist_voucher.modalrevertmultiple')
                @endif
            </div>
        </div>
    </div>
</div>
