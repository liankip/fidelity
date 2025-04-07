<div>
    @foreach (['danger', 'warning', 'success', 'info'] as $key)
        @if (Session::has($key))
            <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                {{ Session::get($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endforeach

    <div class="card">
        <div class="card-body">
            <div wire:poll.keep-alive>
                <div class="table-responsive primary-box-shadow">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th class="align-middle text-center border-top-left">No</th>
                                <th class="align-middle text-center">Office</th>
                                <th class="align-middle text-center">Purchase Name</th>
                                <th class="align-middle text-center">Notes</th>
                                <th class="align-middle text-center">Total Expense</th>
                                <th class="align-middle text-center">Status</th>
                                <th class="align-middle text-center border-top-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $index => $d)
                                <tr class="text-center">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ optional($d->officeExpensePurchase->officeExpense)->office ?? 'N/A' }}</td>
                                    <td>{{ optional($d->officeExpensePurchase)->purchase_name ?? 'N/A' }}</td>
                                    <td>{{ $d->notes }}</td>
                                    <td>{{ rupiah_format($d->total_expense) }}</td>
                                    <td>
                                        @if ($d->status == 'approved')
                                            <span class="badge-custom badge-approved">Approved</span>
                                        @elseif($d->status == 'pending')
                                            <span class="badge-custom badge-pending">Pending</span>
                                        @else
                                            <span class="badge-custom badge-rejected">rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        @hasanyrole('it|top-manager|manager')
                                            <div class="d-flex flex-column gap-2">
                                                @if (is_null($d->approved_by) && is_null($d->approved_date))
                                                    <button data-bs-toggle="modal"
                                                        class="btn btn-sm w-100 {{ $d->status === 'approved' || $d->status === 'rejected' ? 'btn-secondary' : 'btn-success' }}"
                                                        @if ($d->status === 'approved' || $d->status === 'rejected') disabled @endif
                                                        data-bs-target="#approvalModal-{{ $d->id }}">
                                                        Approve
                                                    </button>

                                                    <x-common.modal id="approvalModal-{{ $d->id }}"
                                                        title="Office Expense Approval">
                                                        <x-slot:modal-body>
                                                            <h6>Confirm for approve office expense?</h6>
                                                        </x-slot:modal-body>
                                                        <x-slot:modal-footer>
                                                            <x-common.modal.button-cancel />
                                                            <button type="submit" wire:loading.attr="disabled"
                                                                class="btn btn-success"
                                                                wire:click="approve({{ $d->id }})">Confirm
                                                            </button>
                                                        </x-slot:modal-footer>
                                                    </x-common.modal>
                                                @endif

                                                @if (!is_null($d->approved_by))
                                                    <button class="btn btn-sm w-100 btn-secondary" disabled>Reject</button>
                                                @else
                                                    <button class="btn btn-sm w-100 btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal-{{ $d->id }}">
                                                        Reject
                                                    </button>

                                                    <x-common.modal id="rejectModal-{{ $d->id }}"
                                                        title="Office Expense Approval">
                                                        <x-slot:modal-body>
                                                            <h6>Confirm for reject office expense?</h6>
                                                        </x-slot:modal-body>
                                                        <x-slot:modal-footer>
                                                            <x-common.modal.button-cancel />
                                                            <button type="submit" wire:loading.attr="disabled"
                                                                class="btn btn-danger"
                                                                wire:click="reject({{ $d->id }})">Confirm
                                                            </button>
                                                        </x-slot:modal-footer>
                                                    </x-common.modal>
                                                @endif
                                            </div>
                                        @endhasanyrole
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No approval office expense</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
