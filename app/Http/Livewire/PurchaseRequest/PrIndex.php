<?php

namespace App\Http\Livewire\PurchaseRequest;

use App\Constants\EmailNotificationTypes;
use App\Constants\PurchaseOrderStatus;
use App\Helpers\GeneratePrNo;
use App\Helpers\Whatsapp;
use App\Jobs\SendWhatsapp;
use App\Mail\CompletePurchaseBoqItemsMail;
use App\Mail\PurchaseRequestCreated;
use App\Models\NotificationEmail;
use App\Models\NotificationEmailType;
use App\Models\Price;
use App\Models\PurchaseRequest;
use App\Models\RequestForQuotation;
use App\Models\Supplier;
use App\Models\SupplierItemPrice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

use function PHPUnit\Framework\isNull;

class PrIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $searchcompact, $purchase_requests;
    public $filter = 0;
    public $willdeletepr, $prnowilldelete;
    public $conformdeletemodal = false;

    public function updatedSearchcompact()
    {
        $this->resetPage();
    }

    public function filterHandler($category)
    {
        $this->filter = $category;
        $this->resetPage();
    }

    public function showconfirmcancel($id, $pr_no)
    {
        $this->willdeletepr = $id;
        $this->prnowilldelete = $pr_no;
        $this->conformdeletemodal = true;
    }

    public function closecc()
    {
        $this->willdeletepr = 0;
        $this->prnowilldelete = 0;
        $this->conformdeletemodal = false;
    }

    public function render()
    {
        if ($this->searchcompact) {
            $purchaserequests = PurchaseRequest::with("podetail", "project", "po", "purchaseRequestDetails")
                ->where(function ($query) {
                    $searchcompact = $this->searchcompact;
                    $query->where("pr_no", "like", "%" . $this->searchcompact . "%")
                        ->orWhere("pr_type", "like", "%" . $this->searchcompact . "%")
                        ->orWhere("status", "like", "%" . $this->searchcompact . "%")
                        ->orWhere("remark", "like", "%" . $this->searchcompact . "%")
                        ->orWhere("requester", "like", "%" . $this->searchcompact . "%")
                        ->orWhere("partof", "like", "%" . $this->searchcompact . "%")
                        ->orWhereHas("project", function ($query) use ($searchcompact) {
                            $query->where("name", "like", "%" . $searchcompact . "%");
                        });
                });
            // ->orWhere("pr_type", "like", "%" . $this->searchcompact . "%")
            // ->orWhere("status", "like", "%" . $this->searchcompact . "%")
            // ->orWhere("remark", "like", "%" . $this->searchcompact . "%");
        } else {
            $purchaserequests = purchaserequest::with("podetail");
        }

        if ($this->filter == 1) {
            $purchaserequests->where("status", "New");
        } else if ($this->filter == 2) {
            $purchaserequests->where("status", "Draft");
        } elseif ($this->filter == 3) {
            $purchaserequests->where("status", "Partially");
        } elseif ($this->filter == 4) {
            $purchaserequests->where("status", "Processed");
        } elseif ($this->filter == 5) {
            $purchaserequests->whereHas("po", function ($query) {
                $query->where("status", PurchaseOrderStatus::APPROVED)
                    ->orWhere("status", PurchaseOrderStatus::PAID)
                    ->orWhere("status", PurchaseOrderStatus::PARTIALLY_PAID)
                    ->orWhere("status", PurchaseOrderStatus::NEED_TO_PAY);
            });
        } elseif ($this->filter == 6) {
            $purchaserequests->where("status", "Cancel");
        }

        $purchaserequests = $purchaserequests->orderBy('created_at', 'desc')->paginate(15);
        return view('livewire.purchase-request.pr-index', ["purchaserequests" => $purchaserequests]);
    }
}
