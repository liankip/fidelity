<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\NotificationTop;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class PaymentWaitingListController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $cash = PurchaseOrder::with("project","warehouse")
        // ->where('status', 'Concern')
        //     ->orWhere(function($cash) {
        //         $cash->where('term_of_payment','Cash')
        //               ->where('status','Approved');
        //     })->get();

        return redirect()->route("payment-waiting-lists");
        $po = PurchaseOrder::with("project", "warehouse","podetail")
            ->Where(function ($query) {
                $query->where('term_of_payment', "!=", "CASH")->where('term_of_payment', "!=", "CoD")
                    ->where('status', 'Approved');
            })
            ->orWhere(function ($query) {
                $query->where('term_of_payment', 'CoD')
                    ->where('status', 'Completed');
            })
            ->orWhere(function ($query) {
                $query->where('term_of_payment', "!=", "Cash")
                    ->where('status', 'Completed');
            })
            ->get();
        // ->where('status','Approved')
        // ->where('term_of_payment','Cash')->get();

        // $cash = PurchaseOrder::with("warehouse","project")->where('status','Approved')->where('term_of_payment','Cash');
        // $non_cash = PurchaseOrder::with("warehouse", "project")
        //     ->orwhere('status', 'Concern')
        //     ->orWhere(function ($cash) {
        //         $cash->where('term_of_payment', 'CoD')
        //             ->where('status', 'Completed');
        //     })->get();
        // ->where('status','Completed')
        // ->orderBy('po_no',"DESC")->get();

        return view('payment_waiting_lists.index', compact(['po']));
    }
    public function urgent_list()
    {
        $today = Carbon::now()->format('Y-m-d');
        $get_list = NotificationTop::where('paid_off_date', 'null')->get();


        // $est_cash    = date('Y-m-d', strtotime('+3 days', strtotime($today)));
        $cash = PurchaseOrder::with("project", "warehouse")
            ->where('status', 'Concern')
            ->orWhere(function ($cash) {
                $cash->where('term_of_payment', 'Cash')
                    ->where('status', 'Approved');
            })->get();
        // ->where('status','Approved')
        // ->where('term_of_payment','Cash')->get();

        // $cash = PurchaseOrder::with("warehouse","project")->where('status','Approved')->where('term_of_payment','Cash');
        $list_alert = NotificationTop::with("purchaseorder")->where('paid_off_date', 'null')->get();
        $non_cash = PurchaseOrder::with("warehouse", "project")
            ->where('status', 'Concern')
            ->orWhere(function ($cash) {
                $cash->where('term_of_payment', 'CoD')
                    ->where('status', 'Completed');
            })->get();
        // ->where('status','Completed')
        // ->orderBy('po_no',"DESC")->get();

        return view('payment_waiting_lists.index', compact(['cash', 'non_cash']));
    }
}
