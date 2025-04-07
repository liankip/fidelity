<?php

namespace App\Http\Controllers;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\IdxPurchaseOrder;
use App\Models\HistoryPurchase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChangePoNumController extends Controller
{
    //
    public function change_po_num(Request $request, $id)
    {
        $currentuser = Auth::user();
        $old_po = PurchaseOrder::where('id',$id)->get()->first();
        $old_po_no = $old_po->po_no;
        $old_idx = IdxPurchaseOrder::where('id',1)->get()->first();
        $num_po_no = (int) $request->new_po_num;
        $num_idx = (int) $old_idx->idx;

        if($num_po_no > $num_idx)
        {
            PurchaseOrder::where('id',$id)->update([
                'po_no' => $request->new_po_num.'/'.substr($old_po->po_no,5),
            ]);

            IdxPurchaseOrder::where('id',1)->update([
                'idx' => $request->new_po_num,
            ]);

            $new_po = PurchaseOrder::where('id',$id)->get()->first();
            $new_po_no = $new_po->po_no;

            $history = new HistoryPurchase;
            $history->action_start = $old_po_no;
            $history->action_end = 'Change to '.$new_po_no;
            $history->referensi = $new_po_no;
            $history->action_by = $currentuser->id;
            $history->created_by = $currentuser->id;
            $history->action_date = Carbon::now();
            $history->created_at = Carbon::now();
            $history->save();

            return redirect()->route('purchase-orders')
                ->with('success','Anda Telah Berhasil Mengubah Nomor PO '.$old_po_no.' Menjadi '.$new_po_no);
        }
        else if($num_po_no < $num_idx)
        {
            return redirect()->route('purchase-orders')
                ->with('danger','Anda Tidak Boleh Merubah Nomor PO lebih Rendah dari yang terakhir');
        }
        else
        {
            return redirect()->route('purchase-orders')
                ->with('danger','Anda Tidak Boleh Merubah Nomor PO lebih Rendah dari yang terakhir');
        }



    }

}
