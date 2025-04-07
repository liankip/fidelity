@php use App\Models\Inventory;use App\Models\PurchaseRequest;use App\Models\Task;use App\Permissions\Permission;use Illuminate\Support\Facades\DB;
@endphp
<div>
    @php
        $prStatus = PurchaseRequest::where('pr_no', $this->prNo)->first()->status ?? null;
        $purchaseRequest = '';
        $activePO = 0;
        $inActivePO = 0;

        foreach ($pr as $prItem) {
            $purchaseRequest = $prItem;
            foreach ($prItem->prdetail as $prDetail) {
                foreach ($prDetail->podetailall as $podetail) {
                    if ($podetail->po->po_no) {
                        $activePO++;

                        $projectId = $podetail->prdetail->purchaseRequest->project_id;
                        $taskId = $taskData->id;
                        $itemId = $podetail->item_id;

                        $existNewTask = Inventory::where('project_id', $projectId)
                            ->where('item_id', $itemId)
                            ->where('task_id', $taskId)
                            ->first();

                        if ($existNewTask && $existNewTask->new_task_id !== null) {
                            $existTaskId = $existNewTask->new_task_id;
                            $newTaskName = Task::where('id', $existTaskId)->first()->task ?? '';
                            $newTaskNumber = Task::where('id', $existTaskId)->first()->task_number ?? null;
                        }

                        $zeroActualField =
                            isset($actualInput[$podetail->id]) &&
                            $actualInput[$podetail->id] !== null &&
                            $actualInput[$podetail->id] == 0;

                        if (($existNewTask && $existNewTask->new_task_id !== null) || $zeroActualField) {
                            $inActivePO++;
                        }
                    }
                }
            }
        }
    @endphp

    @foreach ($pr as $prItem)
        @php
            $purchaseRequest = $prItem;
        @endphp
    @endforeach

    <div class="card-body">
        @if ($prStatus == 'Draft')
            <h3 class="badge badge-secondary primary-color-sne">Draft PR</h3>
        @elseif ($prStatus == 'New')
            <h3 class="primary-color-sne">PR No: {{ $prNo }} <span class="badge badge-primary">New</span>
            </h3>
        @else
            <h3 class="primary-color-sne">PR No: {{ $prNo }}
                @if (count($purchaseRequest->prdetail) !== 0 &&
                        count($purchaseRequest->prdetail) == $activePO &&
                        count($purchaseRequest->prdetail) != $inActivePO)
                    <span class="badge badge-success">Aktif</span>
                @elseif(count($purchaseRequest->prdetail) !== 0 && count($purchaseRequest->prdetail) == $inActivePO)
                    <span class="badge badge-danger">Tidak Aktif</span>
                @endif
                @if (strtolower($purchaseRequest->status) == 'wait for approval')
                    <span class="badge badge-warning">Wait For Approval</span>
                @endif
            </h3>
        @endif

        @if (count($purchaseRequest->prdetail) != 0)
            <div style="overflow: auto">
                <table class="table primary-box-shadow table-bordered text-center w-100">
                    <thead class="thead-light">
                    <tr>
                        <th class="border-top-left">Item</th>
                        <th>RFA</th>
                        <th colspan="2">Requested</th>
                        <th rowspan="2" class="align-middle">PO</th>
                        <th>Value</th>
                        <th>Delivery</th>
                        <th colspan="2">Actual PO</th>
                        <th colspan="2">Sisa Lapangan</th>
                        <th>Site Check</th>
                        <th class="border-top-right">Relocate Waste to</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th></th>
                        <th>Date</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th></th>
                        <th>WBS No</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $pr = collect($pr)->map(function ($item) {
                            $item = (object) $item;
                            $item->prdetail = collect($item->prdetail)->map(function ($detail) {
                                $detail = (object) $detail;
                                if (isset($detail->item)) {
                                    $detail->item = (object) $detail->item;
                                }
                                return $detail;
                            });
                            return $item;
                        });
                    @endphp
                    @foreach ($pr as $prItem)
                        @foreach ($prItem->prdetail as $prDetail)
                            <tr>
                                <td>{{ $prDetail->item->name }}</td>
                                <td>
                                    @php
                                        $prDetail->podetail = collect($prDetail->podetail);

                                        $buttonClass = isset($prDetail->is_rfa_exist) && $prDetail->is_rfa_exist
                                            ? 'badge badge-success'
                                            : 'badge badge-danger';
                                        $textValue = isset($prDetail->is_rfa_exist) && $prDetail->is_rfa_exist ? 'Ada' : 'Belum';
                                    @endphp
                                    <span class=" {{ $buttonClass }}">{{ $textValue }}</span>
                                </td>
                                <td>{{ rtrim(rtrim(number_format($prDetail->qty, 2, ',', '.'), '0'), ',') }}
                                </td>
                                <td>{{ $prDetail->unit }}</td>
                                @if ($prDetail->podetail->count() > 0 || $prDetail->is_bulk == 1)
                                    @php
                                        if ($prDetail->podetail->count() > 0) {
                                            $prDetail->podetail = $prDetail->podetail;
                                        } else {
                                            $prDetail->podetail = $prDetail->pivotBulkPR;
                                        }
                                    @endphp
                                    <td class="align-middle">
                                        @foreach (collect($prDetail->podetail) as $podetail)
                                            @if (isset($podetail->po) && $podetail->po->po_no)
                                                <span
                                                    class="badge badge-success mb-2">{{ $podetail->po->po_no }}</span>
                                            @else
                                                <span
                                                    class="badge badge-danger mt-2">PO Belum Diajukan</span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $totalAmount = collect($prDetail->podetail)
                                                ->filter(function ($item) {
                                                    return isset($item->po) && $item->po->po_no !== null;
                                                })
                                                ->sum('amount');
                                        @endphp
                                        Rp. {{ number_format($totalAmount) }}
                                    </td>
                                    <td class="align-middle">
                                        @foreach ($prDetail->podetail as $podetail)
                                            @if (isset($podetail->po) && $podetail->po && $podetail->po->po_no)
                                                @if ($podetail->po->status === 'Approved')
                                                    @if ($podetail->po->totalDo() > 0)
                                                        @foreach ($podetail->po->do as $do)
                                                            <span
                                                                class="badge badge-success mb-2">{{ $do->created_at->format('j M Y') }}</span>
                                                        @endforeach
                                                    @else
                                                        <a href="{{ route('create_do', ['id' => $podetail->po->id]) }}"
                                                           class="btn btn-sm btn-primary" target="_blank">Upload Surat
                                                            Jalan</a>
                                                    @endif
                                                @else
                                                    <span
                                                        class="badge badge-danger">PO {{ $podetail->po->status }}</span>
                                                @endif
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $completedPoDetail = collect($prDetail->podetail->where('percent_complete', 100));
                                        @endphp
                                        @foreach ($completedPoDetail as $podetail)
                                            @if (isset($podetail->po) && $podetail->po && $podetail->po->po_no)
                                                @if ($podetail->po->status === 'Approved' || $podetail->po->status === 'Paid')
                                                    @if ($podetail->po->totalDo() > 0)
                                                        @php
                                                            $itemSubmitted = false;
                                                        @endphp

                                                        
                                                            @if ($podetail->po->hasSubmition())
                                                                @foreach ($podetail->po->submition as $submition)
                                                                    @if ($podetail->item->id == $submition->item_id && $podetail->qty == $submition->qty)
                                                                        @php
                                                                            $itemSubmitted = true;
                                                                        @endphp
                                                                        <p>{{ $submition->qty }}</p>
                                                                    @endif
                                                                @endforeach

                                                                @if (!$itemSubmitted)
                                                                    <a href="{{ route('create_submition', $podetail->id) }}"
                                                                       class="btn btn-sm btn-primary"
                                                                       target="_blank">Upload Foto</a>
                                                                @endif
                                                            @else
                                                                <a href="{{ route('create_submition', $podetail->id) }}"
                                                                   class="btn btn-sm btn-primary"
                                                                   target="_blank">Upload Foto</a>
                                                            @endif
                                                        
                                                    @else
                                                    @endif
                                                @else
                                                @endif
                                            @else
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $allPoDetails = collect($prDetail->podetail)->every(function ($detail) {
                                                return isset($detail->po) && in_array($detail->po->status, ['Approved', 'Paid']);
                                            });
                                        @endphp

                                        @if ($allPoDetails === true)
                                            {{ $prDetail->item->unit }}
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $totalPoDetail = count($prDetail->podetail);

                                            $poDetails = $prDetail->podetail->map(function ($detail) {
                                                return [
                                                    'purchase_order_id' => $detail['purchase_order_id'],
                                                    'item_id' => $detail['item_id'],
                                                ];
                                            });

                                            $allSubmitted = $poDetails->every(function ($detail) {
                                                return DB::table('submition_histories')
                                                    ->where('po_id', $detail['purchase_order_id'])
                                                    ->where('item_id', $detail['item_id'])
                                                    ->exists();
                                            });
                                        @endphp

                                        @if ($allSubmitted == true)
                                            @if ($isEditActual === $prDetail->id)
                                                <input type="text" class="form-control"
                                                       wire:model="actualInput.{{ $prDetail->id }}"
                                                       style="width: 80px">
                                            @else
                                            @php
                                                $existingActualQty = null;
                                                if(isset($actualQtyValue[$prDetail->id])) {
                                                    $existingActualQty = $actualQtyValue[$prDetail->id];
                                                }
                                            @endphp
                                                <input type="text" class="form-control"
                                                       value="{{ $existingActualQty !== null ? $existingActualQty : '' }}"
                                                       style="width: 80px" disabled>
                                            @endif

                                            <div class="d-flex gap-1 mt-1">
                                                <button class="btn btn-sm btn-primary"
                                                                data-bs-toggle="modal" data-bs-target="#actualFieldModal-{{ $prDetail->id }}">
                                                            <i class="fas fa-pen"></i>
                                                </button>

                                                @if(isset($actualInput[$prDetail->id]))
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#historyLapanganModal-{{ $prDetail->id }}">
                                                        <i class="fas fa-rectangle-list"></i>
                                                    </button>

                                                    <!-- History Lapangan Modal -->
                                                    <div class="modal fade" id="historyLapanganModal-{{ $prDetail->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="false" wire:ignore.self>
                                                        <div class="modal-dialog">
                                                        <div class="modal-content" style="border: 1px solid #080808">
                                                            <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Histori Lapangan {{ $prDetail->item->name }}</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @php
                                                                    $filteredHistory = $allInventoryHistory->where('prdetail_id', $prDetail->id);
                                                                @endphp
                                                                <ul class="list-group gap-2">
                                                                    @foreach ($filteredHistory as $history)
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <div class="text-start">
                                                                                <p>
                                                                                    {{ $history->created_at->format('d-M-Y') }}
                                                                                </p>
                                                                                <p style="font-size: 9pt; font-weight: 500; color: #080808">Notes: {{ $history->notes }}</p>
                                                                            </div>
                                                                            <span class="badge bg-primary rounded-pill">Quantity Keluar: {{ $history->stock_change }}</span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                            <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-primary"
                                                                >Save changes</button>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Actual Field Modal -->
                                                    <div class="modal fade" id="actualFieldModal-{{ $prDetail->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="false" wire:ignore.self>
                                                        <div class="modal-dialog">
                                                        <div class="modal-content" style="border: 1px solid #080808">
                                                            <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Sisa Lapangan</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="actualQty" class="form-label w-100 text-start">Quantity Keluar<span class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control" id="actualQty" wire:model="outQtyInput.{{ $prDetail->id }}" @if(isset($actualInput[$prDetail->id]))
                                                                        max="{{ $actualInput[$prDetail->id] }}"
                                                                    @endif>
                                                                    @error('outQtyInput')
                                                                        <p class="text-danger text-start w-100">{{ $message }}</p>
                                                                    @enderror
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="actualDate" class="form-label w-100 text-start">Tanggal Keluar <span class="text-danger">*</span></label>
                                                                    <input type="date" class="form-control" id="actualDate" wire:model="actualDate.{{ $prDetail->id }}">
                                                                    @error('actualDate')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="keterangan" class="form-label w-100 text-start">Keterangan</label>
                                                                    <textarea class="form-control" id="keterangan" wire:model="actualNotes.{{ $prDetail->id }}"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                @php
                                                                    $totalPODetail = $prDetail->podetail->where('percent_complete', 100)->sum('qty');

                                                                    if(isset($actualInput[$prDetail->id])) {
                                                                        $totalPODetail = $actualInput[$prDetail->id];
                                                                    }
                                                                @endphp
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-primary" wire:loading.attr="disabled" wire:target="actualInput.{{ $prDetail->id }}, actualDate.{{ $prDetail->id }}, actualNotes.{{ $prDetail->id }}, saveActual" wire:click="saveActual({{ $prDetail }}, {{ $totalPODetail }})"  
                                                            @if(isset($actualInput[$prDetail->id]) && isset($outQtyInput[$prDetail->id]) && $outQtyInput[$prDetail->id] > $actualInput[$prDetail->id])
                                                                disabled
                                                            @elseif(isset($outQtyInput[$prDetail->id]) && $outQtyInput[$prDetail->id] > $totalPODetail)
                                                                disabled
                                                            @endif
                                                                >Save changes</button>
                                                            </div>
                                                        </div>
                                                        </div>
                                                    </div>
                                                {{-- @if ($isEditActual === $prDetail->id)
                                                    <button class="btn btn-sm btn-success"
                                                            wire:click="saveActual({{ $prDetail }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>

                                                    <button class="btn btn-sm btn-danger"
                                                            wire:click="cancelEditActual({{ $prDetail->id }})">
                                                        <i class="fas fa-close"></i>
                                                    </button>
                                                @else
                                                    @if ($isEditActual === null)
                                                        <button class="btn btn-sm btn-primary"
                                                                wire:click="editActual({{ $prDetail->id }})">
                                                            <i class="fas fa-pen"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-primary" disabled>
                                                            <i class="fas fa-pen"></i>
                                                        </button>
                                                    @endif
                                                @endif --}}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="align-middle">
                                        @if ($allSubmitted == true)
                                            {{ $prDetail->item->unit }}
                                        @endif
                                    </td>

                                    <td class="align-middle">
                                        @php
                                            $siteUploaded = \App\Models\SiteCheckModel::where('project_id', $purchaseRequest->project_id                                        )
                                                    ->where('pr_id', $prDetail->id)
                                                    ->where('item_id', $prDetail->item_id)
                                                    ->first();
                                        @endphp
                                        @if ($siteUploaded)
                                            @php
                                                $jsonFileUpload = json_decode($siteUploaded->file_upload, true);
                                            @endphp

                                            <button type="button" class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailModal-{{ $prDetail->id }}">
                                                Detail
                                            </button>

                                            <div class="modal fade" id="detailModal-{{ $prDetail->id }}"
                                                 tabindex="-1" aria-labelledby="exampleModalLabel"
                                                 aria-hidden="true" wire:key="{{ $prDetail->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5"
                                                                id="exampleModalLabel">
                                                                Detail
                                                                {{ $prDetail->item->name }}
                                                            </h1>
                                                            <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <h5 class="fw-bold">Nama
                                                                    PIC</h5>
                                                                <p class="text-muted">
                                                                    {{ $siteUploaded->name }}</p>
                                                            </div>

                                                            <div class="mb-3">
                                                                <h5 class="fw-bold">
                                                                    Keterangan</h5>
                                                                <p class="text-muted">
                                                                    {{ $siteUploaded->description }}</p>
                                                            </div>

                                                            <div class="mb-3">
                                                                <h5 class="fw-bold">
                                                                    Foto</h5>
                                                                <img
                                                                    src="{{ Storage::url($jsonFileUpload['filePath']) }}"
                                                                    alt="Image"
                                                                    class="img-fluid rounded shadow-sm">
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">
                                                                Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal-{{ $prDetail->id }}">
                                                Upload
                                                Form
                                            </button>

                                            <div class="modal" id="exampleModal-{{ $prDetail->id }}"
                                                 aria-labelledby="exampleModalLabel"
                                                 aria-hidden="true" wire:key="{{ $prDetail->id }}"
                                                 data-bs-backdrop="false"
                                                 wire:ignore>
                                                    <form wire:submit.prevent="handleUpload({{ $prDetail->id }})">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                                        {{ $prDetail->item->name }}
                                                                    </h1>
                                                                    <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group text-start">
                                                                        <label for="nama">Nama
                                                                            PIC<span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control"
                                                                               id="nama" required
                                                                               wire:model="nameModel">
                                                                    </div>
                                                                    <div class="form-group text-start">
                                                                        <label for="keterangan">Keterangan
                                                                            <span
                                                                                class="text-danger">*</span></label>
                                                                        <textarea class="form-control"
                                                                                  id="keterangan"
                                                                                  required
                                                                                  wire:model="descModel"></textarea>
                                                                    </div>
                                                                    <div class="form-group text-start">
                                                                        <label for="uploadFile">Upload
                                                                            foto
                                                                            <span
                                                                                class="text-danger">*</span></label>
                                                                        <input type="file" class="form-control"
                                                                               id="uploadFile" required
                                                                               wire:model="uploadModel"
                                                                               accept=".pdf,.jpg,.jpeg,.png">
                                                                        <p class="text-muted" wire:loading
                                                                           wire:target="uploadModel">
                                                                            Uploading...
                                                                        </p>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">
                                                                        Close
                                                                    </button>
                                                                    <button type="submit"
                                                                            class="btn btn-primary"
                                                                            wire:loading.attr="disabled">
                                                                        Save
                                                                        changes
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                            </div>
                                        @endif

                                    </td>

                                    <td class="align-middle">
                                        @if ($siteUploaded)
                                            @php
                                                $projectId = $prDetail->purchaseRequest->project_id;
                                                $taskId = $taskData->id;
                                                $itemId = $prDetail->item_id;

                                                $existNewTask = Inventory::where('project_id', $projectId)
                                                    ->where('item_id', $itemId)
                                                    ->where('task_id', $taskId)
                                                    ->first();

                                                if ($existNewTask && $existNewTask->new_task_id !== null) {
                                                    $existTaskId = $existNewTask->new_task_id;
                                                    $newTaskName =
                                                        Task::where('id', $existTaskId)->first()->task ??
                                                        $existNewTask->new_task_id;
                                                    $newTaskNumber =
                                                        Task::where('id', $existTaskId)->first()->task_number ??
                                                        null;
                                                }
                                            @endphp

                                            @if ($existNewTask && $existNewTask->new_task_id !== null)
                                                <a href="{{ route('task-monitoring.index', ['taskId' => $existTaskId]) }}"
                                                   class="btn btn-success btn-sm">
                                                    Relocated
                                                    to {{ substr($newTaskNumber, -2) }}
                                                    - {{ $newTaskName }}
                                                </a>
                                            @else
                                                @if (isset($actualInput[$prDetail->id]) &&
                                                        $actualInput[$prDetail->id] !== '' &&
                                                        $actualInput[$prDetail->id] !== null &&
                                                        $actualInput[$prDetail->id] !== 0)
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#relocateModal-{{ $prDetail->id }}">
                                                        Relocate
                                                    </button>
                                                @endif
                                            @endif

                                            <div class="modal fade" id="relocateModal-{{ $prDetail->id }}"
                                                 tabindex="-1" aria-labelledby="exampleModalLabel"
                                                 aria-hidden="true" wire:key="{{ $prDetail->id }}"
                                                 wire:ignore>
                                                <div class="modal-dialog">
                                                    <form
                                                        wire:submit.prevent="handleRelocate({{ $prDetail }})">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5"
                                                                    id="exampleModalLabel">
                                                                    Relocate -
                                                                    <span
                                                                        class="fw-light">{{ $prDetail->item->name }}</span>
                                                                </h1>
                                                                <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label
                                                                                for="fromsite">From</label>
                                                                            <input type="text"
                                                                                   class="form-control"
                                                                                   id="fromsite"
                                                                                   value="{{ $taskName }}"
                                                                                   readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="tosite">To</label>
                                                                            <select name="tosite"
                                                                                    id="tosite"
                                                                                    wire:model="relocateTo"
                                                                                    class="form-control"
                                                                                    required>
                                                                                <option value="">
                                                                                    --
                                                                                    Pilih
                                                                                    Task
                                                                                    --
                                                                                </option>
                                                                                <option value="Gudang">
                                                                                    Gudang
                                                                                </option>
                                                                                @foreach ($taskList as $task)
                                                                                    <option
                                                                                        value="{{ $task->id }}">
                                                                                        {{ substr($task->task_number, -2) }}
                                                                                        - {{ $task->task }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button"
                                                                        class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">
                                                                    Close
                                                                </button>
                                                                <button type="submit"
                                                                        class="btn btn-primary">Save changes
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                        <div class="d-flex justify-content-end mb-2">
                            @if ($purchaseRequest->status == 'Draft' && count($purchaseRequest->prdetail))
                                @can(Permission::AJUKAN_PR)
                                    <form
                                        action="{{ route('ajukan.purchase-request', ['id' => $purchaseRequest->id]) }}"
                                        method="POST" class="mb-2">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-primary mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                 fill="currentColor" class="bi bi-box-arrow-in-left"
                                                 viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                      d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0v-2z"/>
                                                <path fill-rule="evenodd"
                                                      d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
                                            </svg>
                                            Ajukan Purchase Request
                                        </button>
                                    </form>
                                @endcan
                            @endif
                            @if (
                                $purchaseRequest->status != 'Processed' &&
                                    ($purchaseRequest->status != 'Cancel' &&
                                        $purchaseRequest->status != 'Draft' &&
                                        strtolower($purchaseRequest->status) != 'wait for approval'))
                                @if (auth()->user()->hasGeneralAccess())
                                    @if ($purchaseRequest->pr_type == 'Barang')
                                        @can(Permission::CREATE_PO)
                                            <a class="btn btn-primary mb-2"
                                               href="/purchase_order/chooceitempr/{{ $purchaseRequest->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                     height="14" fill="currentColor"
                                                     class="bi bi-clipboard2-plus" viewBox="0 0 16 16">
                                                    <path
                                                        d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z"/>
                                                    <path
                                                        d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-12Z"/>
                                                    <path
                                                        d="M8.5 6.5a.5.5 0 0 0-1 0V8H6a.5.5 0 0 0 0 1h1.5v1.5a.5.5 0 0 0 1 0V9H10a.5.5 0 0 0 0-1H8.5V6.5Z"/>
                                                </svg>
                                                Create PO
                                            </a>
                                        @endcan
                                    @endif
                                    @if ($purchaseRequest->pr_type != 'Barang')
                                        @can(Permission::CREATE_PO)
                                            <a class="btn btn-primary mb-2"
                                               href="/spk/{{ $purchaseRequest->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                     height="14" fill="currentColor"
                                                     class="bi bi-clipboard2-plus" viewBox="0 0 16 16">
                                                    <path
                                                        d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z"/>
                                                    <path
                                                        d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-12Z"/>
                                                    <path
                                                        d="M8.5 6.5a.5.5 0 0 0-1 0V8H6a.5.5 0 0 0 0 1h1.5v1.5a.5.5 0 0 0 1 0V9H10a.5.5 0 0 0 0-1H8.5V6.5Z"/>
                                                </svg>
                                                Create SPK
                                            </a>
                                        @endcan
                                    @endif
                                @endif
                            @endif
                            @if ($purchaseRequest->status == 'Draft')
                                <a href="{{ route('itempr.edit', $prItem->id) }}"
                                   class="btn btn-warning mb-2">
                                    <i class="fas fa-pencil"></i> Edit
                                </a>
                            @endif

                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <a class="btn btn-danger" style="width: 20%"
                   href="{{ route('itempr.index', $purchaseRequest->id) }}">Lanjutkan
                    Purchase Request</a>
        @endif
    </div>

    @if ($taskData->section != 'Consumables' && $taskData->section != 'Indent' && $filterTaskEngineerDrawing->isNotEmpty())
        <div class="card p-4 primary-box-shadow">
            <div class="card-header">
                <h4>List Engineer Drawing</h4>
            </div>
            <ul>
                @forelse ($filterTaskEngineerDrawing as $d)
                    <li>
                        <a href="{{ Storage::url($d->file) }}"
                           target="_blank">{{ $d->original_filename }}</a> was
                        uploaded
                        {{ \Carbon\Carbon::parse($d->created_at)->format('Y-m-d H:i:s') }}
                    </li>
                @empty
                    <li>No Engineer Drawing Uploaded</li>
                @endforelse
            </ul>
        </div>
    @endif

    @if (count($relocatedData) > 0)
        @foreach ($relocatedData as $prNo => $pr)
            <div class="card p-4" wire:key="pr-{{ $prNo }}">
                @php
                    $prStatus = \App\Models\PurchaseRequest::where('partof', $prNo)->first()->status;
                    $prTask = \App\Models\PurchaseRequest::where('partof', $prNo)->first()->task->task;
                @endphp
                @if ($prStatus == 'Draft')
                    <h3 class="badge badge-secondary">Draft PR</h3>
                @elseif ($prStatus == 'New')
                    <h3>Relocated From Task No: {{ $prNo }} <span class="badge badge-primary">New</span>
                    </h3>
                @else
                    <h3>Relocated From Task {{ substr($prNo, -2) }} - {{ $prTask }}</h3>
                @endif
                <table class="table table-bordered text-center table-responsive w-100">
                    <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th>RFA</th>
                        <th colspan="2">Requested</th>
                        <th rowspan="2" class="align-middle">PO</th>
                        <th>Value</th>
                        <th>Delivery</th>
                        <th colspan="2">Actual PO</th>
                        <th colspan="2">Sisa Lapangan</th>
                        <th>Site Check</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th></th>
                        <th>Date</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th></th>
                        <th>Task No</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($pr as $poItem)
                        <tr>
                            <td>{{ $poItem->item->name }}</td>
                            <td>
                                @php
                                    $buttonClass = $poItem->is_rfa_exist
                                        ? 'badge badge-success'
                                        : 'badge badge-danger';
                                    $textValue = $poItem->is_rfa_exist ? 'Ada' : 'Belum';
                                @endphp
                                <span class=" {{ $buttonClass }} text-white">{{ $textValue }}</span>
                            </td>
                            <td>{{ $poItem->prDetail->qty }}</td>
                            <td>{{ $poItem->item->unit }}</td>

                            Check PO
                            @php
                                $prDetailData = $poItem->prDetail;
                            @endphp

                            <td class="align-middle">
                                @foreach ($prDetailData->podetail as $podetail)
                                    @if ($podetail->po->po_no)
                                        @if ($podetail->po->status === 'Approved')
                                            <span
                                                class="badge badge-success mb-2">{{ $podetail->po->po_no }}</span>
                                        @else
                                            <span class="badge badge-danger">PO
                                                        {{ $podetail->po->status }}</span>
                                        @endif
                                    @else
                                    @endif
                                @endforeach
                            </td>

                            Amount Value
                            <td class="align-middle">
                                @php
                                    $totalAmounttes = $prDetailData->podetail
                                        ->filter(function ($item) {
                                            return $item->po->po_no !== null;
                                        })
                                        ->sum('amount');
                                @endphp
                                Rp. {{ number_format($totalAmounttes) }}
                            </td>

                            <td class="align-middle">
                                @foreach ($prDetailData->podetail as $podetail)
                                    @if ($podetail->po->po_no)
                                        @if ($podetail->po->status === 'Approved')
                                            @if ($podetail->po->totalDo() > 0)
                                                @foreach ($podetail->po->do as $do)
                                                    <span
                                                        class="badge badge-success mb-2">{{ $do->created_at->format('j M Y') }}</span>
                                                @endforeach
                                            @else
                                            @endif
                                        @else
                                            <span class="badge badge-danger">PO
                                                        {{ $podetail->po->status }}</span>
                                        @endif
                                    @else
                                    @endif
                                @endforeach
                            </td>

                            {{-- Actual PO QTY --}}
                            <td class="align-middle">
                                @php
                                    $completedPoDetail = collect($prDetailData->podetail->where('percent_complete', 100));
                                @endphp
                                @foreach ($completedPoDetail as $podetail)
                                    @if ($podetail->po->po_no)
                                        @if ($podetail->po->status === 'Approved')
                                            @if ($podetail->po->totalDo() > 0)
                                                @php
                                                    $itemSubmitted = false;
                                                @endphp

                                                    @if ($podetail->po->hasSubmition())
                                                        @foreach ($podetail->po->submition as $submition)
                                                            @if ($podetail->item->id == $submition->item_id && $podetail->qty == $submition->qty)
                                                                @php
                                                                    $itemSubmitted = true;
                                                                @endphp
                                                                <p>{{ $podetail->qty }}</p>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                    @endif
                                            @else
                                            @endif
                                        @else
                                        @endif
                                    @else
                                    @endif
                                @endforeach
                            </td>

                            Actual PO Unit
                            <td class="align-middle">
                                @php
                                    $allPoDetails = $prDetailData->podetail->every(function ($detail) {
                                        return $detail->po->status === 'Approved';
                                    });
                                @endphp

                                @if ($allPoDetails === true)
                                    {{ $prDetailData->item->unit }}
                                @endif
                            </td>

                            Actual Field Qty
                            <td class="align-middle">
                                @php
                                    $totalPoDetail = count($prDetailData->podetail);

                                    $poDetails = $prDetailData->podetail->map(function ($detail) {
                                        return [
                                            'purchase_order_id' => $detail->purchase_order_id,
                                            'item_id' => $detail->item_id,
                                        ];
                                    });

                                    $allSubmitted = $poDetails->every(function ($detail) {
                                        return DB::table('submition_histories')
                                            ->where('po_id', $detail['purchase_order_id'])
                                            ->where('item_id', $detail['item_id'])
                                            ->exists();
                                    });
                                @endphp

                                @if ($allSubmitted == true)
                                    @if ($isEditActual === $prDetailData->id)
                                        <input type="text" class="form-control"
                                               wire:model="actualInput.{{ $prDetailData->id }}"
                                               style="width: 80px">
                                    @else
                                        <input type="text" class="form-control"
                                               wire:model="actualInput.{{ $prDetailData->id }}"
                                               style="width: 80px"
                                               disabled>
                                    @endif

                                    <div class="d-flex gap-1 mt-1">
                                        @if ($isEditActual === $prDetailData->id)
                                            <button class="btn btn-sm btn-success"
                                                    wire:click="saveActual({{ $prDetailData }})">
                                                <i class="fas fa-check"></i>
                                            </button>

                                            <button class="btn btn-sm btn-danger"
                                                    wire:click="cancelEditActual({{ $prDetailData->id }})">
                                                <i class="fas fa-close"></i>
                                            </button>
                                        @else
                                        @endif
                                    </div>
                                @endif
                            </td>

                            Actual Field Unit
                            <td class="align-middle">
                                @if ($allSubmitted == true)
                                    {{ $prDetailData->item->unit }}
                                @endif
                            </td>

                            Site Check
                            <td class="align-middle">
                                @php
                                    $siteUploaded = \App\Models\SiteCheckModel::where(
                                        'project_id',
                                        $prDetailData->purchaseRequest->project_id,
                                    )
                                        ->where('pr_id', $prDetailData->id)
                                        ->where('item_id', $prDetailData->item_id)
                                        ->first();
                                @endphp

                                @if (isset($actualInput[$prDetailData->id]) &&
                                        $actualInput[$prDetailData->id] !== '' &&
                                        $actualInput[$prDetailData->id] !== null &&
                                        $actualInput[$prDetailData->id] !== 0)
                                    @if ($siteUploaded)
                                        @php
                                            $jsonFileUpload = json_decode($siteUploaded->file_upload, true);
                                        @endphp

                                        <button type="button" class="btn btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#detailModal-{{ $prDetailData->id }}">
                                            Detail
                                        </button>

                                        <div class="modal fade" id="detailModal-{{ $prDetailData->id }}"
                                             tabindex="-1" aria-labelledby="detailModalLabel"
                                             aria-hidden="true"
                                             wire:key="{{ $prDetailData->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5"
                                                            id="detailModalLabel">
                                                            Detail
                                                            {{ $prDetailData->item->name }}
                                                        </h1>
                                                        <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <h5 class="fw-bold">Nama
                                                                PIC</h5>
                                                            <p class="text-muted">{{ $siteUploaded->name }}</p>
                                                        </div>

                                                        <div class="mb-3">
                                                            <h5 class="fw-bold">
                                                                Keterangan</h5>
                                                            <p class="text-muted">{{ $siteUploaded->description }}
                                                            </p>
                                                        </div>

                                                        <div class="mb-3">
                                                            <h5 class="fw-bold">
                                                                Foto</h5>
                                                            <img
                                                                src="{{ Storage::url($jsonFileUpload['filePath']) }}"
                                                                alt="Image"
                                                                class="img-fluid rounded shadow-sm">
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">
                                                            Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal-{{ $prDetail->id }}">
                                            Upload Form
                                        </button>

                                        <div class="modal fade" id="exampleModal-{{ $prDetail->id }}"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true"
                                             wire:key="{{ $prDetail->id }}" wire:ignore.self>
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                            {{ $prDetailData->item->name }}
                                                        </h1>
                                                        <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group text-start">
                                                            <label for="nama">Nama
                                                                PIC<span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                   id="nama" required
                                                                   wire:model="nameModel">
                                                        </div>
                                                        <div class="form-group text-start">
                                                            <label for="keterangan">Keterangan
                                                                <span
                                                                    class="text-danger">*</span></label>
                                                            <textarea class="form-control"
                                                                      id="keterangan" required
                                                                      wire:model="descModel"></textarea>
                                                        </div>
                                                        <div class="form-group text-start">
                                                            <label for="uploadFile">Upload
                                                                foto
                                                                <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="file" class="form-control"
                                                                   id="uploadFile" required
                                                                   wire:model="uploadModel"
                                                                   accept=".pdf,.jpg,.jpeg,.png">
                                                            <p class="text-muted" wire:loading
                                                               wire:target="uploadModel">
                                                                Uploading...
                                                            </p>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button"
                                                                class="btn btn-secondary"
                                                                data-bs-dismiss="modal">
                                                            Close
                                                        </button>
                                                        <button type="submit"
                                                                class="btn btn-primary"
                                                                wire:loading.attr="disabled">
                                                            Save
                                                            changes
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            </form>
                                            <button class="btn btn-info me-1" wire:click="export_boq"><i
                                                    class="fa-solid fa-download"></i>
                                                Export BOQ
                                            </button>

                                        </div>
                                    @endif
                                @endif
                            </td>


                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        @endforeach
    @endif
</div>
