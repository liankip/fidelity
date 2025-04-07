<?php

namespace App\Http\Livewire;

use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Models\InventoryHistory;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\SiteCheckModel;
use App\Models\TaskEngineerDrawing;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class TaskMonitoringPurchaseRequest extends Component
{
    use WithFileUploads;

    public $pr;
    public $prNo;
    public $prData;
    public $isEditActual;
    public $actualInput, $actualDate, $actualQtyValue, $actualNotes;
    public $originalInput;
    public $taskName;
    public $taskList;
    public $relocateTo;

    public $prStatus;
    public $purchaseRequest;
    public $activePO;
    public $inActivePO;

    public $taskData;

    public $nameModel;
    public $descModel;
    public $uploadModel;
    public $taskEngineerDrawing;
    public $subTaskEngineerDrawing;
    public $filterTaskEngineerDrawing;
    public $descriptionDrawing;
    public $outQtyInput;

    protected $listeners = ['loadPrDetails', 'openModal' => 'showModal'];

    public function showModal($prDetailId)
    {
        $this->emit('modalOpened', $prDetailId);
    }

    public function mount($prNo, $pr, $taskData, $taskName, $taskList)
    {
        $prIds = $pr->pluck('id')->toArray();
        $this->prNo = $prNo;
        $this->pr = PurchaseRequest::with(['prdetail.item', 'prdetail.podetail', 'prdetail.purchaseRequest.project', 'prdetail.purchaseRequest.task'])
            ->whereIn('id', $prIds)
            ->get();
        $this->taskData = (object)$taskData;
        $this->taskName = $taskName;
        $this->taskList = $taskList;

        $this->subTaskEngineerDrawing = TaskEngineerDrawing::where('task_id', $this->taskData->id)->get();
        $this->descriptionDrawing = $this->subTaskEngineerDrawing->first()->description ?? '';

        $this->filterTaskEngineerDrawing = $this->subTaskEngineerDrawing->filter(function ($item) {
            return is_null($item->section) && is_null($item->description);
        });

        $this->processPrData();
    }

    private function processPrData()
    {
        $historyData = InventoryHistory::where([
            'type' => 'OUT',
            'is_actual' => 1,
        ])->get();

        foreach ($this->pr as $prItem) {
            foreach ($prItem->prdetail as $prDetail) {
                // Check if RFA exists
                if (!is_null($prDetail->item->rfa)) {
                    $rfaData = json_decode($prDetail->item->rfa, true);
                    $prDetail->is_rfa_exist = collect($rfaData)->contains('id', $prItem->project->id);
                }

                // Check item in inventory
                $itemInventory = Inventory::where([
                    'item_id'    => $prDetail->item_id,
                ])->get();

                // Check existing inventory
                $existInventory = Inventory::where([
                    'project_id' => $prDetail->purchaseRequest->project_id,
                    'item_id'    => $prDetail->item_id,
                    'task_id'    => $prDetail->purchaseRequest->task->id,
                    'prdetail_id' => $prDetail->id,
                ])->first();
                
                if($itemInventory->count() > 1){
                    $checkDetails = InventoryDetail::where([
                        'inventory_id' => $itemInventory->where('actual_qty', null)->first()->id,
                        'project_id' => $prDetail->purchaseRequest->project_id,
                    ])->first();

                    if($checkDetails) {

                        $historyRecord = InventoryHistory::where([
                            'inventory_detail_id' => $checkDetails->id,
                            'type' => 'OUT',
                            'is_actual' => 1,
                        ])
                        ->orderBy('created_at', 'desc')
                        ->first();
    
                        if (!isset($this->actualInput[$prDetail->id]) && $historyRecord) {
                            $this->actualInput[$prDetail->id]    = $historyRecord->stock_after;
                            $this->actualDate[$prDetail->id]     = $itemInventory->whereNotNull('actual_qty')->first()->actual_date;
                            $this->actualQtyValue[$prDetail->id] = $historyRecord->stock_after;
                        }
                    }
                    
                    
                } else {
                    if (!isset($this->actualInput[$prDetail->id]) && $existInventory) {
                        $this->actualInput[$prDetail->id]    = $existInventory->actual_qty;
                        $this->actualDate[$prDetail->id]     = $existInventory->actual_date;
                        $this->actualQtyValue[$prDetail->id] = $existInventory->actual_qty;
                    }
                }
                
            }
        }
    }

    public function saveActual(PurchaseRequestDetail $prDetail, $totalPOQty)
    {
        $this->validate([
            'outQtyInput' => 'required',
            'actualDate' => 'required',
        ], [
            'outQtyInput.required' => 'Quantity is required.',
            'actualDate.required' => 'Date is required.',
        ]);
        
        DB::beginTransaction();

        try {
            // Retrieve inventory item
            $inventoryItem = Inventory::where('item_id', $prDetail->item_id)->first();
            if (!$inventoryItem) {
                throw new \Exception('Inventory item not found.');
            }

            $inventoryDetails = InventoryDetail::where([
                'inventory_id' => $inventoryItem->id,
                'project_id' => $prDetail->purchaseRequest->project_id,
            ])->first();

            // Store previous stock values
            $prevStock = $inventoryDetails->stock ?? 0;
            $newStock = $prevStock - ($this->outQtyInput[$prDetail->id] ?? 0);

            // Update or create inventory record
            $updatedInventory = Inventory::updateOrCreate(
                [
                    'project_id' => $prDetail->purchaseRequest->project_id,
                    'task_id' => $prDetail->purchaseRequest->task->id,
                    'item_id' => $prDetail->item_id,
                    'prdetail_id' => $prDetail->id,
                ],
                [
                    'stock' => $newStock,
                    'actual_qty' => $totalPOQty - $this->outQtyInput[$prDetail->id],
                    'actual_date' => $this->actualDate[$prDetail->id]
                ]
            );

            $inventoryDetails->update([
                'stock' => $newStock
            ]);

            Inventory::where('id', $inventoryItem->id)->update([
               'stock' => $newStock,
            ]);
            // Store inventory history
            InventoryHistory::create(
                [
                    'inventory_detail_id' => $inventoryDetails->id,
                    'type' => 'OUT',
                    'prdetail_id' => $prDetail->id,
                    'is_actual' => 1,

                    'stock_before' => $totalPOQty,
                    'stock_after' => $totalPOQty - $this->outQtyInput[$prDetail->id],
                    'stock_change' => $this->outQtyInput[$prDetail->id],
                    'user_id' => auth()->user()->id,
                    'actual_date' => $this->actualDate[$prDetail->id],
                    'notes' => $this->actualNotes[$prDetail->id] ?? '-'
                ]
            );

            DB::commit();

            return redirect()->route('monitoring-purchase-request', $this->taskData->id)->with('success', 'Data has been saved.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }


    public function cancelEditActual($podetailParam)
    {
        $this->isEditActual = null;
        $this->actualInput[$podetailParam] = $this->originalInput[$podetailParam];
    }

    public function editActual($prDetailIdParam)
    {
        $this->isEditActual = $prDetailIdParam;

        $prData = PurchaseRequestDetail::find($prDetailIdParam);

        $checkActualFieldQty = Inventory::where('project_id', $prData->purchaseRequest->project_id)
            ->where('task_id', $prData->purchaseRequest->task->id)
            ->where('item_id', $prData->item_id)
            ->first();

        if (!is_null($checkActualFieldQty)) {
            $this->originalInput[$prDetailIdParam] = $checkActualFieldQty->actual_qty;
        } else {
            $this->originalInput[$prDetailIdParam] = '';
        }
    }

    public function handleUpload($prDetailId)
    {
        $prDetailParam = PurchaseRequestDetail::find($prDetailId);

        $this->validate([
            'nameModel' => 'required|max:255',
            'descModel' => 'required|max:255',
            'uploadModel' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $fileUpload = $this->uploadModel;
            $filePath = $fileUpload->store('site_uploads', 'public');

            $dataUpload = [
                'originalName' => $fileUpload->getClientOriginalName(),
                'filePath' => $filePath,
            ];

            $jsonFileUpload = json_encode($dataUpload);

            SiteCheckModel::create([
                'project_id' => $this->taskData->project_id,
                'pr_id' => $prDetailParam->id,
                'item_id' => $prDetailParam->item_id,
                'name' => $this->nameModel,
                'description' => $this->descModel,
                'file_upload' => $jsonFileUpload,
            ]);

            DB::commit();
            session()->flash('success', 'Data has been uploaded');
            return redirect()->route('task-monitoring.index', $this->taskData->id);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function render()
    {
        $allInventoryHistory = InventoryHistory::where('is_actual', 1)->get();
        return view('livewire.task-monitoring-purchase-request', [
            'relocatedData' => $this->loadRelocatedData(),
            'allInventoryHistory' => $allInventoryHistory
        ]);
    }

    public function loadRelocatedData()
    {
        $getInventoryRelocate = Inventory::with('newTask')
            ->where('new_task_id', $this->taskData->id)
            ->get();

        $groupInventoryRelocate = $getInventoryRelocate->groupBy(function ($item) {
            return $item->oldTask->task_number;
        });

        foreach ($groupInventoryRelocate as $prNo => $po) {
            foreach ($po as $poItem) {
                if (!is_null($poItem->item->rfa)) {
                    $rfaData = json_decode($poItem->item->rfa, true);
                    foreach ($rfaData as $rfa) {
                        if ($rfa['id'] == $poItem->project->id) {
                            $poItem->is_rfa_exist = true;
                        }
                    }
                }
                $existInventory = Inventory::where('project_id', $poItem->prDetail->purchaseRequest->project_id)
                    ->where('item_id', $poItem->item_id)
                    ->where('task_id', $poItem->prDetail->purchaseRequest->task->id)
                    ->first();

                if (!isset($this->actualInput[$poItem->prDetail->id]) && !is_null($existInventory)) {
                    $this->actualInput[$poItem->prDetail->id] = $existInventory->actual_qty;
                }
            }
        }

        return $groupInventoryRelocate;
    }

    public function handleRelocate(PurchaseRequestDetail $prDetail)
    {
        DB::beginTransaction();

        try {
            $projectId = $prDetail->purchaseRequest->project_id;
            $taskId = $this->taskData->id;
            $itemId = $prDetail->item_id;

            $existInventory = Inventory::where('project_id', $projectId)->where('item_id', $itemId)->where('task_id', $taskId)->first();
            $existInventory->update([
                'prdetail_id' => $prDetail->id,
                'new_task_id' => $this->relocateTo,
            ]);
            DB::commit();
            session()->flash('success', 'Data has been relocated');
            return redirect()->route('task-monitoring.index', $this->taskData->id);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
