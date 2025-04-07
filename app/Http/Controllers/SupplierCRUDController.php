<?php

namespace App\Http\Controllers;

use App\Exports\suppliersExport;
use App\Imports\suppliersImport;
use App\Models\Supplier;
use App\Models\SupplierAdditionalFile;
use App\Models\SupplierItemPrice;
use App\Models\User;
use App\Notifications\SupplierCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SupplierCRUDController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:it|top-manager|purchasing|manager|finance');
    }

    public function index(Request $request)
    {

        $suppliers = supplier::where('is_approved', true)->orderBy('id', 'desc')->get();
        $userId = \Auth::id();
        $items = \Cart::session($userId)->getContent();
        $supplierNeedApproval = Supplier::where('is_approved', false)->get();
        return view('masterdata.suppliers.index', compact(['items', 'suppliers', 'supplierNeedApproval']));
    }

    public function create()
    {
        return view('masterdata.suppliers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'pic' => 'required',
            'phone' => 'required',
            'city' => 'required',
            'province' => 'required',
            'term_of_payment' => 'required',
            'npwp' => 'required',
            'surveyor_name' => 'required',
            'recommended_by' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422); // 422 Unprocessable Entity status code
            } else {
                return back()->withInput()->withErrors($validator)->with("danger", "Please fix the following validation errors.");
            }
        }


        $getexist = Supplier::where("name", $request->name)->get();
        if (count($getexist)) {
            return back()->withInput()->with("danger", "Supplier name is exist, please change another name");
        }
        $image_name = null;
        if ($request->hasFile('ktp_image')) {
            $image = $request->file('ktp_image');
            $extension = $image->getClientOriginalExtension();
            $randomName = Str::random(40) . '.' . $extension;
            $imagePath = $image->storeAs('images/suppliers', $randomName, 'public');

            $image_name = $imagePath;
        }

        $supplier = new supplier;
        $supplier->name = $request->name;
        $supplier->pic = $request->pic;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->address = $request->address;
        $supplier->city = $request->city;
        $supplier->province = $request->province;
        $supplier->post_code = $request->post_code;
        $supplier->created_by = auth()->user()->id;
        $supplier->term_of_payment = $request->term_of_payment;
        $supplier->npwp = $request->npwp;
        $supplier->norek = $request->norek;
        $supplier->bank_name = $request->bank_name;
        $supplier->ktp_image = $image_name;
        $supplier->recommended_by = $request->recommended_by;
        $supplier->surveyor_name = $request->surveyor_name;
        $supplier->is_approved = (bool) auth()->user()->hasTopLevelAccess();
        $supplier->approved_by = auth()->user()->hasTopLevelAccess() ? auth()->user()->id : null;
        $supplier->save();

        if ($request->hasFile('additional_files')) {
            foreach ($request->file('additional_files') as $file) {
                $extension = $file->getClientOriginalExtension();
                $randomName = Str::random(40) . '.' . $extension;
                $imagePath = $file->storeAs('images/suppliers', $randomName, 'public');
                SupplierAdditionalFile::create([
                    'path' => $imagePath,
                    'supplier_id' => $supplier->id,
                ]);
            }
        }

        if (auth()->user()->hasTopLevelAccess()) {
            return redirect()->route('suppliers.index')
                ->with('success', 'supplier has been created successfully.');
        }

        $data = [
            'action_by' => auth()->user()->name,
        ];
        Notification::send(User::role(['manager', 'it'])->get(), new SupplierCreated($data));

        return redirect()->route('suppliers.index')
            ->with('success', 'supplier has been created successfully. Please wait for approval.');
    }

    public function show($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        return view('masterdata.suppliers.detail', compact('supplier'));
    }

    public function edit(supplier $supplier)
    {
        return view('masterdata.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'pic' => 'required',
            'phone' => 'required',
            // 'address' => 'required',
            'city' => 'required',
            'province' => 'required',
            // 'post_code' => 'required',
            'term_of_payment' => 'required'
        ]);

        $supplier = supplier::find($id);
        if ($request->hasFile('ktp_image')) {
            $image = $request->file('ktp_image');
            $extension = $image->getClientOriginalExtension();
            $randomName = Str::random(40) . '.' . $extension;
            $imagePath = $image->storeAs('images/suppliers', $randomName, 'public');

            if ($supplier->ktp_image && Storage::disk('public')->exists($supplier->ktp_image)) {
                Storage::disk('public')->delete($supplier->ktp_image);
            }

            $supplier->ktp_image = $imagePath;
        }

        $supplier->name = $request->name;
        $supplier->pic = $request->pic;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->address = $request->address;
        $supplier->city = $request->city;
        $supplier->province = $request->province;
        $supplier->post_code = $request->post_code;
        $supplier->updated_by = auth()->user()->id;
        $supplier->term_of_payment = $request->term_of_payment;
        $supplier->npwp = $request->npwp;
        $supplier->norek = $request->norek;
        $supplier->bank_name = $request->bank_name;
        $supplier->recommended_by = $request->recommended_by;
        $supplier->surveyor_name = $request->surveyor_name;

        $supplier->save();
        return redirect()->route('suppliers.index')
            ->with('success', 'supplier Has Been updated successfully');
    }

    public function destroy(supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')
            ->with('success', 'supplier has been deleted successfully');
    }

    public function item_list($supplierId)
    {
        $supplier = Supplier::where("id", $supplierId)->first();
        $items = SupplierItemPrice::where("supplier_id", $supplierId)->with("item", "supplier")->paginate(10);
        return view('masterdata.suppliers.show', compact(['supplier', 'items']));
    }

    public function export()
    {
        return Excel::download(new suppliersExport, 'sne-masterdata-suppliers.xlsx');
    }

    public function import()
    {
        Excel::import(new suppliersImport, request()->file('file'));

        return back();
    }
}
