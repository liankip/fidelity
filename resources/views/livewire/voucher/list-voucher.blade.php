@php @endphp
<div>
    <table class="table primary-box-shadow">
        <div class="">
            <input type="text" wire:model.debounce.500ms="keyword" placeholder="Search..." class="form-control mb-3" />
        </div>
        <tr class="thead-light">
            <th style="width: 5%; vertical-align: middle" class="text-center border-top-left">No</th>
            <th style="width: 30%; vertical-align: middle" class="text-center">No. Voucher</th>
            <th style="width: 30%; vertical-align: middle" class="text-center">Tipe</th>
            <th style="width: 30%; vertical-align: middle" class="text-center">Total Harga</th>
            <th style="width: 30%; vertical-align: middle" class="text-center">Status</th>
            <th style="width: 20%; vertical-align: middle" class="text-center border-top-right">Detail</th>
        </tr>
        @php
            $grandTotal = 0;
        @endphp
        @forelse($vouchers as $key => $voucher)
            @php
                $totalSum = 0;

                $additionalInformations = json_decode($voucher->additional_informations, true) ?? [];

                if ($additionalInformations) {
                    foreach ($additionalInformations as $additionalInformation) {
                        if (isset($additionalInformation['total']) && is_numeric($additionalInformation['total'])) {
                            $totalSum += $additionalInformation['total'];
                        }
                    }
                }
            @endphp
            @foreach ($voucher->voucher_details as $item)
                @php
                    $totalSum += $item->amount_to_pay;
                @endphp
            @endforeach
            @php
                $grandTotal += $totalSum;
            @endphp
            <tr>
                <td class="text-center" style="vertical-align: middle">{{ $key + 1 }}</td>
                <td class="text-center" style="vertical-align: middle">{{ $voucher->voucher_no }}<br>
                    @php
                        $supplierNames = [];
                        foreach ($voucher->voucher_details as $detail) {
                            $supplierNames[$detail->supplier->name ?? 'No Supplier'] = true;
                        }
                        $supplierList = implode(', ', array_keys($supplierNames));
                    @endphp

                    {{ $supplierList }}
                </td>
                <td class="text-center" style="vertical-align: middle">
                    {{ count($additionalInformations) == 0 ? 'PO' : 'Non PO' }}</td>
                <td class="text-nowrap text-end" style="vertical-align: middle">{{ rupiah_format($totalSum) }}
                </td>
                <td class="text-center align-middle">
                    <h6
                        class="{{ $voucher->payment_submission->status !== 'Approved' ? 'text-muted' : 'text-success' }}">
                        {{ $voucher->payment_submission->status }}</h6>
                </td>
                <td class="text-center">
                    @if (count($additionalInformations) == 0)
                        <a href="{{ route('payment-submission.voucher.detail', ['submission' => $submission->id, 'voucher' => $voucher->id]) }}"
                            class="btn btn-success btn-sm mb-2">
                            Detail Voucher
                        </a>
                    @else
                        <a href="{{ route('payment-submission.additional.detail', ['submission' => $submission->id, 'voucher' => $voucher->id]) }}"
                            class="btn btn-success btn-sm mb-2">
                            Detail Voucher
                        </a>
                    @endif
                    @if ($voucher->payment_submission->status == 'Draft')
                        @if (count($additionalInformations) == 0)
                            <a class="btn btn-primary btn-sm mb-2"
                                href="{{ route('payment-submission.voucher.edit', ['submission' => $submission->id, 'voucher' => $voucher->id]) }}">
                                Edit
                            </a>
                        @else
                            <a class="btn btn-primary btn-sm mb-2"
                                href="{{ route('payment-submission.additional.edit', ['submission' => $submission->id, 'voucher' => $voucher->id]) }}">
                                Edit
                            </a>
                        @endif
                    @endif
                </td>
            </tr>
            @if ($submission->status == 'Approved')
                @foreach ($voucher->voucher_details as $detail)
                    <tr>
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
            @endif
        @empty
            <tr>
                <td colspan="7" class="text-center">No data available</td>
            </tr>
        @endforelse
        <tr>
            <td class="text-center" style="vertical-align: middle" colspan="3">
                <h5>Total:</h5>
            </td>
            <td class="text-center" style="vertical-align: middle" colspan="3">
                <h5>{{ rupiah_format($grandTotal) }}</h5>
            </td>
        </tr>
    </table>
</div>
