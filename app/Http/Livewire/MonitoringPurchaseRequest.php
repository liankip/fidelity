<?php

namespace App\Http\Livewire;

use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\Task;
use Livewire\Component;

class MonitoringPurchaseRequest extends Component
{
    public $taskData;
    public $project;
    public $checkPurchaseRequest;
    public $taskName;
    public $prData;

    public function mount($taskId)
    {
        $this->taskData = Task::with('project')->find($taskId);
        $this->project = Project::where('id', $this->taskData->project_id)->first();
        $project = $this->project;

        $this->checkPurchaseRequest = PurchaseRequest::where('partof', $this->taskData->task_number)->exists();
    }
    public function render()
    {
        $taskList = Task::where('project_id', $this->taskData->project_id)
            ->where('id', '!=', $this->taskData->id)
            ->where('status', '!=', 'Finish')
            ->get();
        $this->taskName = substr($this->taskData->task_number, -2) . ' - ' . $this->taskData->task;

        $prData = $this->loadData();
        return view('livewire.monitoring-purchase-request', [
            'prData' => $prData,
            'matchFound' => isset($matchFound),
            'taskList' => $taskList,
            'taskName' => $this->taskName
        ]);
    }

    public function loadData()
    {
        $taskNumber = $this->taskData->task_number;
        $getPr = PurchaseRequest::with('project', 'prdetail', 'prdetail.item', 'prdetail.pivotBulkPR', 'prdetail.podetail', 'prdetail.podetailall', 'prdetail.podetail.po', 'prdetail.podetail.po.do', 'prdetail.podetail.po.submition', 'prdetail.podetail.item')->where('is_task', 1)->where('partof', $taskNumber)->get();

        $this->prData = collect($getPr)
            ->sortByDesc('created_at')
            ->groupBy(function ($item) {
                return $item['pr_no'];
            });

        // foreach ($this->prData as $pr) {
        //     foreach ($pr as $prItem) {
        //         foreach ($prItem->prdetail as $prDetail) {
        //             if (!is_null($prDetail->item->rfa)) {
        //                 $rfaData = json_decode($prDetail->item->rfa, true);
        //                 foreach ($rfaData as $rfa) {
        //                     if ($rfa['id'] == $prItem->project->id) {
        //                         $prDetail->is_rfa_exist = true;
        //                     }
        //                 }
        //             }

        // foreach ($prDetail->podetail as $poDetail) {
        // $existInventory = Inventory::where('project_id', $prDetail->purchaseRequest->project_id)
        //     ->where('item_id', $prDetail->item_id)
        //     ->where('task_id', $prDetail->purchaseRequest->task->id)
        //     ->first();

        //             if (!isset($this->actualInput[$prDetail->id]) && !is_null($existInventory)) {
        //                 $this->actualInput[$prDetail->id] = $existInventory->actual_qty;

        //                 $this->actualDate[$prDetail->id] = $existInventory->actual_date;
        //                 $this->actualQtyValue[$prDetail->id] = $existInventory->actual_qty;
        //             }
        //             // }
        //         }
        //     }
        // }

        return $this->prData;
    }
}
