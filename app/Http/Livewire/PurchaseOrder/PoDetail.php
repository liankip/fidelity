<?php

namespace App\Http\Livewire\PurchaseOrder;

use App\Models\User;
use App\Models\Price;
use App\Models\Invoice;
use Livewire\Component;
use App\Models\CompanyDetail;
use App\Models\DeliveryOrder;
use App\Models\PurchaseOrder;
use App\Models\VoucherDetail;
use App\Models\SubmitionHistory;
use Illuminate\Support\Facades\DB;

class PoDetail extends Component
{
    public $newDate, $statuspo;
    public $our_company, $price_profil;
    public $edittaxmode = false, $modeltaxcustom;
    public $idpo;
    public $voucherData;
    public $submittionHistory;
    public $deliverOrder;
    public $invoices;
    public $users;

    public function mount($id)
    {
        $this->idpo = $id;
    }

    public function updatePaid()
    {
        PurchaseOrder::where("id", $this->idpo)->update([
            'status' => 'Paid'
        ]);
    }

    public function activeedittaxmode()
    {
        $this->edittaxmode = true;
    }

    public function savetaxcustom()
    {
        $this->validate([
            'modeltaxcustom' => 'required|numeric'
        ], [
            'modeltaxcustom' => 'Tax'
        ]);
        PurchaseOrder::where("id", $this->idpo)->update([
            'tax_custom' => $this->modeltaxcustom
        ]);
        $this->modeltaxcustom = "";
        $this->edittaxmode = false;
    }

    public function canceltaxcustom()
    {
        $this->modeltaxcustom = "";
        $this->edittaxmode = false;
    }

    public function resettaxcutome()
    {
        $this->modeltaxcustom = "";
        PurchaseOrder::where("id", $this->idpo)->update([
            'tax_custom' => null
        ]);
        $this->edittaxmode = false;
    }

    public function render()
    {
        $this->statuspo = PurchaseOrder::with("podetail.prdetail")->where('id', $this->idpo)->first();
        if ($this->statuspo->approved_at) {
            $this->newDate = date_format(date_create($this->statuspo->approved_at), 'F d, Y');
        } elseif ($this->statuspo->date_approved_2) {
            $this->newDate = date_format(date_create($this->statuspo->date_approved_2), 'F d, Y');
        } elseif ($this->statuspo->date_approved) {
            $this->newDate = date_format(date_create($this->statuspo->date_approved), 'F d, Y');
        } else {
            $this->newDate = date_format(date_create($this->statuspo->cretated_at), 'F d, Y');
        }

        $notes = $this->statuspo->notes ? json_decode($this->statuspo->notes) : [];
        // Get all user IDs from the notes
        $userIds = collect($notes)->pluck('user_id')->unique();

        // Fetch user names for these IDs
        $this->users = User::whereIn('id', $userIds)->pluck('name', 'id'); // Keyed by user_id
        
        $this->our_company = CompanyDetail::first();
        $this->price_profil = Price::where('id', $this->statuspo->supplier_id)->first();

        $this->submittionHistory = SubmitionHistory::select(DB::raw('DATE(updated_at) as date'))
            ->where('po_id', $this->idpo)
            ->groupBy(DB::raw('DATE(updated_at)'))
            ->orderBy(DB::raw('DATE(updated_at)'), 'ASC')
            ->get();

        $this->deliverOrder = deliveryorder::select(DB::raw('DATE(updated_at) as date'))
            ->where('referensi', $this->statuspo->po_no)
            ->groupBy(DB::raw('DATE(updated_at)'))
            ->orderBy(DB::raw('DATE(updated_at)'), 'ASC')
            ->get();

        $this->invoices = Invoice::where('po_id', $this->idpo)
            ->select(DB::raw('DATE(updated_at) as date'))
            ->groupBy(DB::raw('DATE(updated_at)'))
            ->orderBy(DB::raw('DATE(updated_at)'), 'ASC')
            ->get();

        $voucherDetail = VoucherDetail::where("purchase_order_id", $this->statuspo->id)->get();
        foreach ($voucherDetail as $detail) {
            if ($detail->hasPaymentRelation()) {
                $this->voucherData[] = $detail;
            }
        }

        return view('livewire.purchase-order.po-detail');
    }
}
