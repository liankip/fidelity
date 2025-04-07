<div wire:ignore.self>
    <div class="container mt-2">
        <div class="row" x-init="initTable()">
            <div class="col-lg-12 margin-tb">
                <h5>
                    Vendor Need Approval
                </h5>
                <hr>
                <x-common.notification-alert />
                @if ($vendors->count() > 0)
                    <div>
                        <button class="btn btn-success" wire:click="approve"
                            @if (count($checklist) == 0) disabled @endif>
                            Approve
                        </button>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal"
                            @if (count($checklist) == 0) disabled @endif>
                            Reject
                        </button>
                    </div>
                @endif
                <div class="card mt-3">
                    <div class="card-body">

                        <table class="table" id="table">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 5%;">
                                        <input type="checkbox" wire:click="checkAll" wire:model="select_all">
                                    </th>
                                    <th class="align-middle" style="width: 5%">#</th>
                                    <th class="align-middle">Company</th>
                                    <th class="align-middle">Email</th>
                                    <th class="align-middle">Phone</th>
                                    <th class="align-middle">Address</th>
                                    <th class="align-middle">Items</th>
                                    <th class="align-middle">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($vendors as $vendor)
                                    <tr>
                                        <td>
                                            <input type="checkbox" wire:model="checklist.{{ $vendor->id }}">
                                        </td>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <a href="{{ route('vendors.show', $vendor->id) }}" target="_blank">
                                                {{ $vendor->name }}
                                            </a>
                                        </td>
                                        <td>{{ $vendor->email }}</td>
                                        <td>{{ $vendor->telp }}</td>
                                        <td>
                                            {{ $vendor->address }}
                                        </td>
                                        <td>
                                            <ol>
                                                @foreach ($vendor->items as $item)
                                                    <li>
                                                        {{ $item->item_name }} ({{ rupiah_format($item->price) }})
                                                    </li>
                                                @endforeach
                                            </ol>
                                        </td>
                                        <td>
                                            {{ date('d F Y', strtotime($vendor->created_at)) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            There is no vendor need approval
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <x-common.modal id="rejectModal" title="Reject Vendor" button="Reject" action="reject">
            <x-slot:modal-body>
                <div class="form-group">
                    <label for="reject_reason">Reason : </label>
                    <textarea wire:model="reasonToReject" class="form-control" id="reject_reason" rows="3"
                        placeholder="Describe your reason here..."></textarea>
                    @error('reject_reason')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </x-slot:modal-body>
            <x-slot:modal-footer>
                <x-common.modal.button-cancel />
                <button type="button" wire:click="reject" class="btn btn-danger">Reject</button>
            </x-slot:modal-footer>
        </x-common.modal>
    </div>
