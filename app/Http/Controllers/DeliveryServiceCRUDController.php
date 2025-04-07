<?php

namespace App\Http\Controllers;

use App\Exports\DeliveryServicesExport;
use App\Models\DeliveryService;
use Illuminate\Http\Request;
// use App\Exports\VendorsExport;
use App\Imports\DeliveryServicesImport;
// use App\Imports\VendorsImport;
use Maatwebsite\Excel\Facades\Excel;

class DeliveryServiceCRUDController extends Controller
{
    public function index()
    {
        $userId = \Auth::id();
        $items = \Cart::session($userId)->getContent();
        $delivery_services = deliveryservice::where("deleted_at", null)->orderBy('id', 'desc')->paginate(10);
        return view('masterdata.delivery_services.index', compact(['items', 'delivery_services']));
    }

    public function create()
    {
        return view('masterdata.delivery_services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'ground' => 'required',
            'keterangan' => 'required',
            // 'tarif_per_kg' => 'required',
            // 'created_by' => 'required',
            // 'unit' => 'required'
        ]);
        $deliveryservice = new deliveryservice;
        $deliveryservice->name = $request->name;
        $deliveryservice->ground = $request->ground;
        $deliveryservice->keterangan = $request->keterangan;
        $deliveryservice->tarif_per_kg = 0;
        $deliveryservice->created_by = auth()->user()->id;
        $deliveryservice->save();
        return redirect()->route('delivery_services.index')
            ->with('success', 'deliveryservice has been created successfully.');
    }

    public function show(deliveryservice $deliveryservice)
    {
        return view('masterdata.delivery_services.show', compact('deliveryservice'));
    }

    public function edit($id)
    {
        $deliveryservicedata = DeliveryService::where("id", $id)->first();

        return view('masterdata.delivery_services.edit', compact('deliveryservicedata'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'ground' => 'required',
            // 'tarif_per_kg' => 'required',
            'keterangan' => 'required',

        ]);
        $deliveryservice = deliveryservice::find($id);
        $deliveryservice->name = $request->name;
        $deliveryservice->ground = $request->ground;
        $deliveryservice->keterangan = $request->keterangan;
        $deliveryservice->updated_by = auth()->user()->id;

        $deliveryservice->save();
        return redirect()->route('delivery_services.index')
            ->with('success', 'deliveryservice Has Been updated successfully');
    }

    public function destroy($id)
    {
        DeliveryService::where("id", $id)->delete();

        return redirect()->route('delivery_services.index')
            ->with('success', 'deliveryservice has been deleted successfully');
    }

    public function export()
    {
        return Excel::download(new DeliveryServicesExport, 'sne-masterdata-deliveryservices.xlsx');
    }

    public function import()
    {
        Excel::import(new DeliveryServicesImport, request()->file('file'));

        return back();
    }
}
