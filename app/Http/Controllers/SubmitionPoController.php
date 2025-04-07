<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\PurchaseOrder;
use Illuminate\Support\Carbon;
use App\Models\HistoryPurchase;
use App\Models\NotificationTop;
use App\Models\SubmitionHistory;
use App\Mail\CompleteUploadPhoto;
use App\Helpers\GenerateReceiptNo;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\NotificationEmailType;
use App\Jobs\GenerateCompleteDocument;
use App\Constants\EmailNotificationTypes;
use App\Notifications\UploadedItemSubmition;
use Illuminate\Support\Facades\Notification;
use App\Helpers\TermOfPayment\GenerateEstimate;
use App\Models\InventoryHistory;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Storage;

class SubmitionPoController extends Controller
{
    public function index()
    {
        $sh = SubmitionHistory::with('purchaseorder', 'item')->orderBy('id', 'desc')->get();
        return view('submitions.index', compact('sh'));
    }

    public function create()
    {
        $item = Item::all();
        $do = DeliveryOrder::all();
        $po = PurchaseOrder::where('status', 'Approved')->orwhere('status', 'Waiting For Payment')->orwhere('status', 'Paid')->orderBy('id', 'desc')->get();

        return view('submitions.create', compact(['po', 'do', 'item']));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'foto_barang' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:5000',
                'qty' => 'required',
                'do_id' => 'required',
                'pic_pengantar' => 'required',
                'penerima' => 'required',
                'actual_date' => 'required',
            ]);

            $imageName = time() . '.' . $request->foto_barang->extension();

            if (config('app.env') === 'production') {

                $tempFotoBarang = $request->foto_barang->storeAs('images/arrived/barang', $imageName, 'local');

                $pathFotoBarang = 'images/arrived/barang/' . $imageName;

                Storage::disk('gcs')->put($pathFotoBarang, fopen($request->foto_barang->getRealPath(), 'r+'));
                Storage::disk('local')->delete($tempFotoBarang);

                $imagePath = Storage::disk('gcs')->url($pathFotoBarang);
            } else {
                $request->foto_barang->move(public_path('images/arrived/barang'), $imageName);

                $imagePath = 'images/arrived/barang/' . $imageName;
            }

            $qty_item_po = PurchaseOrderDetail::where('id', $request->podetail_id)
                ->where('item_id', $request->item_id)
                ->first();

            // $getSameItem = PurchaseOrderDetail::where('purchase_order_id', $request->po_id)
            //     ->where('item_id', $request->item_id)
            //     ->get();

            if ($request->qty > $qty_item_po->qty) {
                $percent = 100;
            } else {
                $qtyPercent = ($request->qty / $qty_item_po->qty) * 100;
                $percent = $qtyPercent + $qty_item_po->percent_complete;

                if ($percent > 100) {
                    $percent = 100;
                }
            }

            $total_sampai_now = $request->qty + $qty_item_po->total_sampai;

            $today = Carbon::now()->format('Y-m-d');

            $save = new SubmitionHistory();

            $save->po_id = $request->po_id;
            $save->item_id = $request->item_id;
            $save->item_name = $qty_item_po->prdetail ? $qty_item_po->prdetail->item_name : $qty_item_po->item->item_name;
            $save->qty = $request->qty;
            $save->unit = $request->unit;
            $save->do_id = $request->do_id;
            $save->pic_pengantar = $request->pic_pengantar;
            $save->penerima = $request->penerima;
            $save->foto_barang = $imagePath;
            $save->actual_date = $request->actual_date;
            $save->save();

            $save_detail = PurchaseOrderDetail::find($qty_item_po->id);
            $save_detail->percent_complete = $percent;

            // if ($getSameItem->count() > 1) {
            //     foreach ($getSameItem as $item) {
            //         $itemPercent = ($request->qty / $item->qty) * 100;
            //         $totalPercent = min(100, $itemPercent + $item->percent_complete);

            //         $same_item_detail = PurchaseOrderDetail::find($item->id);
            //         $same_item_detail->percent_complete = $totalPercent;
            //         $same_item_detail->save();
            //     }
            // }

            if ($total_sampai_now >= $qty_item_po->qty) {
                $request->qty = $qty_item_po->qty - $qty_item_po->total_sampai;
                $total_sampai_now = $qty_item_po->qty;
            }

            $save_detail->total_sampai = $total_sampai_now;

            // if ($getSameItem->count() > 1) {
            //     foreach ($getSameItem as $key => $value) {
            //         $save_detail = PurchaseOrderDetail::find($value->id);
            //         $save_detail->total_sampai = $total_sampai_now > $value->qty ? $value->qty : $total_sampai_now;
            //         $save_detail->save();
            //     }
            // }
            $save_detail->save();

            $avg_percent_po = PurchaseOrderDetail::where('purchase_order_id', $qty_item_po->purchase_order_id)->avg('percent_complete');

            $save_po = PurchaseOrder::find($qty_item_po->purchase_order_id);
            $save_po->percent_complete = $avg_percent_po;

            if ($avg_percent_po >= 100) {
                $currentuser = Auth::user();

                $old_status = PurchaseOrder::where('id', $request->po_id)
                    ->get()
                    ->first();
                $history = new HistoryPurchase();
                $history->action_start = $old_status->status;
                $history->action_end = 'Arrived';
                $history->referensi = $old_status->po_no;
                $history->action_by = $currentuser->id;
                $history->created_by = $currentuser->id;
                $history->action_date = Carbon::now();
                $history->created_at = Carbon::now();
                $history->save();

                $save_po->status_barang = 'Arrived';

                // if ($old_status->status == "Paid") {
                //     $save_po->status = "Completed";

                //     if ($save_po->completeDocument?->count() == 0) {
                //         // Generate complete document
                //         GenerateCompleteDocument::dispatch($request->po_id);

                //         // Send email
                //         $data = (object) [
                //             'po' => $save_po,
                //         ];
                //         $this->sendEmailCompleteDocument($data);
                //     }
                // }

                $save_po->top_date = GenerateEstimate::GetEstimate($request->date_received, $save_po->term_of_payment);
                $save_po->receipt_no = GenerateReceiptNo::get();
                $save_po->save();

                if ($save_po->top_date) {
                    $today = Carbon::now()->format('Y-m-d');
                    $notificationTOP = new NotificationTop();
                    $notificationTOP->purchase_order_id = $save_po->id;
                    $notificationTOP->top_type = $save_po->term_of_payment;
                    $notificationTOP->approve_date = $today;
                    $notificationTOP->est_pay_date = $save_po->top_date;
                    $notificationTOP->created_at = $today;
                    $notificationTOP->save();
                }

                $poData = PurchaseOrder::where('id', $request->po_id)->first();
                $userEmail = ['admin@satrianusa.group', 'workshop@satrianusa.group', 'nur.hidaya@satrianusa.group', 'ops@satrianusa.group'];

                // if (env("DOUBLE_APPROVE")) {
                //     $userEmail[] = $poData->approvedby2->email;
                // } else {
                //     $userEmail[] = $poData->approvedby->email;
                // }

                Mail::to($userEmail)->send(new CompleteUploadPhoto($poData));
                // Send email
                // $arrivedImagesPath = SubmitionHistory::where('po_id', $request->po_id)
                // ->whereNotNull('foto_barang')->limit(3)->get()->pluck('foto_barang')->toArray();

                // $data = (object) [
                //     "po" => $save_po,
                //     "pr" => $save_po->pr,
                //     'uploadedby' => auth()->user()->name,
                //     "arrivedImagesPath" => $arrivedImagesPath,
                //     'submission' => $save
                // ];
                // $this->sendEmailItemArrived($data);
            } elseif ($avg_percent_po > 0) {
                $currentuser = Auth::user();

                $old_status = PurchaseOrder::where('id', $request->po_id)
                    ->get()
                    ->first();
                $history = new HistoryPurchase();
                $history->action_start = $old_status->status;
                $history->action_end = 'Partially Arrived';
                $history->referensi = $old_status->po_no;
                $history->action_by = $currentuser->id;
                $history->created_by = $currentuser->id;
                $history->action_date = Carbon::now();
                $history->created_at = Carbon::now();
                $history->save();

                $save_po->status_barang = 'Partially Arrived';
                $save_po->save();
            }

            $item = Item::where('id', $request->item_id)->first();
            $po = PurchaseOrder::where('id', $request->po_id)->first();

            //notif for created pr
            $podata1 = [
                'po_no' => $po->po_no,
                'po_detail' => $po->id,
                'created_by' => $request->penerima,
                'item' => $item->name,
            ];

        Notification::send($po->pr ? $po->pr->createdby : $po->createdby, new UploadedItemSubmition($podata1));

            //notif for purchesing, finance & manager
            $purches = User::where('type', 2)->orWhere('type', 3)->orWhere('type', 4)->orWhere('type', 5)->get();

            foreach ($purches as $key => $pur) {
                $podata = [
                    'po_no' => $po->po_no,
                    'po_detail' => $po->id,
                    'created_by' => $request->penerima,
                    'item' => $item->name,
                ];

                Notification::send($pur, new UploadedItemSubmition($podata));
            }

        $inventory = Inventory::where('item_id', $request->item_id)->first();
        $historyData = [];

        $updatedDetailsRecord = [];
        $isRawMaterials = $po->podetail->every(fn ($podetail) => $podetail->is_raw_materials == 1);
        $isStock = $po->podetail->every(fn ($podetail) => $podetail->is_stock == 1);

        $warehouseData = null;

        if($isRawMaterials) {
            // $warehouseData = Warehouse::find($save_po->warehouse_id);
            $warehouseData = 'RAW MATERIALS';
        }

        if ($po->pr?->pr_type == "Barang" || $po->podetail->every(fn ($podetail) => $podetail->is_bulk == 1 || $isStock || $isRawMaterials)) {
            if ($inventory) {
                $inventory->update([
                    'stock' => $inventory->stock + $request->qty
                ]);
                $warehouseName = $warehouseData ? $warehouseData : null;
                if ($inventory->details()->where('project_id', $save_po->project_id)->where('warehouse_type', $warehouseName)->exists()) {
                    $updateDetails = $inventory->details()->where('project_id', $save_po->project_id);
                    
                    $historyData = [
                        'inventory_detail_id' => $updateDetails->first()->id,
                        'stock_before' => $updateDetails->first()->stock,
                        'stock_after' => $updateDetails->first()->stock + $request->qty,
                        'stock_change' => $updateDetails->first()->stock + $request->qty - $updateDetails->first()->stock,
                        'user_id' => auth()->id(),
                        'podetail_id' => $request->podetail_id,
                        'type' => 'IN',
                        'actual_date' => $request->actual_date
                    ];

                    $updateDetails->update([
                        'stock' => $inventory->details()->where('project_id', $save_po->project_id)->first()->stock + $request->qty,
                    ]);
                    $updatedDetailsRecord[] = $updateDetails;
                } else {
                    $createDetails = $inventory->details()->create([
                        'inventory_id' => $inventory->id,
                        'project_id' => $save_po->project_id,
                        'stock' => $request->qty,
                        'warehouse_type' => $save_po->project_id !== null ? null : $warehouseData,
                    ]);

                    $updatedDetailsRecord[] = $createDetails;

                    $historyData = [
                        'inventory_detail_id' => $createDetails->id,
                        'stock_before' => 0,
                        'stock_after' => $request->qty,
                        'stock_change' => $request->qty,
                        'user_id' => auth()->id(),
                        'podetail_id' => $request->podetail_id,
                        'type' => 'IN',
                        'actual_date' => $request->actual_date
                    ];
                }
            } else {
                $inventory = Inventory::create([
                    'item_id' => $request->item_id,
                    'stock' => $request->qty
                ]);

                    $createDetails = $inventory->details()->create([
                        'inventory_id' => $inventory->id,
                        'project_id' => $save_po->project_id,
                        'stock' => $request->qty,
                        'warehouse_type' => $save_po->project_id !== null ? null : $warehouseData,
                    ]);

                    $updatedDetailsRecord[] = $createDetails;

                    $historyData = [
                        'inventory_detail_id' => $createDetails->id,
                        'stock_before' => 0,
                        'stock_after' => $request->qty,
                        'stock_change' => $request->qty,
                        'user_id' => auth()->id(),
                        'podetail_id' => $request->podetail_id,
                        'type' => 'IN',
                        'actual_date' => $request->actual_date
                    ];
                }

                InventoryHistory::create($historyData);
            }

            DB::commit();
            return redirect('/po_details/' . $request->po_id)->with('success', 'You have successfully uploaded the item image.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
