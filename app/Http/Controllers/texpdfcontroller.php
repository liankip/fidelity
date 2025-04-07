<?php

namespace App\Http\Controllers;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\CompanyDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;
class texpdfcontroller extends Controller
{
    public function index(){
        // $purchase_orders = purchaseorder::all()->where('id',$id);
        // $employee = Employee::all();
        return view('documents.po');
    }
    public function show($id){
        $purchase_orders = purchaseorder::all()->where('id',$id);
        $purchase_orders = purchaseorder::all()->where('id',$id);
        // $employee = Employee::all();
        return view('documents.po');
    }
    //
    // Generate PDF
    public function createPDF() {
        // retreive all records from db
        // $data = Employee::all();
        // share data to view
        // view()->share('employee',$data);
        // $pdf = PDF::loadView('documents.po', $data);
        // download PDF file with download method
        // return $pdf->download('sne_purchase_order.pdf');
      }
}
