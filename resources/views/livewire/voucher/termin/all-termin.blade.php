<div>
    <h2>Voucher Termin</h2>
    <hr>
    <a class="btn btn-success" href="{{ route('vouchers.index') }}">Voucher Non Termin</a>
    <div class="mt-5">
        <x-common.notification-alert />
        <div class="card mt-2">
            <div class="card-body">
                @can(\App\Permissions\Permission::PRINT_VOUCHER)
                    <livewire:voucher.print-voucher />
                @endcan
                @can(\App\Permissions\Permission::CREATE_VOUCHER)
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('vouchers.termin.create') }}" class="btn btn-primary btn-sm mb-3">Create Voucher</a>
                    </div>
                @endcan
                {{-- <livewire:voucher.list-voucher /> --}}
                <div>
                    <table class="table table-bordered text-sm">
                        <div class="">
                            <input type="text" wire:model.debounce.500ms="keyword" placeholder="Search..."
                                class="form-control mb-3" />
                        </div>
                        <tr class="table-secondary">
                            <th style="width: 5%; vertical-align: middle" class="text-center">No</th>
                            <th style="width: 30%; vertical-align: middle" class="text-center">No. Voucher</th>
                            <th style="width: 30%; vertical-align: middle" class="text-center">Total Harga</th>
                            <th style="width: 30%; vertical-align: middle" class="text-center">Status</th>
                            <th style="width: 20%; vertical-align: middle" class="text-center">Detail</th>
                        </tr>
                        @forelse($vouchers as $key => $voucher)
                            @php
                                $totalSum = 0;
                            @endphp
                            @foreach ($voucher->voucher_details as $item)
                                @php
                                    $totalSum += $item->amount_to_pay;
                                @endphp
                            @endforeach
                            <tr>
                                <td style="vertical-align: middle">{{ $key + 1 }}</td>
                                <td style="vertical-align: middle">{{ $voucher->voucher_no }}</td>
                                <td class="text-nowrap" style="vertical-align: middle">Rp.
                                    {{ number_format($totalSum, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if ($voucher->date_approved)
                                        <h6 class="text-success">Approved</h6>
                                    @else
                                        <h6 class="text-muted">
                                            Waiting for Approval
                                        </h6>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('vouchers.detail', $voucher->id) }}"
                                        class="btn btn-success btn-sm mb-2">
                                        Detail Voucher
                                    </a>
                                    @if ($voucher->date_approved)
                                        <a href="{{ route('vouchers-new.print', $voucher->id) }}"
                                            class="btn btn-info btn-sm mb-2">
                                            Print Voucher
                                        </a>
                                    @endif
                                </td>
                            </tr>
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
