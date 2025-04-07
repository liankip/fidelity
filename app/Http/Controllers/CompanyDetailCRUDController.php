<?php

namespace App\Http\Controllers;

use App\Exports\CompanyDetailsExport;
use App\Models\CompanyDetail;
use Illuminate\Http\Request;
use App\Exports\VendorsExport;
use App\Imports\CompanyDetailsImport;
use App\Imports\VendorsImport;
use Maatwebsite\Excel\Facades\Excel;

class CompanyDetailCRUDController extends Controller
{
    //
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $userId = \Auth::id();
        // $cartItems = \Cart::getContent();
        $items = \Cart::session($userId)->getContent();
        $companydetails = companydetail::orderBy('id','desc')->paginate(5);
        return view('masterdata.companydetails.index', compact(['items','companydetails']));
    }
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
    return view('masterdata.companydetails.create');
    }
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
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
    'npwpd' => 'required',
    'signature' => 'required',
    'created_by' => 'required',
    ]);
    $companydetail = new companydetail;
    $companydetail->name = $request->name;
    $companydetail->pic = $request->pic;
    $companydetail->email = $request->email;
    $companydetail->phone = $request->phone;
    $companydetail->address = $request->address;
    $companydetail->city = $request->city;
    $companydetail->province = $request->province;
    $companydetail->post_code = $request->post_code;
    $companydetail->npwpd = $request->npwpd;
    $companydetail->signature = $request->signature;
    $companydetail->creted_by = $request->creted_by;
    $companydetail->save();
    return redirect()->route('companydetails.index')
    ->with('success','companydetail has been created successfully.');
    }
    /**
    * Display the specified resource.
    *
    * @param  \App\companydetail  $companydetail
    * @return \Illuminate\Http\Response
    */
    public function show(companydetail $companydetail)
    {
    return view('masterdata.companydetails.index',compact('companydetail'));
    }
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\companydetail  $companydetail
    * @return \Illuminate\Http\Response
    */
    public function edit(companydetail $companydetail)
    {
    return view('masterdata.companydetails.edit',compact('companydetail'));
    }
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\companydetail  $companydetail
    * @return \Illuminate\Http\Response
    */
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
        'npwpd' => 'required',
        'signature' => 'required',
        'updated_by' => 'required'
    ]);
    $companydetail = companydetail::find($id);
    $companydetail->name = $request->name;
    $companydetail->pic = $request->pic;
    $companydetail->email = $request->email;
    $companydetail->phone = $request->phone;
    $companydetail->address = $request->address;
    $companydetail->city = $request->city;
    $companydetail->province = $request->province;
    $companydetail->post_code = $request->post_code;
    $companydetail->npwpd = $request->npwpd;
    $companydetail->signature = $request->signature;
    $companydetail->updated_by = $request->updated_by;
    $companydetail->save();
    return redirect()->route('companydetails.index')
    ->with('success','companydetail Has Been updated successfully');
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\companydetail  $companydetail
    * @return \Illuminate\Http\Response
    */
    public function destroy(companydetail $companydetail)
    {
        $companydetail->delete();
        return redirect()->route('companydetails.index')
        ->with('success','companydetail has been deleted successfully');
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export()
    {
        return Excel::download(new CompanyDetailsExport, 'sne-masterdata-companydetails.xlsx');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function import()
    {
        Excel::import(new CompanyDetailsImport,request()->file('file'));

        return back();
    }
}
