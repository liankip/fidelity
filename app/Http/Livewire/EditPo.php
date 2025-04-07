<?php

namespace App\Http\Livewire;

use App\Models\POPRPivotModel;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Livewire\Component;

class EditPo extends Component
{
    public $po;
    public $warehousemodel;
    public $warehouse;

    public $pricemodel;
    public $arraypodetail = [];

    public function mount($id)
    {
        $this->po = PurchaseOrder::findOrFail($id);
        $this->warehousemodel = $this->po->warehouse_id;
        $this->warehouse = Warehouse::where('deleted_at', null)->get();
        $this->arraypodetail = $this->po->podetail->toArray();
    }

    public function render()
    {
        foreach ($this->arraypodetail as $key => $value) {
            if ($this->arraypodetail[$key]['price']) {
                $this->arraypodetail[$key]['amount'] = $this->arraypodetail[$key]['price'] * $this->arraypodetail[$key]['qty'];
            }
        }

        return view('livewire.edit-po');
    }

    public function savedata()
    {
        foreach ($this->arraypodetail as $key => $val) {
            PurchaseOrderDetail::where('id', $val['id'])->update([
                'price' => $this->arraypodetail[$key]['price'],
                'amount' => $this->arraypodetail[$key]['amount'],
                'deliver_status' => $this->arraypodetail[$key]['deliver_status'],
            ]);
            PurchaseOrder::where('id', $this->po->id)->update([
                'warehouse_id' => $this->warehousemodel,
                'deliver_status' => $this->arraypodetail[$key]['deliver_status'],
            ]);
        }

        return redirect()
            ->to('/purchase-orders')
            ->with('success', 'Success To Edit PurchaseOrder ' . $this->po->po_no);
    }

    public function delete($data)
    {
        [$id, $pr_id] = explode(',', $data);

        if ($this->po->podetail->count() <= 1) {
            return redirect()
                ->route('purchase-orders.edit', $this->po->id)
                ->with('warning', 'Item can`t be deleted less than 1' . $this->po->po_no);
        }

        PurchaseOrderDetail::where('id', $id)->delete();

        POPRPivotModel::where('po_id', $this->po->id)->where('pr_id', $pr_id)->first()->delete();

        return redirect()->route('purchase-orders.edit', $this->po->id)->with('success', 'Success To Delete Item');
    }

    public function showdata()
    {
        dd($this->arraypodetail);
    }
}
