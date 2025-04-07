<?php

namespace App\Http\Controllers;

use App\Constants\EmailNotificationTypes;
use App\Constants\PurchaseOrderStatus;
use App\Helpers\CheckPartially;
use App\Helpers\GeneratePoNo;
use App\Helpers\GenerateSpkNo;
use App\Helpers\GetAmount;
use App\Models\HistoryPurchase;
use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Models\InventoryHistory;
use App\Models\MemoList;
use App\Models\NotificationEmailType;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\Setting;
use App\Models\Task;
use App\Models\User;
use App\Models\Voucher;
use App\Notifications\PoCancel;
use App\Notifications\PoWaitingList;
use App\Notifications\PurchaseOrderRevert;
use App\Notifications\PurchaseOrderReview;
use App\Notifications\VoucherRevert;
use App\Roles\Role;
use App\Traits\HistoryPurchases;
use App\Traits\PurchaseOrderApprove as TraitsPurchaseOrderApprove;
use App\Traits\VoucherApprove as VoucherApprove;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\HtmlString;
use App\Mail\CompletePurchaseBoqItemsMail;
use App\Models\SupplierItemPrice;
use App\Models\Supplier;
use App\Models\RequestForQuotation;
use App\Helpers\Whatsapp;
use App\Jobs\SendWhatsapp;

class ApproveController extends Controller
{
    use HistoryPurchases, TraitsPurchaseOrderApprove, VoucherApprove;

    public function review(Request $request, $id)
    {
        $currentuser = Auth::user();

        $old_status = PurchaseOrder::where('id', $id)->get()->first();
        $history = new HistoryPurchase();
        $history->action_start = $old_status->status;
        $history->action_end = 'Review';
        $history->referensi = $old_status->po_no;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();

        $request->validate([
            'remark_review' => 'required',
        ]);

        PurchaseOrder::where('id', $request->id)->update([
            'status' => 'Review',
            'remark_review' => $request->remark_review,
        ]);

        $po = PurchaseOrder::where('id', $request->id)->first();
        $purches = User::whereIn('type', [2, 3, 4, 5, 7])->get();

        foreach ($purches as $key => $pur) {
            $podata = [
                'po_no' => $po->po_no,
                'reason' => $request->remark_review,
                'created_by' => Auth::user()->name,
            ];
            Notification::send($pur, new PurchaseOrderReview($podata));
        }

        $pr_no = PurchaseOrder::where('id', $request->id)
            ->get()
            ->first();
        PurchaseRequest::where('pr_no', $pr_no->pr_no)->update([
            'status' => 'Review',
        ]);
        // return redirect()->back();
        return redirect()
            ->route('aprv_waitinglists.index')
            ->with('success', 'Anda Telah Membuat Status Review ' . $po->po_no . '');
    }

    public function approve(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            //code...
            $types = NotificationEmailType::getEmails(EmailNotificationTypes::PO_APPROVED);
            $currentuser = Auth::user();
            $old_status = PurchaseOrder::where('id', $id)->first();
            // dd($old_status->pr);
            if ($old_status->status == 'Approved') {
                if ($old_status->approvedby) {
                    return redirect('/aprv_waitinglists')->with('danger', 'purchase Order Already Appoved by ' . $old_status->approvedby->name);
                } else {
                    return redirect('/aprv_waitinglists')->with('danger', 'purchase Order Already Appoved by other Manager');
                }
            }

            $apakah_partial = CheckPartially::get($old_status);
            $setting = Setting::first();
            if ((bool) $setting->multiple_po_approval) {
                // if ($apakah_partial) {
                $this->updatePopartial($old_status, $id, $currentuser);
                // } else {

                //     $this->updatepo($old_status, $id, $currentuser);
                // }

                $cek_po = PurchaseOrder::where('id', $request->id)->first();
                if ($cek_po->approved_by_2 != null) {
                    $this->sendApprovedNotification($cek_po);
                }
            } else {
                $this->updatepo($old_status, $id, $currentuser);

                $cek_po = PurchaseOrder::where('id', $request->id)->first();
                $this->sendApprovedNotification($cek_po);
            }

            $po = PurchaseOrder::where('id', $request->id)->first();
                if($request->notes !== null && $request->notes !== '') {
                    $existingNotes = $po->notes ? json_decode($po->notes, true) : [];

                    $newNote = [
                        'user_id' => auth()->id(), 
                        'notes' => $request->notes,
                    ];

                    $existingNotes[] = $newNote;

                    $po->notes = json_encode($existingNotes);
                    $po->save();
                }

            $poPivot = $po->pivotPR;

            $bulkPrItems = null;

            if(count($poPivot) > 1) {
                $bulkPrItems = [];
                foreach ($poPivot as $pr) {
                    if($pr->prdetail->where('is_bulk', 1)->count() > 0) {
                        $bulkPrItems[] = $pr->prdetail->where('is_bulk', 1);
                    }
                }
            } else {
                $bulkPrItems = $po->pr?->prdetail->where('is_bulk', 1);
            }

            $isMultipleApproval = Setting::first()->multiple_po_approval;
            $isApproved = null;

            if ($isMultipleApproval) {
                $isApproved = $po->approved_by_2;
            } else {
                $isApproved = $po->approved_by;
            }

            if ($bulkPrItems !== null && count($bulkPrItems) > 0 && $isApproved != null) {
                $itemsId = $bulkPrItems->pluck('item_id')->unique();
                $inventoryData = Inventory::whereIn('item_id', $itemsId)->get();

                $inventoryDetails = InventoryDetail::whereIn('inventory_id', $inventoryData->pluck('id'))->where('project_id', $old_status->project_id)->get()->map(function ($item)  {
                    $item->item_id = $item->inventory->item_id;
                    return $item;
                });

                $historyData = InventoryHistory::all();

                foreach ($bulkPrItems as $item) {
                    $inventoryStock = $inventoryDetails->where('item_id', $item->item_id)->first();

                    $existInHistory = $historyData
                        ->where('inventory_detail_id', $inventoryStock->id)
                        ->where('type', 'OUT')
                        ->first();

                    if ($existInHistory) {
                        $existingPrId = PurchaseRequestDetail::find($existInHistory->prdetail_id)->purchaseRequest->id;

                        $isSamePr = $existingPrId === $item->purchaseRequest->id;

                        if ($isSamePr) {
                            continue;
                        }
                    }

                    $prItemQty = $item->qty;

                    $prevQty = $inventoryStock->stock;
                    $remainingStock = $prevQty - $prItemQty;
                    // $inventoryStock->stock = $remainingStock;
                    $qtyChange = $prevQty - $remainingStock;

                    $inventoryHistory = [
                        'inventory_detail_id' => $inventoryStock->id,
                        'type' => 'OUT',
                        'stock_before' => $prevQty,
                        'stock_after' => $remainingStock,
                        'stock_change' => $qtyChange,
                        'user_id' => Auth::user()->id,
                        'prdetail_id' => $item->id,
                    ];

                    
                    $inventoryItem = $inventoryData->where('item_id', $item->item_id)->first();
                    $inventoryItem->stock = $inventoryItem->stock - $prItemQty;

                    $updateInventoryDetail = InventoryDetail::where('inventory_id', $inventoryItem->id)->where('project_id', $old_status->project_id)->first();

                    $updateInventoryDetail->stock = $remainingStock;

                    $updateInventoryDetail->save();
                    $inventoryItem->save();


                    InventoryHistory::create($inventoryHistory);
                }
            }

            DB::commit();
            return redirect()
                ->to('aprv_waitinglists')
                ->with('success', 'Anda Telah Me-Approve ' . $po->po_no . '');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function approve_voucher(Request $request, $id)
    {
        $types = NotificationEmailType::getEmails(EmailNotificationTypes::PO_APPROVED);
        $currentuser = Auth::user();
        $old_status = Voucher::where('id', $id)->first();
        if ($old_status->approved_by) {
            return redirect('/voucher_aprv_waitinglists')->with('danger', 'purchase Order Already Appoved by ' . $old_status->approved_by->name);
        }

        $this->updatepo_voucher($old_status, $id, $currentuser);

        $voucher = Voucher::where('id', $request->id)->first();

        $this->sendApprovedVoucherNotification($voucher);

        return redirect()
            ->to('voucher_aprv_waitinglists')
            ->with('success', 'Anda Telah Me-Approve ' . $voucher->voucher_no . '');
    }

    public function approve_task($id)
    {
        try {
            $tasks = Task::where('project_id', $id)->get();

            foreach ($tasks as $task) {
                if (is_null($task->approved_by_user_1) && is_null($task->approved_date_user_1)) {
                    Task::where('project_id', $task->project_id)->update([
                        'approved_date_user_1' => Carbon::now(),
                        'approved_by_user_1' => auth()->user()->id,
                    ]);
                } else {
                    Task::where('project_id', $task->project_id)->update([
                        'approved_date_user_2' => Carbon::now(),
                        'approved_by_user_2' => auth()->user()->id,
                        'status' => 'Approved',
                    ]);
                }
            }

            return redirect()
                ->route('task-approval.index')
                ->with('success', new HtmlString('Anda Telah Me-Approve Task pada project ' . '<a href="' . route('project.task', $tasks->first()->project_id) . '">' . $tasks->first()->project->name . '</a>'));
        } catch (\Exception $e) {
            return redirect()->route('task-approval.index')->with('error', 'Failed to approve task');
        }
    }

    public function reject(Request $request, $id)
    {
        if (!$request->remark_reject) {
            return back()->with('danger', 'Reject Reason is Required');
        }

        $request->validate(
            [
                'remark_reject' => 'required',
            ],
            [
                'remark_reject' => 'reject Reason',
            ],
        );

        $currentuser = Auth::user();

        $old_status = PurchaseOrder::where('id', $id)->get()->first();
        $history = new HistoryPurchase();
        $history->action_start = $old_status->status;
        $history->action_end = 'Rejected';
        $history->referensi = $old_status->po_no;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();
        // $pr = User::where("type",3)->get();
        $po = PurchaseOrder::where('id', $request->id)->first();
        $recerved = User::whereIn('type', [2, 3, 5])->get();
        // $save = PurchaseOrder::find($request->id);
        // $save->remark_reject = $request->remark_apprv;
        // $save->status = 'Rejected';
        // $save->save();
        // dd($request->remark_reject);
        PurchaseOrder::where('id', $request->id)->update([
            'status' => 'Rejected',
            'remark_reject' => $request->remark_reject,
        ]);

        $checkprhavepo = PurchaseOrder::where('pr_no', $po->pr_no)
            ->where('status', '!=', 'Cancel')
            ->where('status', '!=', 'Rejected')
            ->get();
        if (count($checkprhavepo)) {
            PurchaseRequest::where('pr_no', $po->pr_no)->update([
                'status' => 'Partially',
            ]);
        } else {
            PurchaseRequest::where('pr_no', $po->pr_no)->update([
                'status' => 'New',
            ]);
        }

        $freshpo = PurchaseOrder::where('id', $po->id)->first();

        $mainmessage = '';

        foreach ($freshpo->podetail as $no => $barang) {
            $mainmessage = $mainmessage . ($no + 1) . '. ' . $barang->prdetail->item_name . "\n Qty: " . $barang->qty . '' . $barang->unit . "\n";
        }

        $messageh3 = url('po_details', [$freshpo->id]);

        foreach ($recerved as $nilai) {
            // $msg = "*".config('app.company', 'SNE')." ERP* \n\nPembelian disetujui\nNo. PO: *" . $old_status->po_no . "* \n\n" . $mainmessage . "\n\n" . $messageh3 . "\nApproved by: *" . auth()->user()->name . "* \n_Ini adalah pesan otomatis. Simpan nomor ini sebagai contact agar URL pada pesan dapat di-klik._";
            $msg = '*' . config('app.company', 'SNE') . " ERP* \n\nPembelian tidak disetujui\nNo. PO: *" . $freshpo->po_no . "* \nProject: " . $freshpo->pr->project->name . "\nRequested by: " . $freshpo->pr->requester . "\nBagian pekerjaan: " . $freshpo->pr->partof . " \n\n" . $mainmessage . "\n\n" . $messageh3 . "\nApproved by: *" . auth()->user()->name . "* \n\n_Ini adalah pesan otomatis. Simpan nomor ini sebagai contact agar URL pada pesan dapat di-klik._";

            if ($nilai->phone_number) {
                //dimatikan sementara
                // WaMessage::create([
                //     "number"    => $nilai->phone_number,
                //     "message"   => $msg
                // ]);
            }
        }
        // $penerimaemail = GetEmails::get();
        // foreach ($penerimaemail as $email) {
        //     EmailSend::create([
        //         "email"         => $email,
        //         "po_id"         => $freshpo->id,
        //         "created_by"    => auth()->user()->id,
        //         "type"          => "PaymentUploaded"
        //     ]);
        // }

        // foreach ($recerved as $pur) {
        //     $podata = [
        //         'po_no' => $po->po_no,
        //         'po_detail' => $po->id,
        //     ];

        //     Notification::send($pur, new PurchaseOrderRevert($podata));
        // }

        return redirect()
            ->to('aprv_waitinglists')
            ->with('success', 'Anda Telah Me-Reject ' . $po->po_no . '');
    }

    public function ajukan(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $submitted = PurchaseOrder::where('id', $request->id)->first();

            if ($submitted->status == 'Wait For Approval' || $submitted->status == 'Approved' || $submitted->status == 'Need to Pay' || $submitted->status == 'Partialy Paid' || $submitted->status == 'Paid') {
                return redirect()
                    ->route('purchase-orders')
                    ->with('danger', 'PO ' . $submitted->po_no . ' Anda Telah diajukan Sebelumnya');
            }
            $currentuser = Auth::user();
            $old_status = PurchaseOrder::where('id', $id)->get()->first();
            $resutlamount = GetAmount::get($old_status);
            // dd($resutlamount["total"]);
            PurchaseOrder::where('id', $id)->update(['total_amount' => $resutlamount['total']]);
            $history = new HistoryPurchase();
            $history->action_start = $old_status->status;
            $history->action_end = 'Wait For Approval';
            $history->referensi = $old_status->po_no ? $old_status->po_no : '-';
            $history->action_by = $currentuser->id;
            $history->created_by = $currentuser->id;
            $history->action_date = Carbon::now();
            $history->created_at = Carbon::now();
            $history->save();

            $containBulk = $old_status->pr?->prdetail->contains(function ($prdetail) {
                return $prdetail->is_bulk == 1;
            });


            $isPoStock = $old_status->podetail->contains(function ($podetail) {
                return $podetail->is_stock == 1;
            });

            $isRawMaterials = $old_status->podetail->contains(function ($podetail) {
                return $podetail->is_raw_materials == 1;
            });


            if ($old_status->pr?->pr_type == 'Barang' || $containBulk || $old_status->pr_no == null) {
                if ($submitted->po_no) {
                    $nopo = $submitted->po_no;
                } else {

                    if($isPoStock || $isRawMaterials) {
                        $nopo = GeneratePoNo::get(null, $submitted->id);
                    } else {
                        $nopo = GeneratePoNo::get($old_status->project->project_code, $submitted->id);
                    }

                }
                PurchaseOrder::where('id', $request->id)->update([
                    'status' => 'Wait For Approval',
                    'po_no' => $nopo,
                ]);
            } else {
                if ($submitted->po_no) {
                    $nopo = $submitted->po_no;
                } else {
                    $nopo = GenerateSpkNo::get($old_status->project->project_code, $submitted->id);
                }
                PurchaseOrder::where('id', $request->id)->update([
                    'status' => 'Wait For Approval',
                    'po_no' => $nopo,
                ]);
            }

            $po = PurchaseOrder::where('id', $id)->first();
            $reserved = User::whereIn('type', [2, 3, 4, 5])->get();

            foreach ($reserved as $key => $pur) {
                $podata = [
                    'po_no' => $po->po_no,
                    'created_by' => $request->penerima,
                    'po_detail' => $po->id,
                ];
                Notification::send($pur, new PoWaitingList($podata));
            }

            $mainmessage = '';
            $amount = 0;
            foreach ($po->podetail as $no => $barang) {
                $itemName = $barang->prdetail ? $barang->prdetail->item_name : $barang->item->name;
                $itemUnit = $barang->prdetail ? $barang->prdetail->unit : $barang->unit;
                $mainmessage = $mainmessage . ($no + 1) . '. ' . $itemName . "\n Qty: " . $barang->qty . '' . $itemUnit . "\n";
                $amount = $amount + $barang->amount;
            }
            $messageh3 = url('aprv_waitinglists');
            $msgrequester = '';

            if ($old_status->pr != null) {
                foreach ($old_status->pr->users as $keyre => $value) {
                    if ($keyre == 0) {
                        $msgrequester = $msgrequester . $value->name;
                    } else {
                        $msgrequester = $msgrequester . ',' . $value->name;
                    }
                }
            }

            $ongkir = 0;
            $pajak = 0;
            if ($old_status->deliver_status == 2) {
                $ongkir = $old_status->tarif_ds;
            }

            foreach ($old_status->podetail as $key => $value) {
                if ($key == 0) {
                    if ($value->tax_status != 2) {
                        $pajak = round($amount * 0.11);
                    }
                }
            }

            $totalall = $amount + $ongkir + $pajak;

            $isBulkPo = $old_status->podetail->every(function ($item, $key) {
                return $item->is_bulk == 1;
            });

            foreach ($reserved as $key => $nilai) {
                if (!$isBulkPo && !$isPoStock && !$isRawMaterials) {
                    $msg = '*' . config('app.company', 'SNE') . " ERP* \n\nPengajuan PO\nNo. PO: *" . $old_status->po_no . "* \nProject: " . $old_status->project->name . "\nIssued by: " . auth()->user()->name . "\nRequested by: " . $old_status->pr->requester . "\nBagian Pekerjaan: " . $old_status->pr->partof . "\nTotal: Rp" . number_format($totalall, 0, '', '.') . " \n\n" . $mainmessage . "\n\n" . $messageh3 . " \n\n_Ini adalah pesan otomatis. Simpan nomor ini sebagai contact agar URL pada pesan dapat di-klik._";
                    // $msg = "*".config('app.company', 'SNE')." ERP* \n\nPengajuan PO\nNo. PO: *" . $old_status->po_no . "* \nProject: " . $old_status->project->name . "\nIssued by: " . $msgrequester . "\nBagian Pekerjaan: ".$old_status->pr->partof." \n\n" . $mainmessage . "\n\n" . $messageh3 . " \n_Ini adalah pesan otomatis. Simpan nomor ini sebagai contact agar URL pada pesan dapat di-klik._";
                    // Whatsapp::SendMessage($nilai->phone_number, $msg);
                    if ($nilai->phone_number) {
                        //dimatikan sementara
                        // WaMessage::create([
                        //     "number" => $nilai->phone_number,
                        //     "message" => $msg
                        // ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('purchase-orders')
                ->with('success', 'Anda Telah Berhasil Mengajukan PO ' . $po->po_no);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function ajukanPurchaseRequest($id)
    {
        try {
            $prsubmited = PurchaseRequest::with('prdetail')->where('id', $id)->first();

            if($prsubmited->project_id !== null) {
                $boqProject = $prsubmited->project->boqs_list();
                $allPrDetail = PurchaseRequest::with('prdetail')
                    ->where('project_id', $prsubmited->project_id)
                    ->get();

            try {
                // Sum quantities of each item_id in allPrDetail
                $prItems = [];
                foreach ($allPrDetail as $purchaseRequest) {
                    foreach ($purchaseRequest->prdetail as $item) {
                        if (isset($prItems[$item->item_id])) {
                            $prItems[$item->item_id] += $item->qty;
                        } else {
                            $prItems[$item->item_id] = $item->qty;
                        }
                    }
                }

                // Sum quantities of each item_id in boqProject
                $boqItems = [];
                foreach ($boqProject as $boq) {
                    if (isset($boqItems[$boq->item_id])) {
                        $boqItems[$boq->item_id] += $boq->qty;
                    } else {
                        $boqItems[$boq->item_id] = $boq->qty;
                    }
                }

                // Compare quantities
                $matches = [];
                foreach ($boqItems as $item_id => $qty) {
                    if (isset($prItems[$item_id]) && $prItems[$item_id] == $qty) {
                        $matches[$item_id] = $qty;
                    }
                }

                $totalItemsBoq = count($boqItems);
                $totalMatchedItems = count($matches);

                // if ($totalItemsBoq === $totalMatchedItems) {
                //     $usersEmail = ['antony@satrianusa.group', 'anton@satrianusa.group'];
                //     Mail::to($usersEmail)->send(new CompletePurchaseBoqItemsMail($prsubmited->project));
                // }
            } catch (\Exception $e) {
                dd($e);
            }

            }

            if ($prsubmited->pr_no) {
                return session()->flash('success', 'Purchase Request ' . $prsubmited->pr_no . ' Telah di ajukan sebelumnya');
            }

            // $prgenerate = GeneratePrNo::newPR($id);
            PurchaseRequest::where('id', $id)->update([
                // 'pr_no' => $prgenerate,
                'status' => 'Wait for approval',
            ]);

            $requestedItems = $prsubmited->prdetail;
            $itemIds = $prsubmited->prdetail->pluck('item_id');
            $prices = SupplierItemPrice::whereIn('item_id', $itemIds)->get();

            $suppliers = $prices->groupBy('supplier_id');

            foreach ($suppliers as $key => $items) {
                $supplier = Supplier::where('id', $key)
                    ->where('city', 'like', '%' . $prsubmited->city . '%')
                    ->first();

                if (is_null($supplier)) {
                    continue;
                }

                $currentDateTime = \Illuminate\Support\Carbon::now();
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
                    Mail::to($receiver->email)->send(new \App\Mail\PurchaseRequestCreated($prsubmited));
                }
            }

            return redirect()->back()->with('success', 'Purchase Request telah di ajukan');
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function up_ds(Request $request, $id)
    {
        $ds_id = 0;
        $tarif_ds = 0;
        if ($request->ds_status == 1) {
            if ($request->ds_id) {
                $ds_id = $request->ds_id;
            } else {
                return;
            }
        } else {
            if ($request->tarif_ds) {
                $tarif_ds = $request->tarif_ds;
            } else {
                return;
            }
        }
        $currentuser = Auth::user();

        $old_status = PurchaseOrder::where('id', $id)->get()->first();
        $history = new HistoryPurchase();
        $history->action_start = $old_status->status;
        $history->action_end = 'New With Delivery Services';
        $history->referensi = $old_status->po_no;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();

        $po = PurchaseOrder::where('id', $id)->first();
        PurchaseOrder::where('id', $request->id)->update([
            'status' => PurchaseOrderStatus::NEW_WITH_DS,
            'berat' => 0,
            'ds_id' => $ds_id,
            'tarif_ds' => $tarif_ds,
        ]);

        return redirect()
            ->route('purchase-orders')
            ->with('success', 'Anda Telah Berhasil Menambahkan Jasa Pengiriman Untuk PO ' . $po->po_no . '');
    }

    public function up_driver_memo(Request $request, $id)
    {
        $currentuser = Auth::user();

        $old_status = PurchaseOrder::where('id', $id)->get()->first();
        $history = new HistoryPurchase();
        $history->action_start = $old_status->status;
        $history->action_end = 'Upload Driver';
        $history->referensi = $old_status->po_no;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();
        $po = PurchaseOrder::where('id', $id)->first();
        MemoList::create([
            'po_id' => $po->id,
            'driver_name' => $request->driver_name,
            'vehicle' => $request->vehicle,
        ]);
        PurchaseOrder::where('id', $po->id)->update([
            'driver_memo_status' => 1,
        ]);
        return redirect()
            ->route('purchase-orders')
            ->with('success', 'Anda Telah Berhasil Menambahkan Driver Untuk Memo dengan Nomor PO ' . $po->po_no . '');
    }

    public function revert(Request $request, $id)
    {
        if (!$request->remark) {
            return back()->with('danger', 'Revert Reason is Required');
        }

        $request->validate(
            [
                'remark' => 'required',
            ],
            [
                'remark' => 'Revert Reason',
            ],
        );

        $old_status = PurchaseOrder::where('id', $id)->get()->first();
        $this->pushHistory($old_status, PurchaseOrderStatus::REVERTED);

        $po = PurchaseOrder::where('id', $request->id)->first();

        PurchaseOrder::where('id', $request->id)->update([
            'status' => PurchaseOrderStatus::REVERTED,
            'remark' => $request->remark,
        ]);

        $podata = [
            'po_no' => $po->po_no,
            'po_detail' => $po->id,
        ];

        $userReceiver = User::role([Role::MANAGER, Role::IT, Role::PURCHASING])->get();
        Notification::send($userReceiver, new PurchaseOrderRevert($podata));

        return redirect()
            ->to('aprv_waitinglists')
            ->with('success', 'Anda Telah Mengembalikan ' . $po->po_no . '');
    }

    public function revert_voucher(Request $request, $id)
    {
        if (!$request->remark) {
            return back()->with('danger', 'Revert Reason is Required');
        }

        $request->validate(
            [
                'remark' => 'required',
            ],
            [
                'remark' => 'Revert Reason',
            ],
        );

        $voucher = Voucher::where('id', $id)->get()->first();
        // $this->pushHistory($voucher, PurchaseOrderStatus::REVERTED);

        // $po = PurchaseOrder::where("id", $request->id)->first();

        Voucher::where('id', $request->id)->update([
            'rejected_by' => auth()->user()->id,
            'reason' => $request->remark,
        ]);

        $podata = [
            'voucher_no' => $voucher->voucher_no,
            'po_detail' => 'voucher_aprv_waitinglists',
        ];

        $userReceiver = User::role([Role::MANAGER, Role::IT, Role::PURCHASING])->get();
        Notification::send($userReceiver, new VoucherRevert($podata));

        return redirect()
            ->to('voucher_aprv_waitinglists')
            ->with('success', 'Anda Telah Mengembalikan ' . $voucher->voucher_no . '');
    }

    public function cancel(Request $request, $id)
    {
        $currentuser = Auth::user();

        $old_status = PurchaseOrder::where('id', $id)->get()->first();
        $history = new HistoryPurchase();
        $history->action_start = $old_status->status;
        $history->action_end = 'Cancel';
        $history->referensi = $old_status->po_no;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();

        PurchaseOrder::where('id', $request->id)->update(['status' => 'Cancel']);

        $po = PurchaseOrder::where('id', $id)->first();
        $pr = PurchaseRequest::where('pr_no', $po->pr_no)->first();
        if ($pr->remark) {
            $newremark = $pr->remark . ',' . $po->po_no;
        } else {
            $newremark = $pr->remark;
        }

        $checkprhavepo = PurchaseOrder::where('pr_no', $po->pr_no)
            ->where('status', '!=', 'Cancel')
            ->where('status', '!=', 'Rejected')
            ->get();

        if (count($checkprhavepo)) {
            PurchaseRequest::where('pr_no', $po->pr_no)->update([
                'status' => 'Partially',
                'remark' => $newremark,
            ]);
        } else {
            PurchaseRequest::where('pr_no', $po->pr_no)->update([
                'status' => 'New',
                'remark' => $newremark,
            ]);
        }

        $pivotPr = $old_status->pivotPR()->pluck('pr_id')->unique();
        $prData = PurchaseRequest::whereIn('id', $pivotPr)->get();

        foreach ($pivotPr as $key => $prId) {
            $prRecord = $prData->where('id', $prId)->first();

            $prDetails = PurchaseRequestDetail::where('pr_id', $prId)->whereHas('podetail')->get();

            $poDetails = PurchaseOrderDetail::where('purchase_order_id', $id)->where('status', '!=', 'Cancel')->whereIn('purchase_request_detail_id', $prDetails->pluck('id'))->get();

            $prIdsInPo = $poDetails->pluck('purchase_request_detail_id')->unique();
            $existInOtherPo = $prDetails->whereNotIn('id', $prIdsInPo)->isNotEmpty();

            $status = $existInOtherPo ? 'Partially' : 'New';
            if ($prRecord->status === 'Processed' && !$existInOtherPo) {
                $status = 'New';
            }

            PurchaseRequest::where('id', $prId)->update([
                'status' => $status,
            ]);
        }

        $purches = User::whereIn('type', [2, 3, 5, 7])->get();

        foreach ($purches as $pur) {
            $podata = [
                'po_no' => $po->po_no,
                'created_by' => $request->penerima,
                'po_detail' => $po->id,
            ];

            Notification::send($pur, new PoCancel($podata));
        }
        // return redirect()->back();
        return redirect()
            ->route('purchase-orders')
            ->with('success', 'Anda Telah Berhasil Membatalkan PO ' . $po->po_no . '');
    }

    private function updatepo($old_status, $id, $currentuser)
    {
        if ($old_status->approved_by) {
            return redirect()
                ->to('aprv_waitinglists')
                ->with('success', 'PO ' . $old_status->po_no . ' telah di apporve sebelumnya');
        }

        $history = new HistoryPurchase();
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

        $po = PurchaseOrder::where('id', $id)->update([
            'status' => 'Approved',
            'date_approved' => date('Y-m-d H:i:s'),
            'approved_at' => date('Y-m-d H:i:s'),
            'approved_by' => auth()->user()->id,
        ]);
    }

    private function updatepo_voucher($old_status, $id, $currentuser)
    {
        if ($old_status->approved_by) {
            return redirect()
                ->to('aprv_waitinglists')
                ->with('success', 'PO ' . $old_status->po_no . ' telah di apporve sebelumnya');
        }

        // $history = new HistoryPurchase;
        // $history->action_start = $old_status->status;
        // $history->action_end = 'Approved';
        // $history->referensi = $old_status->po_no;
        // $history->action_by = $currentuser->id;
        // $history->created_by = $currentuser->id;
        // $history->action_date = Carbon::now();
        // $history->created_at = Carbon::now();
        // $history->save();

        // if ($old_status->pr->pr_type == "Barang") {
        //     $pogenerated = GeneratePoNo::get($old_status->project->project_code);
        // } else {
        //     $pogenerated = GenerateSpkNo::get($old_status->project->project_code);
        // }

        Voucher::where('id', $id)->update([
            'date_approved' => date('Y-m-d H:i:s'),
            'approved_by' => auth()->user()->id,
        ]);
    }

    private function updatePopartial($old_status, $id, $currentuser)
    {
        if ($old_status->date_approved == null && $old_status->approved_by == null) {
            $history = new HistoryPurchase();
            $history->action_start = $old_status->status;
            $history->action_end = 'Half Approved';
            $history->referensi = $old_status->po_no;
            $history->action_by = $currentuser->id;
            $history->created_by = $currentuser->id;
            $history->action_date = Carbon::now();
            $history->created_at = Carbon::now();
            $history->save();
            PurchaseOrder::where('id', $id)->update([
                'date_approved' => date('Y-m-d H:i:s'),
                'approved_by' => auth()->user()->id,
            ]);
        } else {
            if ($old_status->approved_by == auth()->user()->id) {
                return redirect()
                    ->to('aprv_waitinglists')
                    ->with('danger', 'Anda telah approved sebelumnya, perlu persetujuan manager lain ' . $old_status->po_no . '');
            }
            $history = new HistoryPurchase();
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

            $po = PurchaseOrder::where('id', $id)->update([
                // "po_no" => $pogenerated,
                'status' => 'Approved',
                'date_approved_2' => date('Y-m-d H:i:s'),
                'approved_at' => date('Y-m-d H:i:s'),
                'approved_by_2' => auth()->user()->id,
            ]);
        }
    }
}
