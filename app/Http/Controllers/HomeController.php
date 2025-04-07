<?php

namespace App\Http\Controllers;

use App\Models\Exchangerate;
use App\Models\IdxPurchaseOrder;
use App\Models\IdxPurchaseRequest;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $userId = \Auth::id();
        // // $cartItems = \Cart::getContent();
        // $items = \Cart::session($userId)->getContent();
        // return view('home', compact('items'));
        $kurs = Exchangerate::all();
        return view('home',["kurs",$kurs]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome()
    {
        return view('adminHome');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function managerHome()
    {
        // purchase request
        $pr = PurchaseRequest::get();
        $total_pr = count($pr);

        $pr_new = PurchaseRequest::where('status','New')->get();
        $total_pr_new = count($pr_new);
        $pr_process = PurchaseRequest::where('status','Draft')->get();
        $total_pr_process = count($pr_process);
        $pr_cancel = PurchaseRequest::where('status','Cancel')->get();
        $total_pr_cancel = count($pr_cancel);

        // purchase order
        $po_new = PurchaseOrder::where('status','New')->get();
        $total_po_new = count($po_new);
        $po_approved = PurchaseOrder::where('status','Approved')->get();
        $total_po_approved = count($po_approved);
        $po_arrived = PurchaseOrder::where('status','Arrived')->get();
        $total_po_arrived = count($po_arrived);
        $po_w_approved = PurchaseOrder::where('status', 'Wait For Approval')->get();
        $total_po_w_approved = count($po_w_approved);
        $po_w_payment = PurchaseOrder::where('status', 'Waiting For Payment')->get();
        $total_po_w_payment = count($po_w_payment);

        $po_cancelled = PurchaseOrder::where('status', 'Cancel')->get();
        $total_po_cancelled = count($po_cancelled);

        $po_rejected = PurchaseOrder::where('status', 'Rejected')->get();
        $total_po_rejected = count($po_rejected);

        $total_po_thisyear = PurchaseOrder::get()->count();
        $total_pr_thisyear = IdxPurchaseRequest::where('id',1)->first();

        return view('managerHome', compact([
            'total_pr',
            'total_po_thisyear',
            'total_pr_new',
            'total_pr_thisyear',
            'total_pr_cancel',
            'pr_cancel',
            'total_pr_process',
            'total_po_new',
            'total_po_approved',
            'total_po_arrived',
            'total_po_w_approved',
            'total_po_w_payment',
            'total_po_cancelled',
            'total_po_rejected'
        ]));
    }
    public function PurchasingHome()
    {
        // purchase request
        $pr = PurchaseRequest::get();
        $total_pr = count($pr);

        $pr_new = PurchaseRequest::where('status','New')->get();
        $total_pr_new = count($pr_new);
        $pr_process = PurchaseRequest::where('status','Draft')->get();
        $total_pr_process = count($pr_process);
        $pr_cancel = PurchaseRequest::where('status','Cancel')->get();
        $total_pr_cancel = count($pr_cancel);

        // purchase order
        $po_new = PurchaseOrder::where('status','New')->get();
        $total_po_new = count($po_new);
        $po_approved = PurchaseOrder::where('status','Approved')->get();
        $total_po_approved = count($po_approved);
        $po_arrived = PurchaseOrder::where('status','Arrived')->get();
        $total_po_arrived = count($po_arrived);
        $po_w_approved = PurchaseOrder::where('status', 'Wait For Approval')->get();
        $total_po_w_approved = count($po_w_approved);
        $po_w_payment = PurchaseOrder::where('status', 'Waiting For Payment')->get();
        $total_po_w_payment = count($po_w_payment);

        $po_cancelled = PurchaseOrder::where('status', 'Cancel')->get();
        $total_po_cancelled = count($po_cancelled);

        $po_rejected = PurchaseOrder::where('status', 'Rejected')->get();
        $total_po_rejected = count($po_rejected);

        $total_po_thisyear = PurchaseOrder::get()->count();
        $total_pr_thisyear = IdxPurchaseRequest::where('id',1)->first();

        return view('PurchasingHome', compact([
            'total_pr',
            'total_po_thisyear',
            'total_pr_new',
            'total_pr_thisyear',
            'total_pr_cancel',
            'pr_cancel',
            'total_pr_process',
            'total_po_new',
            'total_po_approved',
            'total_po_arrived',
            'total_po_w_approved',
            'total_po_w_payment',
            'total_po_cancelled',
            'total_po_rejected'
        ]));
    }
    public function FinanceHome()
    {
        return view('FinanceHome');
    }
    public function ITHome()
    {
        // purchase request
        $pr = PurchaseRequest::get();
        $total_pr = count($pr);

        $pr_new = PurchaseRequest::where('status','New')->get();
        $total_pr_new = count($pr_new);
        $pr_process = PurchaseRequest::where('status','Draft')->get();
        $total_pr_process = count($pr_process);
        $pr_cancel = PurchaseRequest::where('status','Cancel')->get();
        $total_pr_cancel = count($pr_cancel);

        // purchase order
        $po_new = PurchaseOrder::where('status','New')->get();
        $total_po_new = count($po_new);
        $po_approved = PurchaseOrder::where('status','Approved')->get();
        $total_po_approved = count($po_approved);
        $po_arrived = PurchaseOrder::where('status','Arrived')->get();
        $total_po_arrived = count($po_arrived);
        $po_w_approved = PurchaseOrder::where('status', 'Wait For Approval')->get();
        $total_po_w_approved = count($po_w_approved);
        $po_w_payment = PurchaseOrder::where('status', 'Waiting For Payment')->get();
        $total_po_w_payment = count($po_w_payment);

        $po_cancelled = PurchaseOrder::where('status', 'Cancel')->get();
        $total_po_cancelled = count($po_cancelled);

        $po_rejected = PurchaseOrder::where('status', 'Rejected')->get();
        $total_po_rejected = count($po_rejected);

        $total_po_thisyear = PurchaseOrder::get()->count();
        $total_pr_thisyear = IdxPurchaseRequest::where('id',1)->first();

        return view('ITHome', compact([
            'total_pr',
            'total_po_thisyear',
            'total_pr_new',
            'total_pr_thisyear',
            'total_pr_cancel',
            'pr_cancel',
            'total_pr_process',
            'total_po_new',
            'total_po_approved',
            'total_po_arrived',
            'total_po_w_approved',
            'total_po_w_payment',
            'total_po_cancelled',
            'total_po_rejected'
        ]));
    }
    public function LapanganHome()
    {
        return view('LapanganHome');
    }
    public function AdminLapanganHome()
    {
        return view('AdminLapanganHome');
    }
}
