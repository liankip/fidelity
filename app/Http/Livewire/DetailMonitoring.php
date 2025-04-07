<?php

namespace App\Http\Livewire;

use App\Models\InventoryDetail;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\SiteCheckModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class DetailMonitoring extends Component
{
    use WithFileUploads;
    public $prId;
    public $prData;
    public $poDetailList;

    public $searchTerm = '';

    public $nameModel;
    public $descModel;
    public $uploadModel;

    public $relocateTo;


    public function mount($prId)
    {
        $this->prId = $prId;
    }
    public function render()
    {
        $this->prData = PurchaseRequest::with('project')->where('id', $this->prId)->first();
        $this->poDetailList = $this->prData->purchaseRequestDetails;

        $poList = $this->prData->po;
        $poDetail = [];
        foreach ($poList as $po) {
            foreach ($po->podetail as $podetail) {
                $poDetail[] = $podetail;
            }
        }

        foreach ($poDetail as $podetail) {
            if (!is_null($podetail->item->rfa)) {
                $rfaData = json_decode($podetail->item->rfa, true);
                foreach ($rfaData as $rfa) {
                    if ($rfa['id'] == $this->prData->project->id) {
                        $podetail->is_rfa_exist = true;
                    }
                }
            }
        }
        $this->poDetailList = $this->applySearchFilter($poDetail);


        $inventoryItems = InventoryDetail::where('inventory_details.project_id', $this->prData->project->id)
            ->where('purchase_requests.id', $this->prData->id) // Added condition for purchase request id
            ->join('inventories', 'inventory_details.inventory_id', '=', 'inventories.id')
            ->join('items', 'inventories.item_id', '=', 'items.id')
            ->leftJoin('purchase_requests', 'purchase_requests.project_id', '=', 'inventory_details.project_id')
            ->orderBy('items.name', 'asc')
            ->select('inventory_details.*', 'items.name as item_name')
            ->with(['inventory.item', 'inventory_outs'])
            ->distinct()
            ->get();

        $taskData = $this->prData->task;

        if ($taskData) {
            $taskNo = $taskData->task_number;
            $taskName = $taskData->task;
        } else {
            $taskNo = "-";
            $taskName = "-";
        };

        $taskList = PurchaseRequest::where('project_id', $this->prData->project_id)->where('pr_no', '!=', $this->prData->pr_no)->where('is_task', 1)->with('task')->get();

        return view('livewire.detail-monitoring', compact('inventoryItems', 'taskNo', 'taskName', 'taskList'));
    }

    private function applySearchFilter($poDetailList)
    {
        if ($this->searchTerm) {
            return array_filter($poDetailList, function ($poDetail) {
                return stripos($poDetail->item->name, $this->searchTerm) !== false;
            });
        }

        return $poDetailList;
    }

    public function handleUpload($itemParam)
    {
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
                'project_id' => $this->prData->project->id,
                'pr_id' => $this->prId,
                'item_id' => $itemParam,
                'name' => $this->nameModel,
                'description' => $this->descModel,
                'file_upload' => $jsonFileUpload
            ]);

            DB::commit();
            session()->flash('success', 'Data has been uploaded');
            return redirect()->route('new-monitoring.detail', $this->prId);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function handleRelocate(PurchaseOrderDetail $poDetail)
    {
        DB::beginTransaction();

        try {
            $prDetail = PurchaseRequestDetail::find($poDetail->purchase_request_detail_id);
            // Update PR ID in PR Detail
            $prDetail->pr_id = intval($this->relocateTo);
            $prDetail->save();

            // Update PO Data
            $po = $poDetail->po;
            $po->pr_no = $prDetail->purchaseRequest->pr_no;
            $po->save();

            DB::commit();
            session()->flash('success', 'Data has been relocated');
            return redirect()->route('new-monitoring.detail', $this->prId);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
