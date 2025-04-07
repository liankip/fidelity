<?php

namespace App\Http\Livewire;

use App\Models\BOQ;
use App\Models\BulkPOPivot;
use App\Models\Item;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\Setting;
use Carbon\Carbon;
use DB;
use Livewire\Component;
use Session;

class ChooseItemCapexExpense extends Component
{
    public $qty, $notes;
    public $itemsarray = [];
    public $prid;
    public $selectedUnit;
    public $setting;
    public $project_id;

    public function mount($id)
    {
        $this->prid = $id;
        $this->setting = Setting::first();

        $this->prequest = PurchaseRequest::where('id', $id)->first();
        $this->project = $this->prequest->project;

        $this->project_id = $this->project->id;
    }

    public function getData()
    {
        $itemsQuery = null;

        if ($this->setting->boq || (!$this->setting->boq && $this->project->boq)) {
            $itemsQuery = $this->project->purchaseRequestItems($this->prequest->id);
        } else {
            $itemsQuery = Item::all();
        }

        $existingItemIds = PurchaseRequestDetail::whereHas('purchaseRequest', function ($query) {
            $query->where('project_id', $this->project->id);
        })
            ->selectRaw('item_id, SUM(qty) as qty')
            ->groupBy('item_id')
            ->get();

        $allMatch = true;

        foreach ($itemsQuery as $index => $item) {
            $existingItem = $existingItemIds->firstWhere('item_id', $item['item_id']);

            if (!$existingItem || $existingItem->qty != $item['qty']) {
                $allMatch = false;
                break;
            }
        }

        if ($allMatch) {
            $itemsQuery = collect();
        }

        $purchaseRequestDetail = PurchaseRequestDetail::where('pr_id', $this->prid)->get();

        $this->qty = [];
        $this->notes = [];

        foreach ($itemsQuery as $index => $item) {
            $existingItem = $existingItemIds->where('item_id', $item->item_id)->first();

            $quantity = $item->qty - ($existingItem->qty ?? 0);

            if ($quantity > 0) {
                $this->qty[$index] = $quantity;

                $purchaseRequestDetailForItem = $purchaseRequestDetail->firstWhere('item_id', $item->item_id);
                $this->notes[$index] = $purchaseRequestDetailForItem->notes ?? '';
            }
        }

        $itemsQuery = $itemsQuery->filter(function ($item, $key) {
            return isset($this->qty[$key]);
        });

        return $itemsQuery;
    }

    public function addItem($items)
    {
        DB::beginTransaction();
        try {
            $existingItemIds = PurchaseRequestDetail::whereHas('purchaseRequest', function ($query) {
                $query->where('project_id', $this->project_id);
            })->get();

            $projectId = $this->project->id;

            foreach ($items as $index => $item) {
                $existingPRDetails = $existingItemIds->where('item_id', $item['item']['id']);

                $totalExistingQty = $existingPRDetails->sum('qty');
                $remainingQty = $item['qty'] - $totalExistingQty;

                if ($this->qty[$index] > $remainingQty) {
                    throw new \Exception("Quantity untuk item {$item['item']['name']} tidak boleh melebihi {$remainingQty}.");
                }

                $existingDetail = PurchaseRequestDetail::where('pr_id', $this->prequest->id)->where('item_id', $item['item']['id'])->first();

                if ($existingDetail) {
                    $existingDetail->update([
                        'qty' => (float) $this->qty[$index],
                        'notes' => $this->notes[$index],
                        'updated_by' => auth()->user()->id,
                    ]);
                } else {
                    PurchaseRequestDetail::create([
                        'pr_id' => $this->prequest->id,
                        'item_id' => $item['item']['id'],
                        'item_name' => $item['item']['name'],
                        'type' => $item['item']['type'],
                        'unit' => $item['unit']['name'],
                        'qty' => $this->qty[$index],
                        'created_by' => auth()->user()->id,
                        'updated_by' => auth()->user()->id,
                        'status' => 'baru',
                        'notes' => $this->notes[$index] ?? '',
                    ]);
                }

                if ($totalExistingQty === 0) {
                    BOQ::updateOrCreate(
                        [
                            'project_id' => $projectId,
                            'item_id' => $item['item']['id'],
                        ],
                        [
                            'qty' => (float) $this->qty[$index],
                            'updated_by' => auth()->user()->id,
                        ],
                    );
                } else {
                    $existingBOQ = BOQ::where('project_id', $projectId)->where('item_id', $item['item']['id'])->first();

                    if ($this->qty[$index] < $remainingQty) {
                        $existingBOQ->update([
                            'qty' => $totalExistingQty + $this->qty[$index],
                            'updated_by' => auth()->user()->id,
                        ]);
                    } else {
                        $existingBOQ->update([
                            'qty' => $item['qty'],
                            'updated_by' => auth()->user()->id,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('capex-expense.boq', ['project_id' => $this->project->id])
                ->with('success', 'Item successfully created for purchase request.');
        } catch (\Exception $e) {
            Session::flash('danger', $e->getMessage());
            DB::rollBack();
        }
    }

    public function render()
    {
        $items = $this->getData();

        return view('livewire.choose-item-capex-expense', compact('items'));
    }
}
