<?php

namespace App\Http\Controllers;

use App\Models\HistoryPurchase;
use App\Models\IdxPurchaseRequest;
use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\User;
use App\Models\Warehouse;
use App\Notifications\PurchaseRequestCreated;
use App\Roles\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PurchaseRequestController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //for redirect to currect page
        return redirect(url('purchase-requests'));
        if ($request->search) {
            $searchcompact = $request->search;
            $purchase_requests = purchaserequest::with("podetail")->where("pr_no", "like", "%" . $request->search . "%")->orWhere("pr_type", "like", "%" . $request->search . "%")->orWhere("status", "like", "%" . $request->search . "%")->orWhere("remark", "like", "%" . $request->search . "%")->orderBy('created_at', 'desc');
            $purchase_requests->appends(['search' => $request->search]);
        } else {
            $searchcompact = "";
            $purchase_requests = purchaserequest::with("podetail")->orderBy('created_at', 'desc');
        }

        $purchase_requests = $purchase_requests->paginate(8);
        $userId = \Auth::id();
        // $cartItems = \Cart::getContent();
        $items = \Cart::session($userId)->getContent();

        // $cekpr_detail = PurchaseRequestDetail::all()->where('pr_id','id');
        return view('purchase_requests.index', compact(['items', 'purchase_requests', 'searchcompact']));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $userId = \Auth::id();
        // $cartItems = \Cart::getContent();
        $items = \Cart::session($userId)->getContent();
        $projects = project::all()->where('deleted_at', null);
        $warehouses = warehouse::all()->where('deleted_at', null);
        // dd($projects);
        // ddd($warehouses);
        $idxs = idxpurchaserequest::all();
        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');
        // dd($idx);
        $map = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];


        $returnValueRoman = '';
        while ($month > 0) {
            foreach ($map as $roman => $int) {
                if ($month >= $int) {
                    $month -= $int;
                    $returnValueRoman .= $roman;
                    break;
                }
            }
        }

        return view('purchase_requests.create', compact([
            'items',
            'warehouses',
            'projects',
            'year',
            'returnValueRoman',
            'idxs'
        ]));
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
            'pr_no' => 'required',
            'pr_type' => 'required',
            'project_id' => 'required',
            // 'warehouse_id' => 'required',
            'status' => 'required',
            "requester" => "required",
            "partof" => "required",
            'created_by' => 'required'
        ]);
        $purchaserequest = new PurchaseRequest;
        $purchaserequest->pr_no = $request->pr_no;
        $purchaserequest->pr_type = $request->pr_type;
        $purchaserequest->project_id = $request->project_id;
        $purchaserequest->warehouse_id = 0;
        $purchaserequest->requester = $request->requester;
        $purchaserequest->partof = $request->partof;
        $purchaserequest->status = $request->status;
        $purchaserequest->remark = $request->remark;
        $purchaserequest->created_by = $request->created_by;
        $purchaserequest->save();


        $idx_pr = IdxPurchaseRequest::find(1);
        $idx_pr->idx = $request->idx_next;
        $idx_pr->save();




        //for create
        $currentuser = Auth::user();

        $history = new HistoryPurchase;
        $history->action_start = 'New PR';
        $history->action_end = 'New PR';
        $history->referensi = $request->pr_no;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();

        // $podata = [
        //     'pr_no' => $purchaserequest->pr_no,
        //     'pr_detail' => $purchaserequest->id,
        //     "created_by" => $currentuser->name
        // ];
        // Notification::send($currentuser, new PurchaseRequestCreated($podata));

        $purches = User::role([Role::PURCHASING, Role::IT])->get();

        foreach ($purches as $key => $pur) {
            $podata = [
                'pr_no' => $purchaserequest->pr_no,
                'pr_detail' => $purchaserequest->id,
                "created_by" => $currentuser->name
            ];
            Notification::send($pur, new PurchaseRequestCreated($podata));
        }




        // return redirect()->route('purchase_requests.index')
        // ->with('success','Purchase Request has been created successfully.');
        // return redirect()->route('cart.list')

        return redirect()->to("/itemprindex/" . $purchaserequest->id)
            ->with('success', 'Purchase Request Destination has been created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\purchaserequest  $purchaserequest
     * @return \Illuminate\Http\Response
     */
    public function show(purchaserequest $purchaserequest)
    {
        return view('purchase_requests.show', compact('purchaserequest'));
    }
    /**
     * Show the form for detail the specified resource.
     *
     * @param  \App\item  $item
     * @return \Illuminate\Http\Response
     */
    public function detaildata(purchaserequest $purchaserequest)
    {
        return view('purchase_requests.x', compact('purchaserequest'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PurchaseRequest  $PurchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseRequest $PurchaseRequest)
    {
        return view('purchase_requests.edit', compact('PurchaseRequest'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\purchaserequest  $purchaserequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'pr_no' => 'required',
            'pr_type' => 'required',
            'project_id' => 'required',
            'warehouse_id' => 'required',
            'status' => 'required',
            'remark' => 'required',
            'updated_by' => 'required'


        ]);
        $purchaserequest = purchaserequest::find($id);
        $purchaserequest->pr_no = $request->pr_no;
        $purchaserequest->pr_type = $request->pr_type;
        $purchaserequest->project_id = $request->project_id;
        $purchaserequest->warehouse_id = $request->warehouse_id;
        $purchaserequest->status = $request->status;
        $purchaserequest->remark = $request->remark;
        $purchaserequest->updated_by = $request->updated_by;
        $purchaserequest->save();
        $history = new HistoryPurchase;
        $history->action_start = 'New PR';
        $history->action_end = 'Edit Destinasi PR';
        $history->referensi = $request->pr_no;
        $history->action_by = $currentuser->id;
        $history->created_by = $currentuser->id;
        $history->action_date = Carbon::now();
        $history->created_at = Carbon::now();
        $history->save();
        return redirect()->route('purchase_requests.index')
            ->with('success', 'Purchase Request Has Been updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\purchaserequest  $purchaserequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(purchaserequest $purchaserequest, $id)
    {

        // $purchaserequest->delete();
        // return redirect()->route('purchase_requests.index')
        // ->with('success','Purchase Request has been deleted successfully');

        // dd($id);
        $userId = \Auth::id();
        $purchaserequest->validate([
            'status' => 'required',
        ]);
        $purchaserequest = PurchaseRequest::find($id);
        $purchaserequest->status = "Cancel";
        $purchaserequest->updated_by = $userId;
        $purchaserequest->save();
        return redirect()->route('purchase_requests.index')
            ->with('success', 'Purchase Request Has Been Canceled Successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PurchaseRequest  $purchaserequest
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, $id)
    {
        $userId = \Auth::id();
        $request->validate([
            'status' => 'required',
        ]);
        $purchaserequest = PurchaseRequest::find($id);
        $purchaserequest->status = "Cancel";
        $purchaserequest->updated_by = $userId;
        $purchaserequest->save();
        return redirect()->route('purchase_requests.index')
            ->with('success', 'Purchase Request Has Been Canceled Successfully');
    }
}
