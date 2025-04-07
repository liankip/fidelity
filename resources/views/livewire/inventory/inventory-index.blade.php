<div class="mt-2">
    <style>
        /* Modal backdrop */
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Modal content */
        .custom-modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Close button */
        .custom-modal-close {
            color: #aaa;
            float: right;
            cursor: pointer;
        }

        .custom-modal-close:hover,
        .custom-modal-close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            {{-- <div class="pull-right mb-2">
                    <a class="btn btn-success" href="{{ route('payments.create') }}"> Create payment</a>
                </div> --}}
        </div>
    </div>
    @foreach (['danger', 'warning', 'success', 'info'] as $key)
        @if (Session::has($key))
            <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                {{ Session::get($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @endif
    @endforeach
    <h2 class="primary-color-sne">Inventory</h2>
    {{-- <div class="mb-3 mt-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWarehouseModal">Create Warehouse</button>
    </div> --}}

    <!-- Warehouse Modal -->
    <div class="modal fade bg-black" style="--bs-bg-opacity: 0.5" id="createWarehouseModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="false" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Warehouse</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Warehouse Name</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1"
                            placeholder="Warehouse Name" wire:model='warehouseModel'>
                        @error('warehouseModel')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:loading.attr="disabled"
                        wire:target="warehouseModel, saveWarehouse" wire:click="saveWarehouse">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-5 primary-box-shadow">
        <div class="card-body">
            <div class="mb-3" wire:ignore>
                {{-- <button class="btn btn-success" wire:click="generate" wire:loading.attr="disabled"
                    wire:loading.class="btn-secondary">
                    <span wire:loading.remove wire:target="generate">Generate</span>
                    <span wire:loading wire:target="generate">Generating...</span>
                </button> --}}
                <select wire:model='projectmodel' name="project_id" id="project_id"
                    class="js-example-basic-single form-select">
                    <option value="" readonly selected>Pilih Project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id ?? $project->name }}">
                            @if ($project->id !== null)
                                {{ $project->name }}<span>: </span>{{ $project->company_name }}
                            @else
                                {{ $project->name }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            @if ($projectmodel)
                <a class="btn btn-primary" href="{{ route('inventory-out.export', $projectmodel) }}">Export</a>

                @if (!intval($projectmodel))
                    <button class="btn btn-success" id="inputItemBtn">Input Item</button>

                    <!-- Custom Modal Create Warehouse-->
                    <div id="customModal" class="custom-modal" wire:ignore.self>
                        <div class="custom-modal-content">
                            <span class="custom-modal-close">&times;</span>
                            <h5>Input Item to {{ $projectmodel }}</h5>

                            <div>
                                @foreach ($items as $index => $item)
                                    <div class="d-flex gap-2 mb-2 justify-content-between"
                                        wire:key="item-{{ $index }}">
                                        <!-- Item Dropdown -->
                                        <select wire:model="items.{{ $index }}.item_id"
                                            class="form-control dropdownItems">
                                            <option value="">Select Item</option>
                                            @foreach ($itemsData as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>

                                        <!-- Quantity Input -->
                                        <input type="number" wire:model="items.{{ $index }}.qty"
                                            class="form-control w-25" min="1" placeholder="Qty">

                                        <!-- Remove Button -->
                                        <button class="btn btn-danger" wire:click="removeItem({{ $index }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>

                                    @error('items.' . $index . '.item_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('items.' . $index . '.qty')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                @endforeach
                            </div>

                            <!-- Add Item Button -->
                            <button class="btn btn-primary btn-sm" wire:click="addItem">+ Add Item</button>

                            <!-- Save Button -->
                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <button class="btn btn-sm btn-secondary custom-modal-close">Cancel</button>
                                <button class="btn btn-sm btn-success" wire:click="saveItems">Save</button>
                            </div>
                        </div>
                    </div>


                @endif
            @endif

            <div class="input-group mb-3 mt-3">
                <input type="text" class="form-control" name="search"
                    placeholder="Search Item Code, Item Name or Project Name" wire:model.debounce.500ms="search"
                    spellcheck="false" data-ms-editor="true">
            </div>

            <table class="table table-bordered" style="border-color: #c8c8c8;">
                <thead class="thead-light">
                    <tr>
                        <th class="text-center border-top-left" style="border-color: #c8c8c8; width: 5%">No</th>
                        <th class="text-center" style="border-color: #c8c8c8; width: 20%">Project</th>
                        <th class="text-center" style="border-color: #c8c8c8; width: 25%">Item</th>
                        <th class="text-center" style="border-color: #c8c8c8; width: 10%">Stock Awal</th>
                        <th class="text-center" style="border-color: #c8c8c8; width: 10%">Stock Sisa</th>
                        <th class="text-center border-top-right" style="border-color: #c8c8c8; width: 20%">Task</th>
                    </tr>
                </thead>
                <tbody style="border-color: #c8c8c8;">
                    @php
                        $iteration = 0;
                    @endphp
                    @foreach ($groupedInventories as $projectKey => $projectInventories)
                        @php
                            $iteration++;
                            $projectRowspan = 0;
                            foreach ($projectInventories as $inventory) {
                                $projectRowspan += count($inventory->details);
                            }
                            [$projectName, $projectId] = explode(' _ ', $projectKey);
                        @endphp
                        @foreach ($projectInventories as $outer_index => $inventory)
                            @php
                                $inventoryRowspan = count($inventory->details);
                                $row_class = $outer_index % 2 == 0 ? '' : 'bg-light';
                            @endphp
                            @foreach ($inventory->details as $index => $inventory_detail)
                                <tr class="{{ $row_class }}" style="border-color: #c8c8c8;">
                                    @if ($outer_index === 0 && $index === 0)
                                        <td rowspan="{{ $projectRowspan }}" class="text-center"
                                            style="border-color: #c8c8c8;">
                                            {{ $iteration }}
                                        </td>
                                        <td rowspan="{{ $projectRowspan }}" style="border-color: #c8c8c8;">
                                            {{-- <a href="{{ route('boq.index', $inventory_detail->project_id) }}"> --}}
                                            {{ trim($projectName, '"') }}
                                            {{-- </a> --}}
                                        </td>
                                    @endif
                                    @if ($index === 0)
                                        @php
                                            $stock = 0;
                                            $outQty = 0;
                                            $remainingStock = 0;
                                            foreach ($inventory->details as $detail) {
                                                if ($detail->project_id == $projectId) {
                                                    $stock += $detail->stock;
                                                }
                                            }

                                            if ($projectId == '') {
                                                $projectId = null;
                                            }

                                            // $detailData = \App\Models\InventoryDetail::where('project_id', $projectId)->where('inventory_id', $inventory_detail->inventory_id)->first();

                                            $warehouseModel = null;

                                            if ($projectId == null) {
                                                if ($projectmodel == '') {
                                                    $sliceProjectName = explode($CONSTANT . ': ', $projectName);
                                                } else {
                                                    $sliceProjectName = explode($CONSTANT . ': ', $projectmodel);
                                                }

                                                if (count($sliceProjectName) > 1) {
                                                    $warehouseModel = trim($sliceProjectName[1], '"');
                                                } elseif (trim($sliceProjectName[0], '"') == 'Gudang Medan') {
                                                    $warehouseModel = 'Gudang Medan';
                                                } elseif (!intval($projectmodel)) {
                                                    $warehouseModel = $sliceProjectName[0];
                                                }
                                            }

                                            $detailData = \App\Models\InventoryDetail::where('project_id', $projectId)
                                                ->where('inventory_id', $inventory_detail->inventory_id)
                                                ->where('warehouse_type', $warehouseModel)
                                                ->get();

                                            if(count($detailData) > 1) {
                                                $detailData = $detailData->where('id', $inventory_detail->id)->first();
                                            } else {
                                                $detailData = $detailData->first();
                                            }

                                            $inventoryData = collect();
                                            if ($detailData != null) {
                                                $inventoryData = \App\Models\InventoryHistory::where(
                                                    'inventory_detail_id',
                                                    $detailData->id,
                                                )->get();
                                            }

                                            if (count($inventoryData) > 0) {
                                                $totalStock =
                                                    $detailData->detailHistory
                                                        ->where('type', 'OUT')
                                                        ->where('is_actual', 0)
                                                        ->sum('stock_change') + $detailData->stock;

                                                if (
                                                    $detailData->detailHistory
                                                        ->where('type', 'OUT')
                                                        ->where('is_actual', 1)
                                                        ->count() > 0
                                                ) {
                                                    $remainingStock = $detailData->stock;

                                                    $totalStockChange = $detailData->detailHistory
                                                        ->where('type', 'OUT')
                                                        ->where('is_actual', 1)
                                                        ->sum('stock_change');

                                                    $totalStock = $detailData->stock + $totalStockChange;
                                                } else {
                                                    $remainingStock = $detailData->stock;
                                                }
                                            } else {
                                                $totalStock = $stock;
                                                $remainingStock = $detailData ? $detailData->stock : $stock;
                                            }

                                        @endphp
                                        <td rowspan="{{ $inventoryRowspan }}" style="border-color: #c8c8c8;">
                                            {{ $inventory->item_name }}

                                            @if (count($inventoryData) > 0)
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-sm btn-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal-{{ $detailData->id }}">
                                                        Item History
                                                    </button>

                                                    @if (!intval($projectId) && $detailData->stock > 0)
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#inventoryOutModal-{{ $detailData->id }}">
                                                            Input Inventory Out
                                                        </button>

                                                        <div class="modal fade bg-black inventoryOutModal"
                                                            id="inventoryOutModal-{{ $detailData->id }}"
                                                            tabindex="-1" aria-labelledby="exampleModalLabel"
                                                            aria-hidden="true" data-bs-backdrop="false"
                                                            style="--bs-bg-opacity: 0.5" wire:ignore.self>
                                                            <div class="modal-dialog">
                                                                <div class="modal-content"
                                                                    style="border: 1px solid #080808">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5"
                                                                            id="exampleModalLabel">Inventory Out
                                                                            {{ $detailData->inventory->item->name }}
                                                                        </h1>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">

                                                                        <div class="mb-3">
                                                                            <label for="outQty"
                                                                                class="form-label">Quantity
                                                                                Keluar</label>
                                                                            <input type="number" class="form-control"
                                                                                id="outQty" wire:model="outQty"
                                                                                required>
                                                                            @error('outQty')
                                                                                <small
                                                                                    class="text-danger">{{ $message }}</small>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="actualDate"
                                                                                class="form-label">Tanggal
                                                                                Keluar</label><label
                                                                                class="form-label"></label>
                                                                            <input type="date" class="form-control"
                                                                                id="actualDate"
                                                                                wire:model="actualDate" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="keterangan"
                                                                                class="form-label">Keterangan</label>
                                                                            <textarea class="form-control" id="keterangan" rows="3" wire:model="keteranganModel" required></textarea>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                        <button type="button" class="btn btn-primary"
                                                                            wire:click="saveInventoryOut({{ $detailData }}, {{ $remainingStock }})"
                                                                            wire:loading.attr="disabled"
                                                                            wire:target="outQty, keteranganModel, saveInventoryOut">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Custom Modal Inventory Out-->
                                                    @endif
                                                </div>
                                            @endif


                                        </td>
                                        <td rowspan="{{ $inventoryRowspan }}" class="text-center"
                                            style="border-color: #c8c8c8;">
                                            {{ $totalStock }}
                                        </td>
                                        <td class="text-center" style="border-color: #c8c8c8;">
                                            {{ $remainingStock }}
                                        </td>
                                        <td class="text-center" style="border-color: #c8c8c8;">
                                            {{-- @if ($inventoryData->count() > 0)
                                                @foreach ($inventoryData as $key => $data)
                                                    @foreach ($data as $data)
                                                        <li style="font-size: 10pt">
                                                            <a href="{{ route('task-monitoring.index', ['taskId' => $data->taskRel->id]) }}">{{ $key }}</a>
                                                        </li>
                                                    @endforeach
                                                @endforeach
                                            @else
                                                -
                                            @endif --}}
                                        </td>

                                        <!-- History Modal -->
                                        <div class="modal fade bg-black" id="exampleModal-{{ $detailData->id }}"
                                            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
                                            style="--bs-bg-opacity: 0.5" data-bs-backdrop="false">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Inventory
                                                            History {{ $inventory_detail->inventory->item->name }}</h1>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php
                                                            $inventoryIn = $inventoryData->where('type', 'IN');
                                                            $inventoryOut = $inventoryData->where('type', 'OUT');
                                                        @endphp

                                                        <div class="accordion" id="accordionPanelsStayOpenExample">
                                                            @if ($inventoryIn->count() > 0)
                                                                <div class="accordion-item mb-3">
                                                                    <h2 class="accordion-header">
                                                                        <button class="accordion-button collapsed"
                                                                            type="button" data-bs-toggle="collapse"
                                                                            data-bs-target="#panelsStayOpen-collapse-{{ $inventory_detail->id }}"
                                                                            aria-expanded="true"
                                                                            aria-controls="panelsStayOpen-collapse-{{ $inventory_detail->id }}"
                                                                            style="background-color: #c8c8c8; color: #000;">
                                                                            Inventory In
                                                                        </button>
                                                                    </h2>
                                                                    <div id="panelsStayOpen-collapse-{{ $inventory_detail->id }}"
                                                                        class="accordion-collapse collapse show">
                                                                        <div class="accordion-body"
                                                                            style="border: 1px solid #c8c8c8; max-height: 300px; overflow-y: scroll; scrollbar-width: thin;">
                                                                            <ul class="list-group gap-2">
                                                                                @foreach ($inventoryIn as $historyData)
                                                                                    <div class="list-group-item">
                                                                                        <div
                                                                                            class="d-flex w-100 justify-content-between">
                                                                                            <div>
                                                                                                <h6 class="mb-1">
                                                                                                    {{ \Carbon\Carbon::parse($historyData->actual_date ?? $historyData->created_at)->format('d M Y') }}
                                                                                                </h6>

                                                                                                <div>
                                                                                                    <small
                                                                                                        class="text-muted">Stock
                                                                                                        Sebelumnya:
                                                                                                        {{ $historyData->stock_before }}</small>
                                                                                                    <br>
                                                                                                    <small
                                                                                                        class="text-muted">Stock
                                                                                                        Masuk:
                                                                                                        {{ $historyData->stock_change }}</small>
                                                                                                    <div class="my-2 w-100 bg-secondary"
                                                                                                        style="height: 1px">
                                                                                                    </div>
                                                                                                    <small
                                                                                                        class="text-muted fw-bold">Stock
                                                                                                        Total :
                                                                                                        {{ $historyData->stock_before + $historyData->stock_change }}</small>
                                                                                                </div>
                                                                                            </div>
                                                                                            @if ($historyData->podetail_id !== null)
                                                                                                <small
                                                                                                    class="text-muted">
                                                                                                    <a href="{{ route('po-detail', $historyData->poDetailRel->po->id) }}"
                                                                                                        target="_blank">
                                                                                                        {{ $historyData->poDetailRel->po->po_no }}
                                                                                                    </a>
                                                                                                </small>
                                                                                            @else
                                                                                                <small
                                                                                                    class="text-muted">
                                                                                                    @if ($historyData->work_order_id !== null)
                                                                                                        <a href="{{ route('work-order.monitoring', $historyData->workOrderRel->id) }}"
                                                                                                            target="_blank">
                                                                                                            {{ $historyData->workOrderRel->number }}
                                                                                                        </a>
                                                                                                    @else
                                                                                                        {{ $historyData->created_at->format('d M Y') }}
                                                                                                    @endif
                                                                                                </small>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if ($inventoryOut->count() > 0)
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header">
                                                                        <button class="accordion-button collapsed"
                                                                            type="button" data-bs-toggle="collapse"
                                                                            data-bs-target="#panelsStayOpen-collapse-out-{{ $inventory_detail->id }}"
                                                                            aria-expanded="true"
                                                                            aria-controls="panelsStayOpen-collapse-out-{{ $inventory_detail->id }}"
                                                                            style="background-color: #c8c8c8; color: #000;">
                                                                            Inventory Out
                                                                        </button>
                                                                    </h2>
                                                                    <div id="panelsStayOpen-collapse-out-{{ $inventory_detail->id }}"
                                                                        class="accordion-collapse collapse show">
                                                                        <div class="accordion-body"
                                                                            style="border: 1px solid #c8c8c8; max-height: 300px; overflow-y: scroll; scrollbar-width: thin;">
                                                                            <ul class="list-group gap-2">
                                                                                @foreach ($inventoryOut as $historyData)
                                                                                    <div
                                                                                        class="list-group-item position-relative">
                                                                                        <div
                                                                                            class="d-flex w-100 justify-content-between">
                                                                                            <div>
                                                                                                <h6 class="mb-1">
                                                                                                    {{ \Carbon\Carbon::parse($historyData->actual_date ?? $historyData->created_at)->format('d M Y') }}
                                                                                                </h6>

                                                                                                <div>
                                                                                                    <small
                                                                                                        class="text-muted">Stock
                                                                                                        Sebelumnya:
                                                                                                        {{ $historyData->stock_before }}</small>
                                                                                                    <br>
                                                                                                    @php
                                                                                                        $isManual =
                                                                                                            !$historyData->prdetail_id &&
                                                                                                            !$historyData->sales_id;
                                                                                                    @endphp
                                                                                                    <small
                                                                                                        class="text-muted">Stock
                                                                                                        Keluar:
                                                                                                        @if ($historyData->is_actual !== 1 && !$isManual)
                                                                                                            {{ $historyData->stock_before - $historyData->stock_after }}
                                                                                                        @elseif(!$isManual && $historyData->is_actual == 1)
                                                                                                            {{ $historyData->stock_change }}
                                                                                                            <span
                                                                                                                class="badge badge-success">(Actual
                                                                                                                Lapangan)</span>
                                                                                                        @endif

                                                                                                        @if ($isManual)
                                                                                                            {{ $historyData->stock_change }}
                                                                                                        @endif

                                                                                                    </small>
                                                                                                    <div class="my-2 w-100 bg-secondary"
                                                                                                        style="height: 1px">
                                                                                                    </div>
                                                                                                    @php
                                                                                                        if (
                                                                                                            $historyData->is_actual !==
                                                                                                            1
                                                                                                        ) {
                                                                                                            $totalStock =
                                                                                                                $historyData->stock_after;
                                                                                                        } elseif (
                                                                                                            $historyData->is_actual ==
                                                                                                            1
                                                                                                        ) {
                                                                                                            $totalStock =
                                                                                                                $historyData->stock_after;
                                                                                                        } else {
                                                                                                            $totalStock =
                                                                                                                $historyData->stock_change;
                                                                                                        }
                                                                                                    @endphp
                                                                                                    <small
                                                                                                        class="text-muted fw-bold">Stock
                                                                                                        Total :
                                                                                                        {{ $totalStock }}</small>
                                                                                                </div>
                                                                                            </div>
                                                                                            @if ($historyData->prdetail_id === null)
                                                                                                @php
                                                                                                    $workOrderRel =
                                                                                                        $historyData->workOrderRel ??
                                                                                                        null;
                                                                                                    $salesRel =
                                                                                                        $historyData->salesRel ??
                                                                                                        null;
                                                                                                @endphp

                                                                                                @if ($workOrderRel)
                                                                                                    <small
                                                                                                        class="text-muted">
                                                                                                        <a href="{{ route('work-order.monitoring', $workOrderRel->id) }}"
                                                                                                            target="_blank">
                                                                                                            {{ $workOrderRel->number }}
                                                                                                        </a>
                                                                                                    </small>
                                                                                                @elseif($salesRel)
                                                                                                    <small
                                                                                                        class="text-muted">
                                                                                                        Completed Sales
                                                                                                    </small>
                                                                                                @endif
                                                                                                @if (!$workOrderRel)
                                                                                                    <small
                                                                                                        class="text-muted">
                                                                                                        {{ $historyData->created_at->format('d M Y') }}
                                                                                                    </small>
                                                                                                @endif
                                                                                            @else
                                                                                                <small
                                                                                                    class="text-muted">
                                                                                                    <a href="{{ route('purchase_request_details.show', $historyData->prDetailRel->purchaseRequest->id) }}"
                                                                                                        target="_blank">
                                                                                                        {{ $historyData->prDetailRel->purchaseRequest->pr_no }}
                                                                                                    </a>
                                                                                                </small>
                                                                                            @endif
                                                                                        </div>
                                                                                        @if ($historyData->notes !== null)
                                                                                            <div class="my-2 w-100 bg-secondary"
                                                                                                style="height: 1px">
                                                                                            </div>
                                                                                            <small
                                                                                                class="text-muted">Notes:
                                                                                                {{ $historyData->notes }}</small>
                                                                                        @endif
                                                                                    </div>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4 d-flex justify-content-end">
                {{ $inventories->links() }}
            </div>
        </div>
    </div>
    @push('javascript')
        <script>
            function initSelect2() {
                $('.dropdownItems').select2({
                    theme: 'bootstrap-5',
                    width: '70%'
                }).on('change', function(e) {
                    @this.set($(this).attr('wire:model'), $(this).val());
                });

                $('#project_id').select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                }).on('change', function(e) {
                    @this.set($(this).attr('wire:model'), $(this).val());
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                initSelect2();

                Livewire.hook('message.processed', (message, component) => {
                    initSelect2();

                    window.addEventListener('close-modal', function() {
                        $('#customModal').css('display', 'none');
                        $('.inventoryOutModal').modal('hide');
                        $('#createWarehouseModal').modal('hide');
                    });
                    // $(document).on('change', '#project_id', function(e) {
                    //     @this.set('projectmodel', e.target.value);
                    // });

                });

                // $(document).ready(function() {
                //     $('#project_id').select2({
                //         theme: 'bootstrap-5'
                //     });


                // });

                $(document).on('click', '#inputItemBtn', function() {
                    $('#customModal').css('display', 'block');
                });

                $(document).on('click', '#inputInventoryOutBtn', function() {
                    $('#customModalInventoryOut').css('display', 'block');
                });

                $(document).on('click', '.custom-modal-close', function() {
                    $('#customModal').css('display', 'none');
                    $('#customModalInventoryOut').css('display', 'none');
                });
            })
        </script>
    @endpush
</div>
