@php use Carbon\Carbon; @endphp
<div class="mt-2" wire:ignore>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left d-lg-flex justify-content-between">
                <div class=" ">
                    <a href="{{ route('capex-expense.boq.list', $project_id) }}" class="third-color-sne"> <i
                            class="fa-solid fa-chevron-left fa-xs"></i> Back</a>
                    <h2 class="text-black">Capex Expense BOQ - {{ $boqSpreadsheet->project->name }}</h2>
                    <h4 class="text-secondary"><strong>
                            Created By: {{ $boqSpreadsheet->user->name }} <br>
                        </strong></h4>
                </div>
                @if ($boqSpreadsheet->status == 'Submitted')
                    <div class=" ">
                        <h4 class="text-secondary"><strong>
                                <span class="badge bg-warning">Submitted</span>
                            </strong></h4>
                    </div>
                @elseif($boqSpreadsheet->status == 'Approved')
                    <div class=" ">
                        <h4 class="text-secondary"><strong>
                                <span class="badge bg-success">Checked</span>
                            </strong></h4>
                    </div>
                @elseif($boqSpreadsheet->status == 'Reviewed')
                    <div class=" ">
                        <h4 class="text-secondary"><strong>
                                <span class="badge bg-primary">Reviewed</span>
                            </strong></h4>
                    </div>
                @elseif ($boqSpreadsheet->status == 'Finalized')
                    <div class=" ">
                        <h4 class="text-secondary"><strong>
                                <span class="badge bg-info">Approved</span>
                            </strong></h4>
                    </div>
                @endif
            </div>

            <x-common.notification-alert />

            @if ($boqSpreadsheet->approved && $boqSpreadsheet->approved2)
                <div class="text-end">
                    <span class="text-success">
                        <strong>Approved by {{ $boqSpreadsheet->approved->name }}</strong>
                    </span>
                    @if ($boqSpreadsheet->date_approved)
                        - <em>{{ date('d F Y', strtotime($boqSpreadsheet->date_approved)) }}</em>
                    @endif
                </div>
                <div class="text-end">
                    <span class="text-success">
                        <strong>Approved by {{ $boqSpreadsheet->approved2->name }}</strong>
                    </span>
                    @if ($boqSpreadsheet->date_approved_2)
                        - <em>{{ date('d F Y', strtotime($boqSpreadsheet->date_approved_2)) }}</em>
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
                @if ((is_null($review) && $boqSpreadsheet->status === 'Submitted'))
                    @if (auth()->user()->hasK3LevelAccess())
                        @if ($boqSpreadsheet->approved_by_2)
                            {{ $boqSpreadsheet->approved_by_2 == Auth::user()->id ? 'You' : $boqSpreadsheet->approved->name }} already
                            approved
                            this on
                            {{ $boqSpreadsheet->date_approved ? Carbon::parse($boqSpreadsheet->date_approved)->format('d-m-Y') : ($boqSpreadsheet->date_approved_2 ? Carbon::parse($boqSpreadsheet->date_approved_2)->format('d-m-Y') : 'N/A') }}
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
                        {{-- @if ($boqSpreadsheet->approved_by)
                            {{ $boqSpreadsheet->approved_by == Auth::user()->id ? 'You' : $boqSpreadsheet->approved->name }} already
                            approved
                            this on
                            {{ $boqSpreadsheet->date_approved ? Carbon::parse($boqSpreadsheet->date_approved)->format('d-m-Y') : ($boqSpreadsheet->date_approved_2 ? Carbon::parse($boqSpreadsheet->date_approved_2)->format('d-m-Y') : 'N/A') }}
                        @else
                            <button class="btn btn-primary ml-3" wire:click="approve">Approve</button>
                            @if (auth()->user()->hasTopLevelAccess())
                                <button class="btn btn-success ml-3" wire:click="review">Review</button>
                            @endif
                            <button class="btn btn-danger ml-3" wire:click="reject">Reject</button>
                        @endif --}}
{{--                        <a href="{{ route('boq.project.edit', ['projectId' => $project->id, 'taskId' => $boqSpreadsheet->task_id, 'boqId' => $boqSpreadsheet->id]) }}"--}}
{{--                           class="btn btn-sm btn-outline-primary">Edit--}}
{{--                        </a>--}}
                        <button class="btn btn-primary ml-3" wire:click.prevent="submitBOQ" id="submitBOQButton"
                                onclick="disableSubmitBOQ()">
                            Submit BOQ
                        </button>
                    @endif
                @else
                    @if ($boqSpreadsheet->status === 'Submitted')
                        <button class="btn btn-primary ml-3" wire:click="submitReview">Submit Review</button>
                    @endif
                @endif

                @if (
                    $boqSpreadsheet->status === 'Draft' &&
                        (auth()->user()->hasTopManagerAccess() ||
                            auth()->user()->hasTier1Access() ||
                            auth()->user()->hasTier2Access() ||
                            auth()->user()->hasK3LevelAccess() ||
                            auth()->user()->hasAdminLapanganLevelAccess()))
{{--                    <a href="{{ route('boq.project.edit', ['projectId' => $project->id, 'taskId' => $boqSpreadsheet->task_id, 'boqId' => $boqSpreadsheet->id]) }}"--}}
{{--                       class="btn btn-sm btn-outline-primary">Edit--}}
{{--                    </a>--}}
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
            @if (!is_null($review) && $boqSpreadsheet->status !== 'Reviewed' && auth()->user()->hasTopLevelAccess())
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
                <table class="table">
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
                    @foreach ($boqSpreadsheet->getJsonDataAsObjectArray() as $index => $item)
                        @php
                            $total += $item->quantity * $item->price;
                        @endphp
                        <tr>
                            <td class="text-center align-middle">{{ $index + 1 }}</td>
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
                </table>
            @endif
        </div>
    </div>
</div>
