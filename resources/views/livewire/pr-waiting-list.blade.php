<div>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="primary-color-sne">PR Waiting Approval</h2>
            </div>
        </div>
    </div>

    @php
        $sessionKey = ['success', 'danger', 'warning', 'info'];
    @endphp

    @foreach ($sessionKey as $key)
        @if (Session::has($key))
            <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                {{ Session::get($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @endif
    @endforeach

    <div class="card primary-box-shadow mt-5">

        <div class="card-body" style="overflow-x: scroll;">
            <table class="table primary-box-shadow">
                <thead class="thead-light">
                    <tr class="table-secondary">
                        <th class="text-center border-top-left" style="width: 5%">
                            No
                        </th>
                        <th style="text-align: center; width: 25%;" class="border">Task Number
                        </th>
                        <th style="text-align: center; width: 20%;" class="border">Warehouse</th>
                        <th style="text-align: center; width: 5%;" class="border">Item</th>
                        <th style="text-align: center; width: 10%;" class="border border-top-right">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($prData as $index => $pr)
                        <tr>
                            <td class="text-center border-bottom-0 border-top border-start border-end">
                                {{ $index + 1 }}
                            </td>
                            <td class="border">
                                <div>
                                    <span style="background-color: #ffc107; padding: 0px 5px; border-radius: 6px;">
                                        {{ $pr->partof }}
                                    </span>
                                </div>

                                <span>
                                    @if($pr->project == null && $pr->prdetail->every(fn ($item) => $item->is_raw_materials == 1))
                                        Raw Materials
                                    @else
                                        Project :
                                        {{ $pr->project ? $pr->project->name : 'data project terhapus' }}
                                    @endif
                                    <br>
                                </span>

                                <span style="font-size: 14px; font-style: italic;">
                                    Notes:
                                    @if ($pr->remark != null)
                                        {{ $pr->remark }}
                                    @endif
                                    <br>
                                </span>

                                <span
                                    style="font-size: 14px;
                                            font-style: italic;">
                                </span>
                            </td>

                            <td class="border">
                                <span>
                                    <span
                                        style="background-color: #198754;
                                                    color: white;
                                                    padding: 0px 10px;
                                                    border-radius: 6px;">Tanggal
                                        Request</span><br>
                                    <div style="font-weight: 900">{{ $pr->created_at }}</div>
                                </span>
                                @if ($pr->warehouse_id != 0 && $pr->warehouse)
                                    {{ $pr->warehouse->name }}
                                @else
                                    Project
                                @endif
                            </td>

                            <td class="border" style="text-align: center">
                                {{ count($pr->prdetail) }}
                            </td>
                            <td>
                                @if ((bool) $this->setting->multiple_pr_approval)
                                    @if (is_null($pr->approved_by))
                                        <div class="d-flex flex-column">
                                            <button type="button" class="btn btn-success btn-sm w-100"
                                                wire:click="approvePR({{ $pr->id }})">
                                                First Approve
                                            </button>
                                        </div>
                                    @elseif (!is_null($pr->approved_by) && is_null($pr->approved_by_2))
                                        <div class="d-flex flex-column">
                                            @if ($pr->approved_by != auth()->user()->id)
                                                <button type="button" class="btn btn-success btn-sm w-100"
                                                    wire:click="approvePR({{ $pr->id }})">
                                                    Second Approve
                                                </button>
                                            @else
                                                <button disabled class="btn btn-sm btn-success">Approved
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-success">Approved</span>
                                        </div>
                                    @endif
                                @else
                                    @if (is_null($pr->approved_by))
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-warning">Awaiting Approval</span>
                                            <button type="button" class="btn btn-success btn-sm w-100 mt-2"
                                                wire:click="approvePR({{ $pr->id }})">
                                                Approve
                                            </button>
                                        </div>
                                    @else
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-success">Approved</span>
                                        </div>
                                    @endif
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td class="border-top-0 border-end border-start"></td>
                            <td colspan="6">
                                <div class="accordian-body">
                                    <table class="table table-bordered">
                                        <thead class="border">
                                            <tr class="info">
                                                <th class="border" style="text-align: center" width="5%"></th>
                                                <th class="border border-top-left" style="text-align: center">No</th>
                                                <th class="border" style="text-align: center">Item Name</th>
                                                <th class="border" style="text-align: center">Quantity</th>
                                                <th class="border" style="text-align: center">Unit</th>
                                            </tr>
                                        </thead>

                                        <tbody class="bg-white">
                                            @foreach ($pr->prdetail as $index => $detail)
                                                <tr>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $detail->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $detail->item->name }}

                                                        @if($stockData->pluck('item_id')->contains($detail->item_id))
                                                            <br>
                                                            <span class="badge badge-success">Available Stock:</span>

                                                            @php
                                                                $availableStocks = $stockData->where('item_id', $detail->item_id);
                                                            @endphp

                                                            @foreach($availableStocks as $stock)
                                                                @if($stock->stock >= $detail->qty)
                                                                    <div class="mt-2">
                                                                        <input type="checkbox" class="form-check-input stock-checkbox"
                                                                            name="stock_checkbox_{{ $detail->item_id }}"
                                                                            data-item-id="{{ $detail->item_id }}"
                                                                            value="{{ $detail->id }}, {{ $detail->item_id }}, {{ $stock->stock }}, {{ $detail->purchaseRequest->id }}, {{$stock->warehouse_type}}"
                                                                            >
                                                                        <label class="form-check-label">{{ $stock->warehouse_type ?? 'Gudang' }} - Stock: <strong>{{ $stock->stock }}</strong></label>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @endif

                                                    </td>
                                                    <td style="max-width: 100px">
                                                        @if (!$isEditing)
                                                            <span>{{ $detail->qty }}</span>
                                                            <button class="btn btn-sm btn-outline-primary"
                                                                wire:click="editQty({{ $detail->id }}, `{{ $detail->qty }}`)"><i
                                                                    class="fas fa-edit"></i></button>
                                                        @else
                                                            @if ($isEditing == $detail->id)
                                                                <div class="d-flex gap-3 align-items-center">
                                                                    <input type="number" class="form-control"
                                                                        wire:model="qtyUpdate.{{ $detail->id }}"
                                                                        value="{{ $detail->qty }}"
                                                                        max="{{ $detail->qty }}" min="1">

                                                                    <div class="d-flex">
                                                                        <button class="btn btn-sm btn-primary"
                                                                            wire:click="saveEditQty({{ $detail->id }}, {{ $detail->qty }})">
                                                                            <i class="fas fa-save"></i>
                                                                        </button>
                                                                        <button class="btn btn-sm btn-danger ms-2"
                                                                            wire:click="cancelEditQty">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <span>{{ $detail->qty }}</span>
                                                                <button class="btn btn-sm btn-outline-primary"
                                                                    disabled><i class="fas fa-edit"></i></button>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>{{ $detail->unit }}</td>
                                                </tr>

                                                <!-- Modal -->
                                                <div class="modal fade" id="deleteModal-{{ $detail->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Item</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <p>Are you sure you want to delete <strong>{{ $detail->item->name }}</strong>?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-danger" wire:click="deleteItem({{ $detail->id }})" data-bs-dismiss="modal">Delete</button>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>

                        <tr style="background-color: #e9e9e943">
                            <td colspan="6"></td>
                        </tr>
                    @empty
                        <tr class="text-center text-danger bg-white p-3 rounded fw-bold">
                            <td colspan="6">
                                No PR
                                data found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @push('javascript')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

                $(document).on('change', '.stock-checkbox', function() {
                    let itemId = $(this).data('item-id');
                    $('.stock-checkbox[data-item-id="' + itemId + '"]').not(this).prop('checked', false);

                    Livewire.emit('storeChecked', $(this).val());
                });
        });
    </script>

    @endpush
</div>
