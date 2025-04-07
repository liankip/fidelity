<?php

namespace App\Http\Controllers;

use App\Helpers\GetEmails;
use App\Models\DeliveryOrder;
use App\Models\EmailSend;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Notifications\UploadedDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class UploadDoController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $do = DeliveryOrder::all();
        return view('DeliveryOrderUpload', compact('do'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'image' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:5000',
                'do_no' => 'required',
            ],
            [],
            [
                'image' => 'Image',
                'do_no' => 'DO Number',
            ],
        );

        $imageName = time() . '.' . $request->image->extension();

        if (config('app.env') === 'production') {
            $tempPath = $request->image->storeAs('images/do', $imageName, 'local');

            $pathFile = 'images/do/' . $imageName;

            Storage::disk('gcs')->put($pathFile, fopen($request->image->getRealPath(), 'r+'));

            Storage::disk('local')->delete($tempPath);

            $path = Storage::disk('gcs')->url($pathFile);
        } else {
            $request->image->move(public_path('images/do'), $imageName);

            $path = 'images/do/' . $imageName;
        }

        $deliveryOrder = DeliveryOrder::create([
            'do_no' => $request->do_no,
            'do_type' => $request->do_type,
            'do_pict' => $path,
            'referensi' => $request->referensi,
            'created_by' => auth()->user()->id,
        ]);

        $po = PurchaseOrder::where('po_no', $request->referensi)->first();

        $purches = User::where('type', 4)->orWhere('type', 3)->orWhere('type', 5)->get();

        foreach ($purches as $key => $pur) {
            $podata = [
                'po_no' => $po->po_no,
                'created_by' => $request->penerima,
                'do_no' => $request->do_no,
            ];
            Notification::send($pur, new UploadedDo($podata));
        }

        foreach (GetEmails::get() as $email) {
            EmailSend::create([
                "email"         => $email,
                "po_id"         => $po->id,
                "created_by"    => auth()->user()->id,
                "type"          => "DOUploaded",
                "do_id"         => $deliveryOrder->id
            ]);
        }

        $mainmessage = '';
        foreach ($po->podetail as $no => $barang) {
            $itemName = $barang->prdetail ? $barang->prdetail->item_name : $barang->item->name;
            $mainmessage = $mainmessage . ($no + 1) . '. ' . $itemName . "\n Qty: " . $barang->qty . '' . $barang->unit . "\n";
        }

        return redirect()->route('po-detail', $po->id)
            ->with('success', 'Surat Jalan ' . $request->do_no . 'Berhasil di Tambahkan untuk PO No ' . $po->po_no)
            ->with('image', $imageName);
    }

    public function updateFile(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $doData = DeliveryOrder::findOrFail($id);

            if ($request->hasFile('foto_barang')) {
                $imageName = time() . '.' . $request->foto_barang->extension();

                if (config('app.env') === 'production') {

                    $pathFile = 'images/do/' . $imageName;

                    Storage::disk('gcs')->put($pathFile, fopen($request->foto_barang->getRealPath(), 'r+'));

                    $doPict = Storage::disk('gcs')->url($pathFile);
                } else {
                    $request->foto_barang->move(public_path('images/do'), $imageName);
                    $doPict = 'images/do/' . $imageName;
                }

                $doData->do_pict = $doPict;
                $doData->save();
            }

            DB::commit();
            return response()->json(['success' => 'File updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return response()->json(['error' => 'Error updating file'], 500);
        }
    }
}
