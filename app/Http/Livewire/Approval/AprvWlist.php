<?php

namespace App\Http\Livewire\Approval;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Helpers\Whatsapp;
use App\Models\EmailSend;
use App\Models\Inventory;
use App\Helpers\GetEmails;
use App\Jobs\SendWhatsapp;
use App\Models\PurchaseOrder;
use App\Helpers\CheckPartially;
use App\Models\HistoryPurchase;
use App\Models\NotificationTop;
use App\Models\PurchaseRequest;
use App\Traits\HistoryPurchases;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\NotificationEmailType;
use App\Constants\PurchaseOrderStatus;
use App\Constants\EmailNotificationTypes;
use App\Models\PurchaseOrderDetail;
use App\Models\RequestForQuotationDetail;
use App\Notifications\PurchaseOrderRevert;
use App\Models\PurchaseOrderPriceComparison;
use App\Models\Setting;
use Illuminate\Support\Facades\Notification;
use App\Traits\PurchaseOrderApprove as TraitsPurchaseOrderApprove;
use Illuminate\Support\Facades\Log;

class AprvWlist extends Component
{
    use HistoryPurchases, TraitsPurchaseOrderApprove;

    public $po;
    public $testing;
    public $prarray = [];
    public $checkall;

    //sho consern reject
    public $consernshow = false;
    public $consernshowmultiple = false;

    public $revertconsernmodel;

    //id po to rect
    public $idrevert;
    public $supplierItemPrice;

    public $setting;

    public function mount()
    {
        $purchaseOrders = PurchaseOrder::with(["warehouse", "project", "podetail.prdetail", "supplier"])
        ->where(function ($query) {
            $query->where("status", "Wait For Approval")
                ->whereHas("pr")
                ->orWhere(function ($subQuery) {
                    $subQuery->whereHas("podetail", function ($podetailQuery) {
                        $podetailQuery
                            ->whereHas("po", function ($poQuery) {
                                $poQuery->where("status", "Wait For Approval");
                            })
                            ->where(function ($podetailSubQuery) {
                                $podetailSubQuery
                                    ->where("is_bulk", 1)
                                    ->orWhere("is_stock", 1)
                                    ->orWhere('is_raw_materials', 1);
                            });
                    });
                });
        })
        ->orderBy("updated_at", "DESC")
        ->get();



        // Filter purchase orders that project have approver
        $this->po = $purchaseOrders->filter(function ($po) {
            return ($po->project && $po->project->canApprovePO()) || !$po->project;
        });

        $supplierItemPrice = collect([]);

        foreach ($this->po as $po) {
            $isBulkPo = $po->podetail->every(fn ($podetail) => $podetail->is_bulk == 1);
            $isStock = $po->podetail->every(fn ($podetail) => $podetail->is_stock == 1);
            $isRawMaterials = $po->podetail->every(fn ($podetail) => $podetail->is_raw_materials == 1);
            foreach ($po->podetail as $podetail) {
                // $item = RequestForQuotationDetail::with('requestForQuotation.supplier')->where('item_id', $podetail->item_id)->whereNotNull('price')->orderBy('price', 'asc')->first();
                // if ($item) {
                //     $vendorItemPrice->push([
                //         'item_id' => $podetail->item_id,
                //         'vendor' => $item->requestForQuotation->supplier->name,
                //         'price' => $item->price
                //     ]);
                // }
                if($isBulkPo || $isStock || $isRawMaterials){
                    $priceComparison = PurchaseOrderDetail::where('id', $podetail->id)->get();
                } else {
                    $priceComparison = PurchaseOrderPriceComparison::with('supplierItemPrice.supplier')->where('purchase_request_id', $po->pr->id)->where('item_id', $podetail->item_id)->get();
                }


                foreach ($priceComparison as $comparison) {
                    if ($comparison->supplierItemPrice?->supplier->id == $po->supplier_id) {
                        continue;
                    }

                    $supplierItemPrice->push([
                        'item_id' => $podetail->item_id,
                        'vendor' =>(!$isBulkPo && !$isStock && !$isRawMaterials) ? $comparison->supplierItemPrice->supplier->name : $comparison->po->supplier->name,
                        'price' => (!$isBulkPo && !$isStock && !$isRawMaterials) ? $comparison->supplierItemPrice->price : $comparison->price
                    ]);
                }
            }
        }
        $this->supplierItemPrice = $supplierItemPrice;

        $this->prarray = $this->po->toArray();

        foreach ($this->prarray as $key => $value) {
            $this->prarray[$key]["checked"] = 0;
        }

        $this->setting = Setting::first();
    }

    public function render()
    {
        return view('livewire.approval.aprv-wlist', [
            'statuses' => $this->po->mapWithKeys(fn($po) => [$po->id => $this->checkPoDetailStatus($po)])
        ]);
    }

    public function checkPoDetailStatus($purchaseorder)
    {
        if (!$purchaseorder) {
            return [];
        }

        $podetails = $purchaseorder->podetail;

        return array_filter([
            'isStock'       => $podetails->every(fn($pod) => $pod->is_stock == 1),
            'isBulkPo'      => $podetails->every(fn($pod) => $pod->is_bulk == 1),
            'isRawMaterials' => $podetails->every(fn($pod) => $pod->is_raw_materials == 1),
        ]);
    }


    public function showdata()
    {
        dd($this->prarray);
    }

    public function allcheck()
    {
        if ($this->checkall) {
            foreach ($this->prarray as $key => $value) {

                $this->prarray[$key]["checked"] = 1;
            }
        } else {
            foreach ($this->prarray as $key => $value) {
                $this->prarray[$key]["checked"] = 0;
            }
        }
    }

    public function approve()
    {
        $newprarray = [];
        foreach ($this->prarray as $value) {
            if ($value["checked"]) {
                array_push($newprarray, $value["id"]);
            }
        }
        if (count($newprarray)) {
            foreach ($newprarray as $valarray) {
                $potempgen = PurchaseOrder::with('project')->where("id", $valarray)->first();

                $apakah_partial = CheckPartially::get($potempgen);

                $resultpo = PurchaseOrder::with("warehouse", "supplier")->whereIn("id", $newprarray)->get();
                $currentuser = Auth::user();

                foreach ($resultpo as $po) {

                    if ($po->status == "Approved") {
                        continue;
                    }

                    if ((bool)$this->setting->multiple_po_approval) {
                        if ($apakah_partial) {
                            $this->updatePopartial($po, $currentuser);
                        } else {
                            $this->updatepo($po, $currentuser);
                        }
                    } else {
                        $this->updatepo($po, $currentuser);
                    }
                }
            }

            return redirect(request()->header("Referer"))->with('success', 'Purchase Orders Aproved');
            // session()->flash('success', 'Berhasil Mengapprove PO.');
        } else {
            return session()->flash('danger', 'Anda belum menchecklist satupun PO.');
        }
    }

    //showrejectreason
    public function showconsern($id)
    {
        $this->idrevert = $id;
        $this->consernshow = true;
    }

    //showrejectreason multiple
    public function showconsernmultiple()
    {
        $newprarray = [];
        foreach ($this->prarray as $key => $value) {
            if ($value["checked"]) {
                array_push($newprarray, $value["id"]);
            }
        }
        if (!count($newprarray)) {
            session()->flash('danger', 'Anda belum menchecklist satupun PO.');
            return;
        }
        $this->consernshowmultiple = true;
    }

    //close all consern modal
    public function closeconsern()
    {
        $this->consernshow = false;
        $this->consernshowmultiple = false;
    }


    public function reject()
    {
        $this->validate(['revertconsernmodel' => 'required'], ['revertconsernmodel' => 'Reject Reason']);
        $newprarray = [];
        foreach ($this->prarray as $value) {
            if ($value["checked"]) {
                array_push($newprarray, $value["id"]);
            }
        }

        if (count($newprarray)) {
            $resultpo = PurchaseOrder::whereIn("id", $newprarray)->get();
            $reserved = User::whereIn("type", [2, 3, 5])->get();
            $currentuser = Auth::user();
            foreach ($resultpo as $po) {

                PurchaseOrder::where("id", $po->id)->update([
                    'status' => 'Rejected',
                    'remark_reject' => $this->revertconsernmodel
                ]);

                $history = new HistoryPurchase;
                $history->action_start = $po->status;
                $history->action_end = 'Rejected';
                $history->referensi = $po->po_no;
                $history->action_by = $currentuser->id;
                $history->created_by = $currentuser->id;
                $history->action_date = Carbon::now();
                $history->created_at = Carbon::now();
                $history->save();

                foreach ($reserved as $pur) {
                    $podata = [
                        'po_no' => $po->po_no,
                        'po_detail' => $po->id,
                    ];
                    Notification::send($pur, new PurchaseOrderRevert($podata));

                    $freshpo = PurchaseOrder::where("id", $po->id)->first();

                    $mainmessage = "";

                    foreach ($freshpo->podetail as $no => $barang) {
                        $mainmessage = $mainmessage . ($no + 1) . ". " . $barang->prdetail->item_name . "\n Qty: " . $barang->qty . "" . $barang->unit . "\n";
                    }

                    $messageh3 = url("po_details", [$freshpo->id]);

                    $msg = "*" . config('app.company', 'SNE') . " ERP* \n\nPembelian tidak disetujui\nNo. PO: *" . $freshpo->po_no . "* \nProject: " . $freshpo->pr->project->name . "\nRequested by: " . $freshpo->pr->requester . "\nBagian pekerjaan: " . $freshpo->pr->partof . " \n\n" . $mainmessage . "\n\n" . $messageh3 . "\nApproved by: *" . auth()->user()->name . "* \n\n_Ini adalah pesan otomatis. Simpan nomor ini sebagai contact agar URL pada pesan dapat di-klik._";

                    if ($pur->phone_number) {
                        //dimatikan sementara
                        // WaMessage::create([
                        //     "number"    => $pur->phone_number,
                        //     "message"   => $msg
                        // ]);
                    }
                    // }
                    // $penerimaemail = GetEmails::get();
                    // foreach ($penerimaemail as $email) {
                    //     EmailSend::create([
                    //         "email"         => $email,
                    //         "po_id"         => $freshpo->id,
                    //         "created_by"    => auth()->user()->id,
                    //         "type"          => "PaymentUploaded"
                    //     ]);
                    // }
                }


                $checkprhavepo = PurchaseOrder::where("pr_no", $po->pr_no)
                    ->where("status", "!=", "Cancel")
                    ->where("status", "!=", "Rejected")->get();
                if (count($checkprhavepo)) {
                    PurchaseRequest::where("pr_no", $po->pr_no)->update([
                        'status' => 'Partially',
                        // 'remark' => $newremark
                    ]);
                } else {
                    PurchaseRequest::where("pr_no", $po->pr_no)->update([
                        'status' => 'New',
                        // 'remark' => $newremark
                    ]);
                }
            }


            return redirect(request()->header("Referer"))->with('success', 'Anda Telah Me-Reject PO');
        } else {
            session()->flash('danger', 'Anda belum menchecklist satupun PO.');
        }
    }

    public function revert()
    {
        $this->validate(['revertconsernmodel' => 'required'], ['revertconsernmodel' => 'Revert Reason']);
        $newprarray = [];
        foreach ($this->prarray as $value) {
            if ($value["checked"]) {
                array_push($newprarray, $value["id"]);
            }
        }

        if (count($newprarray)) {
            $resultpo = PurchaseOrder::whereIn("id", $newprarray)->get();
            $reserved = User::whereIn("type", [2, 3, 5])->get();
            foreach ($resultpo as $po) {
                PurchaseOrder::where("id", $po->id)->update([
                    'status' => PurchaseOrderStatus::REVERTED,
                    'remark' => $this->revertconsernmodel
                ]);

                $this->pushHistory($po, PurchaseOrderStatus::REVERTED);

                foreach ($reserved as $pur) {
                    $podata = [
                        'po_no' => $po->po_no,
                        'po_detail' => $po->id,
                    ];
                    Notification::send($pur, new PurchaseOrderRevert($podata));
                }
            }


            return redirect(request()->header("Referer"))->with('success', 'Anda Telah Megembalikan PO');
        } else {
            session()->flash('danger', 'Anda belum menchecklist satupun PO.');
        }
    }

    private function updatepo($old_status, $currentuser)
    {
        try {
            //code...
        
        if ($old_status->approved_by) {
            return;
        }
        $history = new HistoryPurchase;
        $history->action_start = $old_status->status;
        $history->action_end = 'Approved';
        $history->referensi = $old_status->po_no;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();

        // if ($old_status->pr->pr_type == "Barang") {
        //     $pogenerated = GeneratePoNo::get($old_status->project->project_code);
        // } else {
        //     $pogenerated = GenerateSpkNo::get($old_status->project->project_code);
        // }

        PurchaseOrder::where('id', $old_status->id)->update([
            // "po_no" => $pogenerated,
            "status" => "Approved",
            "date_approved" => date('Y-m-d H:i:s'),
            "approved_at" => date('Y-m-d H:i:s'),
            "approved_by" => auth()->user()->id
        ]);
        $freshpo = PurchaseOrder::where("id", $old_status->id)->first();

        // $today = Carbon::now()->format('Y-m-d');
        // $est_cash = date('Y-m-d', strtotime('+3 days', strtotime($today)));

        // $save = new NotificationTop;
        // $save->purchase_order_id = $freshpo->id;
        // $save->top_type = $freshpo->term_of_payment;
        // $save->approve_date = $today;
        // if ($freshpo->term_of_payment == 'Cash') {
        //     $save->est_pay_date = $est_cash;
        //     $save->est_paid_off_date = $est_cash;
        // }
        // $save->created_by = auth()->user()->id;
        // $save->created_at = $today;
        // $save->save();

        $this->sendApprovedNotification($freshpo);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    private function updatePopartial($old_status, $currentuser)
    {
        if ($old_status->date_approved == Null && $old_status->approved_by == Null) {
            $history = new HistoryPurchase;
            $history->action_start = $old_status->status;
            $history->action_end = 'Half Approved';
            $history->referensi = $old_status->po_no;
            $history->action_by = $currentuser->id;
            $history->created_by = $currentuser->id;
            $history->action_date = Carbon::now();
            $history->created_at = Carbon::now();
            $history->save();
            PurchaseOrder::where('id', $old_status->id)->update([
                "date_approved" => date('Y-m-d H:i:s'),
                "approved_by" => auth()->user()->id
            ]);
        } else {
            if ($old_status->approved_by == auth()->user()->id) {
                return;
            }
            $history = new HistoryPurchase;
            $history->action_start = $old_status->status;
            $history->action_end = 'Full Approved';
            $history->referensi = $old_status->po_no;
            $history->action_by = $currentuser->id;
            $history->created_by = $currentuser->id;
            $history->action_date = Carbon::now();
            $history->created_at = Carbon::now();
            $history->save();

            // if ($old_status->pr->pr_type == "Barang") {
            //     $pogenerated = GeneratePoNo::get($old_status->project->project_code);
            // } else {
            //     $pogenerated = GenerateSpkNo::get($old_status->project->project_code);
            // }

            PurchaseOrder::where('id', $old_status->id)->update([
                // "po_no" => $pogenerated,
                'status' => 'Approved',
                "date_approved_2" => date('Y-m-d H:i:s'),
                "approved_at" => date('Y-m-d H:i:s'),
                "approved_by_2" => auth()->user()->id
            ]);
            $freshpo = PurchaseOrder::where("id", $old_status->id)->first();

            $today = Carbon::now()->format('Y-m-d');
            $est_cash = date('Y-m-d', strtotime('+3 days', strtotime($today)));

            $save = new NotificationTop;
            $save->purchase_order_id = $freshpo->id;
            $save->top_type = $freshpo->term_of_payment;
            $save->approve_date = $today;
            if ($freshpo->term_of_payment == 'Cash') {
                $save->est_pay_date = $est_cash;
                $save->est_paid_off_date = $est_cash;
            }
            $save->created_by = auth()->user()->id;
            $save->created_at = $today;
            $save->save();

            $this->sendApprovedNotification($freshpo);
        }
    }
}
