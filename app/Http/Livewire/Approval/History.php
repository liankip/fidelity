<?php

namespace App\Http\Livewire\Approval;

use App\Models\PurchaseOrder;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;


    public function UpdatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if ($this->search) {
            $get = PurchaseOrder::with("project", "warehouse", "approvedby",'supplier','podetail')
                ->where(function ($query) {
                    $query->where("status", "Approved")
                        ->orWhere("status", "Rejected");
                })
                ->where(function ($query) {
                    $query->where("po_no", 'like', "%" . $this->search . "%")
                        ->orWhereHas("project", function ($query) {
                            $query->where('name', 'like', "%" . $this->search . "%");
                        })
                        ->orWhereHas('approvedby', function ($query) {
                            $query->where('name', 'like', "%" . $this->search . "%");
                        })
                        ->orWhereHas('supplier', function ($query) {
                            $query->where('name', 'like', "%" . $this->search . "%");
                        })
                        ->orWhereHas('warehouse', function ($query) {
                            $query->where('name', 'like', "%" . $this->search . "%");
                        });
                })
                ->orderBy("updated_at", "DESC");
        } else {
            $get = PurchaseOrder::with("project", "warehouse")
                ->where("status", "Approved")
                ->orWhere("status", "Rejected")
                ->orderBy("updated_at", "DESC");
        }
        $purchase_requests = $get->paginate(15);
        return view('livewire.approval.history', ["purchase_requests" => $purchase_requests]);
    }
}
