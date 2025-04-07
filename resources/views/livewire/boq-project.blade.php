@php use Carbon\Carbon; @endphp
<div class="mt-2" wire:ignore>
    <div class="row">
        <div class="row">
            <div class="col-lg-12 mb-5">
                <a class="btn btn-danger" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>
        <div class="col-lg-12 margin-tb">
            <div class="pull-left d-lg-flex justify-content-between">
                <div class=" ">
                    <h2 class="text-black">BOQ - {{ $project->name }}</h2>
                    <h4 class="text-secondary"><strong>
                            Created By: {{ $boqs->user->name }} <br>
                        </strong></h4>
                </div>
                @if ($boqs->status == 'Submitted')
                    <div class=" ">
                        <h4 class="text-secondary"><strong>
                                <span class="badge bg-warning">Submitted</span>
                            </strong></h4>
                    </div>
                @elseif($boqs->status == 'Approved')
                    <div class=" ">
                        <h4 class="text-secondary"><strong>
                                <span class="badge bg-success">Checked</span>
                            </strong></h4>
                    </div>
                @elseif($boqs->status == 'Reviewed')
                    <div class=" ">
                        <h4 class="text-secondary"><strong>
                                <span class="badge bg-primary">Reviewed</span>
                            </strong></h4>
                    </div>
                @elseif ($boqs->status == 'Finalized')
                    <div class=" ">
                        <h4 class="text-secondary"><strong>
                                <span class="badge bg-info">Approved</span>
                            </strong></h4>
                    </div>
                @endif
            </div>
            <hr>
            <x-common.notification-alert />
            @if ($boqs->approved && $boqs->approved2)
                <div class="text-end">
                    <span class="text-success">
                        <strong>Approved by {{ $boqs->approved->name }}</strong>
                    </span>
                    @if ($boqs->date_approved)
                        - <em>{{ date('d F Y', strtotime($boqs->date_approved)) }}</em>
                    @endif
                </div>
                <div class="text-end">
                    <span class="text-success">
                        <strong>Approved by {{ $boqs->approved2->name }}</strong>
                    </span>
                    @if ($boqs->date_approved_2)
                        - <em>{{ date('d F Y', strtotime($boqs->date_approved_2)) }}</em>
                    @endif
                </div>
            @endif
        </div>
    </div>
    @if (auth()->user()->hasTopLevelAccess() ||
            auth()->user()->hasK3LevelAccess() ||
            auth()->user()->hasTier1Access() ||
            auth()->user()->hasTier2Access() ||
            auth()->user()->hasApproveBOQSpreadsheet() ||
            auth()->user()->hasAdminLapanganLevelAccess())
        <div class="d-lg-flex justify-content-end my-3 align-items-center">
            <div class="d-flex gap-3">
                @if ((is_null($review) && $boqs->status === 'Submitted'))
                    @if (auth()->user()->hasK3LevelAccess())
                        @if ($boqs->approved_by_2)
                            {{ $boqs->approved_by_2 == Auth::user()->id ? 'You' : $boqs->approved->name }} already
                            approved
                            this on
                            {{ $boqs->date_approved ? Carbon::parse($boqs->date_approved)->format('d-m-Y') : ($boqs->date_approved_2 ? Carbon::parse($boqs->date_approved_2)->format('d-m-Y') : 'N/A') }}
                        @else
                            @if ($setting->multiple_k3_approval)
                                <button type="button" class="btn btn-primary ml-3" data-bs-toggle="modal"
                                    data-bs-target="#approveK3Modal">Approve
                                </button>
                                <button type="button" class="btn btn-danger ml-3" data-bs-toggle="modal"
                                    data-bs-target="#rejectedK3Modal">Reject
                                </button>
                            @endif
                        @endif
                    @elseif(auth()->user()->hasTopLevelAccess() ||
                            auth()->user()->hasTier1Access() ||
                            auth()->user()->hasTier2Access() ||
                            auth()->user()->hasApproveBOQSpreadsheet() ||
                            auth()->user()->hasAdminLapanganLevelAccess())
                        {{-- @if ($boqs->approved_by)
                            {{ $boqs->approved_by == Auth::user()->id ? 'You' : $boqs->approved->name }} already
                            approved
                            this on
                            {{ $boqs->date_approved ? Carbon::parse($boqs->date_approved)->format('d-m-Y') : ($boqs->date_approved_2 ? Carbon::parse($boqs->date_approved_2)->format('d-m-Y') : 'N/A') }}
                        @else
                            <button class="btn btn-primary ml-3" wire:click="approve">Approve</button>
                            @if (auth()->user()->hasTopLevelAccess())
                                <button class="btn btn-success ml-3" wire:click="review">Review</button>
                            @endif
                            <button class="btn btn-danger ml-3" wire:click="reject">Reject</button>
                        @endif --}}
                        <a href="{{ route('boq.project.edit', ['projectId' => $project->id, 'taskId' => $boqs->task_id, 'boqId' => $boqs->id]) }}"
                            class="btn btn-sm btn-outline-primary">Edit
                        </a>
                        <button class="btn btn-primary ml-3" wire:click.prevent="submitBOQ" id="submitBOQButton"
                            onclick="disableSubmitBOQ()">
                            Submit BOQ
                        </button>
                    @endif
                @else
                    @if ($boqs->status === 'Submitted')
                        <button class="btn btn-primary ml-3" wire:click="submitReview">Submit Review</button>
                    @endif
                @endif

                @if (
                    $boqs->status === 'Draft' &&
                        (auth()->user()->hasTopManagerAccess() ||
                            auth()->user()->hasTier1Access() ||
                            auth()->user()->hasTier2Access() ||
                            auth()->user()->hasK3LevelAccess() ||
                            auth()->user()->hasAdminLapanganLevelAccess()))
                    <a href="{{ route('boq.project.edit', ['projectId' => $project->id, 'taskId' => $boqs->task_id, 'boqId' => $boqs->id]) }}"
                        class="btn btn-sm btn-outline-primary">Edit
                    </a>
                    <button class="btn btn-primary ml-3" wire:click.prevent="submitBOQ" id="submitBOQButton"
                        onclick="disableSubmitBOQ()">
                        Submit BOQ
                    </button>

                    <script>
                        function disableSubmitBOQ() {
                            var button = document.getElementById("submitBOQButton");
                            button.disabled = true;
                            button.innerText = "Submitting...";
                        }
                    </script>
                @endif
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if (!is_null($review) && $boqs->status !== 'Reviewed' && auth()->user()->hasTopLevelAccess())
                <table class="table">
                    <thead class="thead-light">
                        <th class="text-center align-middle" width="5%">No</th>
                        <th class="text-center align-middle" width="15%">Item Name</th>
                        <th class="text-center align-middle" width="5%">Unit</th>
                        <th class="text-center align-middle" width="10%">Quantity</th>
                        <th class="text-center align-middle" width="10%">Price Estimation</th>
                        <th class="text-center align-middle" width="10%">Shipping Cost Estimation</th>
                        <th class="text-center align-middle" width="10%">Total Estimation</th>
                        <th class="text-center align-middle" width="10%">Action</th>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($review->getJsonDataAsObjectArray() as $index => $item)
                            @php
                                $total += $item->quantity * $item->price;
                            @endphp
                            <tr>
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">{{ $item->item_name }}</td>
                                <td class="text-center align-middle">{{ $item->unit }}</td>
                                <td class="text-center align-middle">{{ $item->quantity }}</td>
                                <td class="text-center align-middle">{{ rupiah_format($item->price) }}</td>
                                <td class="text-center align-middle">{{ $item->shipping_cost }}</td>
                                <td class="text-center align-middle">
                                    {{ rupiah_format($item->quantity * $item->price) }}
                                </td>
                                <td class="text-center align-middle">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editModal" wire:click="edit({{ $index }})">Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger"
                                        wire:click="delete({{ $index }})">Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="6" class="text-center align-middle"><strong>Total</strong></td>
                            <td class="text-center align-middle"><strong>{{ rupiah_format($total) }}</strong></td>
                            <td class="text-center align-middle"></td>
                        </tr>
                    </tbody>
                </table>
            @else
                <x-common.table id="boqTable" class="table" :paging="false">
                    <thead class="thead-light">
                        <th class="text-center align-middle" width="5%">No</th>
                        <th class="text-center align-middle" width="15%">Item Name</th>
                        <th class="text-center align-middle" width="5%">Unit</th>
                        <th class="text-center align-middle" width="10%">Quantity</th>
                        <th class="text-center align-middle" width="10%">Price Estimation</th>
                        <th class="text-center align-middle" width="10%">Shipping Cost Estimation</th>
                        <th class="text-center align-middle" width="10%">Total Estimation</th>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($boqs->getJsonDataAsObjectArray() as $index => $item)
                            @php
                                $total += $item->quantity * $item->price;
                            @endphp
                            <tr>
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">{{ $item->item_name }}</td>
                                <td class="text-center align-middle">{{ $item->unit }}</td>
                                <td class="text-center align-middle">{{ $item->quantity }}</td>
                                <td class="text-center align-middle">{{ rupiah_format($item->price) }}</td>
                                <td class="text-center align-middle">{{ $item->shipping_cost }}</td>
                                <td class="text-center align-middle">
                                    {{ rupiah_format($item->quantity * $item->price) }}
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-center align-middle"><strong>Total</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center align-middle"><strong>{{ rupiah_format($total) }}</strong></td>
                        </tr>
                    </tfoot>
                </x-common.table>
            @endif
        </div>
    </div>

    <x-common.modal id="approveK3Modal" title="Komentar Approved">
        <x-slot:modal-body>
            <form wire:submit.prevent="approve">
                <div class="form-group">
                    <textarea required class="form-control" name="comment" wire:model="comment"
                        placeholder="Masukkan Komentar Approved"></textarea>
                    @error('comment')
                        <span class="alert alert-danger mt-1 mb-1">{{ $message }}</span>
                    @enderror
                </div>
                <x-common.modal.button-cancel />
                <button type="submit" class="btn btn-success">Save</button>
            </form>
        </x-slot:modal-body>
        <x-slot:modal-footer>
        </x-slot:modal-footer>
    </x-common.modal>

    <x-common.modal id="rejectedK3Modal" title="Komentar Rejected">
        <x-slot:modal-body>
            <form wire:submit.prevent="reject">
                <div class="form-group">
                    <label class="form-label" for="comment">Komentar</label>
                    <textarea required class="form-control" wire:model="comment" placeholder="Masukkan Komentar Rejected"></textarea>
                    @error('comment')
                        <span class="alert alert-danger mt-1 mb-1">{{ $message }}</span>
                    @enderror
                </div>
                <x-common.modal.button-cancel />
                <button type="submit" class="btn btn-success">Save</button>
            </form>
        </x-slot:modal-body>
        <x-slot:modal-footer>
        </x-slot:modal-footer>
    </x-common.modal>

    <x-common.modal id="editModal" title="Edit BOQ Project">
        <x-slot:modal-body>
            <div class="form-group">
                <x-common.input label="Item Name" name="item_name" wire:model='item_name' />
            </div>
            <div class="form-group">
                <x-common.input label="Unit" name="unit_name" wire:model='unit_name' />
            </div>
            <div class="form-group">
                <x-common.input label="Quantity" name="qty" wire:model='qty' />
            </div>
            <div class="form-group">
                <x-common.input label="Price Estimation" name="price" wire:model='price' />
            </div>
            <div class="form-group">
                <x-common.input label="Shipping Cost Estimation" name="shipping_cost" wire:model='shipping_cost' />
            </div>
        </x-slot:modal-body>
        <x-slot:modal-footer>
            <x-common.modal.button-cancel />
            <button type="button" class="btn btn-success" wire:click="update">Save</button>
        </x-slot:modal-footer>
    </x-common.modal>
</div>
