<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveAllCartRequest;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\Warehouse;
use App\Models\PurchaseRequestDetail;
use App\Models\IdxPurchaseRequest;
use Carbon\Carbon;
class CartController extends Controller
{
    /**
     * show cart list
     * @return view
     */
    public function cartList()
    {
        $userId = \Auth::id();
        $items = \Cart::session($userId)->getContent();

        $projects = project::all();
        $warehouses = warehouse::all();

        $purchaserequest = PurchaseRequest::all()->where('status','New');

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

        return view('carts.index', compact(
            'items',
            'warehouses',
            'projects',
            'year',
            'returnValueRoman',
            'idxs',
            'purchaserequest'
        ));
    }


    /**
     * @param Request $request
     *
     * @return view
     */
    public function addToCart(Request $request)
    {
        // dd($request->all());
        $userId = \Auth::id();

        \Cart::session($userId)->add([
            'id'         => $request->id,
            'name'       => $request->name,
            'price'      => 0,
            'quantity'   => $request->quantity,
            'attributes' => [
                'image'     => $request->image,
                'type'      => $request->type,
                'item_code' => $request->item_code,
                'unit' => $request->unit,
            ]
        ]);

        // session()->flash('success', 'Item is Added to PR Successfully !');

        return redirect()->route('cart.list')->with('success', 'Item is Added to PR Successfully !');
    }

    public function updateCart(Request $request)
    {
        $userId = \Auth::id();
        \Cart::session($userId)->update(
            $request->id,
            [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->quantity
                ],
            ]
        );

        session()->flash('success', 'Item PR is Updated Successfully !');

        return redirect()->route('cart.list');
    }

    public function removeCart(Request $request)
    {
        $userId = \Auth::id();
        \Cart::session($userId)->remove($request->id);
        session()->flash('success', 'Item PR Remove Successfully !');

        return redirect()->route('cart.list');
    }

    public function clearAllCart()
    {
        $userId = \Auth::id();
        \Cart::session($userId)->clear();

        session()->flash('success', 'All Item PR Clear Successfully !');

        return redirect()->route('cart.list');
    }

    /**
     * Save all cart in cart session
     *
     * @param SaveAllCartRequest $request
     * @return void
     */
    public function saveAllCart(SaveAllCartRequest $request)
    {
        // get cart item using cart session
        $userId = \Auth::id();
        $getCarts = \Cart::session($userId);
        // dd($items);

        if($getCarts->getTotalQuantity() <= 0){
            return session()->flash('danger', 'Pesanan anda kosong. silakankan melakakukan pesanan');
        }

        \DB::beginTransaction();
        try {
            $cartItems = $getCarts->getContent();

            $purchaseRequest = PurchaseRequest::findOrFail($request->pr_id);
            $filterCartItems = $cartItems->map(function($item){
                return[
                    'item_id'   => $item->id,
                    'item_name' => $item->name,
                    'qty'       => $item->quantity,
                    'type'      => $item->attributes->get('type'),
                    'unit'      => $item->unit ?? 'unit',
                    'status'    => 'new',
                    'notes'     => $item->notes ?? 'notes'
                ];
            });

            // dd($filterCartItems);
            $purchaseRequest->purchaseRequestDetails()->createMany($filterCartItems);
        } catch (\Throwable $error) {
            \DB::rollBack();
            return $error;
            // return session()->flash('error', $error);
        }

        \DB::commit();

        // hapus session cart karena sudah menyimpan data
        $getCarts->clear();
        // return 'sukses';
        return redirect()->route('purchase_requests.index')->with('success', 'Purchase Request has been created successfully.');
    }

    // Cart GT

    /**
     * show cart gudang transfer list
     * @return view
     */
    public function cartGtList()
    {

    }

    /**
     * @param Request $request
     *
     * @return view
     */
    public function addToGtCart(Request $request)
    {

    }
    public function updateGtCart(Request $request)
    {

    }
    public function removeGtCart(Request $request)
    {

    }
    public function clearAllGtCart()
    {

    }
    /**
     * Save all cart in cart session
     *
     * @param SaveAllGtCartRequest $request
     * @return void
     */
    public function saveAllGtCart(SaveAllGtCartRequest $request)
    {

    }


    // Cart Inventory Usage

    /**
     * show cart inventory usage list
     * @return view
     */
    public function cartUsageList()
    {

    }
    /**
     * @param Request $request
     *
     * @return view
     */
    public function addToUsageCart(Request $request)
    {

    }
    public function updateUsageCart(Request $request)
    {

    }
    public function removeUsageCart(Request $request)
    {

    }
    public function clearAllUsageCart()
    {

    }
        /**
     * Save all cart in cart session
     *
     * @param SaveAllUsageCartRequest $request
     * @return void
     */
    public function saveAllUsageCart(SaveAllUsageCartRequest $request)
    {

    }


}
