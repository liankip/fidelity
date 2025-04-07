<?php

namespace App\Http\Controllers;
use App\Models\PurchaseOrder;
use App\Models\SubmitionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoViewSubmitionController extends Controller
{
    //
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function viewphoto_submition($po_id)
    {
        $po = PurchaseOrder::where('id',$po_id)->get()->first();
        // dd($po);
        // $po_no = $po->po_no;
        // $invoices = invoice::with("purchaseorder")->orderBy('created_at','desc')->paginate(10);
        $sh = SubmitionHistory::where('po_id',$po->id)->orderBy('id','desc')->get();

        return view('photo_view.photo_submition', compact(['sh','po']));
    }

    public function updateFile (Request $request, $id)
    {
        try {
            $submissionHistory = SubmitionHistory::findOrFail($id);

            if ($request->hasFile('foto_barang')) {
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

                $submissionHistory->foto_barang = $imagePath;
                $submissionHistory->save();
            }

            return response()->json(['success' => 'File updated successfully']);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['error' => 'Error updating file'], 500);
        }

    }
}
