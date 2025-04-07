<?php

namespace App\Http\Livewire\PurchaseOrder;

use App\Models\{PurchaseRequest, PurchaseRequestDetail};
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ChooceItemPr extends Component
{
    public $pr, $prdetail, $quantity, $checkall;

    protected $rules = [
        'prdetail.*.checked' => '',
        'prdetail.*.qty' => '',
    ];

    public function mount($id)
    {
        if (Session::has('purchaserequestdetail') && Session::get('id_po') == $id) {
            return Redirect(url('purchase_order', ['id' => $id]));
        } else {
            Session::forget('purchaserequestdetail');
        }

        $this->pr = PurchaseRequest::where('id', $id)->first();

        // check item only capex expense
        if ($this->pr->project_id !== null && $this->pr->partof == 'capex') {
            $this->prdetail = PurchaseRequestDetail::with('item', 'podetail')
                ->whereHas('purchaseRequest', function ($query) use ($id) {
                    $query->where('id', $id)->where('project_id', $this->pr->project_id)->where('rejected_by', null);
                })
                ->get();
        // check item only project or task monitoring
        } elseif ($this->pr->project_id !== null) {
            $this->prdetail = PurchaseRequestDetail::with('item', 'podetail')
                ->whereHas('purchaseRequest', function ($query) {
                    $query->where('project_id', $this->pr->project_id)->where('rejected_by', null)->where('is_task', 1)->where('pr_no', '!=', null);
                })
                ->get();
        // check item only raw material
        } else {
            $this->prdetail = PurchaseRequestDetail::with('item', 'podetail')
                ->whereHas('purchaseRequest', function ($query) use ($id) {
                    $query->where('id', $id)->where('project_id', null)->where('rejected_by', null);
                })
                ->get();
        }

        foreach ($this->prdetail as $key => $value) {
            $countreduce_qty = 0;
            if (count($value->podetail)) {
                foreach ($value->podetail as $qtyy) {
                    $countreduce_qty += $qtyy->qty;
                }
            }

            if ($countreduce_qty) {
                $this->prdetail[$key]['reduce_qty'] = $this->prdetail[$key]['qty'] - $countreduce_qty;
            } else {
                $this->prdetail[$key]['reduce_qty'] = null;
            }

            if ($this->prdetail[$key]['reduce_qty'] != null || $this->prdetail[$key]['reduce_qty'] != 0) {
                $this->quantity[$key] = $this->prdetail[$key]['reduce_qty'];
            } else {
                $this->quantity[$key] = $this->prdetail[$key]['qty'];
            }

            if ($this->prdetail[$key]['reduce_qty'] < 0) {
                unset($this->prdetail[$key]);
                continue;
            }

            if ($countreduce_qty == $this->prdetail[$key]['qty']) {
                unset($this->prdetail[$key]);
            }

            if (count($value->pivotBulkPR) > 0 && count($value->purchaseRequest->po) > 0) {
                unset($this->prdetail[$key]);
            }
        }
    }

    public function render()
    {
        return view('livewire.purchase-order.chooce-item-pr');
    }

    public function allcheck()
    {
        if ($this->checkall) {
            foreach ($this->prdetail as $key => $value) {
                // dd("check list all");
                $this->prdetail[$key]['checked'] = 1;
            }
        } else {
            foreach ($this->prdetail as $key => $value) {
                $this->prdetail[$key]['checked'] = 0;
            }
        }
    }
    public function continue()
    {
        $itemselected = [];
        foreach ($this->prdetail as $value) {
            if ($value->checked) {
                array_push($itemselected, $value->id);
            }
        }
        if (count($itemselected) <= 0) {
            return session()->flash('danger', 'Anda belum memilih item satupun');
        } else {
            Session::put('data', $itemselected);
            Session::put('id_po', $this->pr->id);
            Session::forget('purchaserequestdetail');
            return Redirect(url('purchase_order', ['id' => $this->pr->id]));
        }
    }
}
