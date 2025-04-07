<?php

namespace App\Http\Livewire;

use App\Constants\EmailNotificationTypes;
use App\Constants\PurchaseOrderStatus;
use App\Helpers\GeneratePrNo;
use App\Helpers\Whatsapp;
use App\Jobs\SendWhatsapp;
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


class PurchaseRequestTest extends Component
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

    public function ajukanpr($id)
    {
        $prsubmited = PurchaseRequest::with('prdetail')->where("id", $id)->first();

        if ($prsubmited->pr_no) {
            return session()->flash('success', "Purchase Request " . $prsubmited->pr_no . " Telah di ajukan sebelumnya");
        }
        $prgenerate = GeneratePrNo::get();
        PurchaseRequest::where("id", $id)->update([
            "pr_no" => $prgenerate,
            "status" => "New"
        ]);

        $requestedItems = $prsubmited->prdetail;
        $itemIds = $prsubmited->prdetail->pluck('item_id');
        $prices = SupplierItemPrice::whereIn('item_id', $itemIds)->get();

        $suppliers = $prices->groupBy('supplier_id');

        foreach ($suppliers as $key => $items) {
            $supplier = Supplier::where('id', $key)->where('city', 'like', '%' . $prsubmited->city . '%')->first();

            if (is_null($supplier))
                continue;

            $currentDateTime = Carbon::now();
            $expired_at = $currentDateTime->addDay()->setTime(12, 0, 0);

            $rfq = RequestForQuotation::create([
                'id' => (string) \Str::uuid(),
                'period' => $currentDateTime,
                'expired_at' => $expired_at,
                'supplier_id' => $supplier->id,
                'purchase_request_id' => $prsubmited->id,
            ]);

            foreach ($items as $item) {
                $requestedItem = $requestedItems->where('item_id', $item->item_id)->first();
                $rfq->itemDetail()->create([
                    'item_id' => $item->item_id,
                    'price' => null,
                    'unit' => $item->item->unit,
                    'qty' => $requestedItem?->qty,
                ]);
            }

            $to = config('app.wa_default_to');
            $link = route('request-for-quotation', $rfq->id);
            $message = Whatsapp::rfqMessage($supplier, $rfq, $link);

            SendWhatsapp::dispatch($message, $to);
        }

        $types = NotificationEmailType::where('name', EmailNotificationTypes::PR_CREATED)->first();

        if ($types) {
            foreach ($types->emails as $receiver) {
                Mail::to($receiver->email)->send(new PurchaseRequestCreated($prsubmited));
            }
        }

        session()->flash('success', "Purchase Request berhasil di ajukan");
    }

    public function render()
    {
        $user = auth()->user();
        if (!$user->hasPermissionTo('create-pr-no-boq')) {
            return abort(403);
        }

        $purchaserequests = purchaserequest::with("podetail")->orderBy('created_at', 'desc')->get();

        return view('livewire.purchase-request-test', compact('purchaserequests'));
    }
}
