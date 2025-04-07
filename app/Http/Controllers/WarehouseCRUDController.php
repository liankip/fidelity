<?php

namespace App\Http\Controllers;

use App\Exports\WarehousesExport;
use App\Imports\WarehousesImport;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WarehouseCRUDController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:it|top-manager|purchasing|manager|finance');
    }

    public function index(Request $request)
    {
        if ($request->search) {
            $searchcompact = $request->search;
            $warehouses = Warehouse::where('deleted_at', null)->orwhere("pic", "like", "%" . $request->search . "%")->orWhere("name", "like", "%" . $request->search . "%")->orWhere("email", "like", "%" . $request->search . "%")->orWhere("phone", "like", "%" . $request->search . "%")->orWhere("address", "like", "%" . $request->search . "%")->orWhere("city", "like", "%" . $request->search . "%")->orWhere("province", "like", "%" . $request->search . "%")->paginate(5);
            $warehouses->appends(['search' => $request->search]);
        } else {
            $searchcompact = "";
            $warehouses = Warehouse::where('deleted_at', null)->orderBy('id', 'desc')->paginate(5);
        }
        $userId = \Auth::id();
        $warehouses = \Cart::session($userId)->getContent();
        $warehouses = warehouse::where('deleted_at', null)->orderBy('id', 'desc')->paginate(5);
        return view('masterdata.warehouses.index', compact(['warehouses', 'warehouses', "searchcompact"]));
    }

    public function create()
    {
        return view('masterdata.warehouses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'pic' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'post_code' => 'required',
            // 'created_by' => 'required'
        ]);
        $warehouse = new warehouse;
        $warehouse->name = $request->name;
        $warehouse->pic = $request->pic;
        $warehouse->email = $request->email;
        $warehouse->phone = $request->phone;
        $warehouse->address = $request->address;
        $warehouse->city = $request->city;
        $warehouse->province = $request->province;
        $warehouse->post_code = $request->post_code;
        $warehouse->created_by = auth()->user()->id;
        $warehouse->save();
        return redirect()->route('warehouses.index')
            ->with('success', 'warehouse has been created successfully.');
    }

    public function show(warehouse $warehouse)
    {
        return view('masterdata.warehouses.show', compact('warehouse'));
    }

    public function edit(warehouse $warehouse)
    {
        return view('masterdata.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'pic' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            'post_code' => 'required',
            // 'updated_by' => 'required'
        ]);
        $warehouse = warehouse::find($id);
        $warehouse->name = $request->name;
        $warehouse->pic = $request->pic;
        $warehouse->email = $request->email;
        $warehouse->phone = $request->phone;
        $warehouse->address = $request->address;
        $warehouse->city = $request->city;
        $warehouse->province = $request->province;
        $warehouse->post_code = $request->post_code;
        $warehouse->updated_by = auth()->user()->id;
        $warehouse->save();
        return redirect()->route('warehouses.index')
            ->with('success', 'warehouse Has Been updated successfully');
    }

    public function destroy(warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouses.index')
            ->with('success', 'warehouse has been deleted successfully');
    }

    public function export()
    {
        return Excel::download(new WarehousesExport, 'sne-masterdata-warehouses.xlsx');
    }

    public function import()
    {
        Excel::import(new WarehousesImport, request()->file('file'));

        return back();
    }
}
