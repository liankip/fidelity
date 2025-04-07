<?php

namespace App\Http\Controllers;

use App\Exports\PricesExport;
use App\Imports\pricesImport;
use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\Price;
use App\Models\Supplier;
use App\Models\SupplierItemPrice;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PriceCRUDController extends Controller
{

    public function index(Request $request)
    {
        $userId = auth()->user()->id;

        if ($request->search) {
            $searchcompact = $request->search;
            $prices = SupplierItemPrice::WhereHas('item', function ($query) use ($searchcompact) {
                $query->where("name", "like", "%" . $searchcompact . "%");
            })
                ->orWhereHas("supplier", function ($query) use ($searchcompact) {
                    $query->where("name", "like", "%" . $searchcompact . "%")->orWhere("term_of_payment", "like", "%" . $searchcompact . "%");
                })
                ->orWhere("price", "like", "%" . $searchcompact . "%")
                ->paginate(10);
            $prices->appends(['search' => $searchcompact]);
        } else {
            $searchcompact = "";
            $prices = SupplierItemPrice::with("item", "supplier")->orderBy('id', 'desc')->paginate(10);
        }

        return view('masterdata.prices.index', compact(['prices', 'searchcompact']));
    }

    public function create(Request $request)
    {
        if ($request->item) {
            $item = Item::where("id", $request->item)->first();
        } else {
            $item = "";
        }
        $items = Item::all();
        $suppliers = Supplier::where("is_approved", true)->get();

        return view('masterdata.prices.create', compact(['items', 'suppliers', "item"]));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => ['required', 'numeric', 'exists:suppliers,id'],
            'item_id'     => ['required', 'numeric', 'exists:items,id'],
            'unit_id'     => ['required', 'numeric', 'exists:units,id'],
            'price'       => ['required', 'numeric', 'min:0'],
            'choise'      => ['required', 'numeric', 'min:1', 'max:3'],
        ],[], [
            'supplier_id' => 'Supplier',
            'item_id'     => 'Item',
            'unit_id'     => 'Unit',
            'price'       => 'Price',
            'choise'      => 'Choise',
        ]);

        if ($request->choise == 1) {
            $tax = 11;
            $taxstatus = 1;
        } elseif ($request->choise == 2) {
            $tax = 11;
            $taxstatus = 0;
        } else {
            $taxstatus = 2;
            $tax = 0;
        }

        $supplier_item_prices = new SupplierItemPrice;
        $supplier_item_prices->supplier_id = $request->supplier_id;
        $supplier_item_prices->item_id = $request->item_id;
        $supplier_item_prices->unit_id = $request->unit_id;
        $supplier_item_prices->price = $request->price;
        $supplier_item_prices->tax = $tax;
        $supplier_item_prices->tax_status = $taxstatus;
        if ($request->kurs) {
            $supplier_item_prices->depend_usd = 1;
            $supplier_item_prices->old_idr_by_usd = $request->kurs;
        }
        $supplier_item_prices->created_by = auth()->user()->id;
        $supplier_item_prices->save();


        return redirect()->route('prices.index')->with('success', 'Price has been created successfully');
    }

    public function show(SupplierItemPrice $price)
    {
        return view('masterdata.prices.show', compact('price'));
    }

    public function edit(SupplierItemPrice $price)
    {
        $priceold = SupplierItemPrice::where("id", $price->id)->first();

        if($priceold->tax_status == 1) {
            $price_old = number_format($priceold->price +  ((11/100) * $priceold->price), 0, ',', '.');
        }else{
            $price_old = number_format($priceold->price, 0, ',', '.');
        }

        return view('masterdata.prices.edit', compact(['price', 'priceold', 'price_old']));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'price'       => ['required', 'numeric', 'min:0'],
            'choise'      => ['required', 'numeric', 'min:1', 'max:3'],
        ],[], [
            'price'       => 'Price',
            'choise'      => 'Choise',
        ]);

        if ($request->choise == 1) {
            $tax = 11;
            $taxstatus = 1;
        } elseif ($request->choise == 2) {
            $tax = 11;
            $taxstatus = 0;
        } else {
            $taxstatus = 2;
            $tax = 0;
        }

        if(SupplierItemPrice::where('id', $id)->count() == 0) {
            return redirect()->route('prices.index')->with('danger', 'Price not found');
        }

        SupplierItemPrice::where('id', $id)
            ->update([
                'price' => $request->price,
                'tax' => $tax,
                'tax_status' => $taxstatus,
                'updated_by' => auth()->user()->id,
            ]);

        return redirect()->route('prices.index')->with('success', 'Price has been updated successfully');
    }

    public function destroy(SupplierItemPrice $price)
    {
        $price->delete();
        return redirect()->route('prices.index')
            ->with('success', 'Price has been deleted successfully');
    }

    public function export()
    {
        return Excel::download(new PricesExport, 'sne-masterdata-prices.xlsx');
    }

    public function import()
    {
        Excel::import(new PricesImport, request()->file('file'));

        return back();
    }

    public function Updatepricebydolar(Request $request)
    {
        $request->validate([
            'rupiah' => 'required',
        ]);

        $pricewithdolar = SupplierItemPrice::where("depend_usd", 1)->get();
        foreach ($pricewithdolar as $key => $val) {
            $result = ($val->price * (float)$request->rupiah) / $val->old_idr_by_usd;
            Price::where("id", $val->id)->update([
                "price" => round($result),
                "old_idr_by_usd" => $request->rupiah
            ]);
        }

        return redirect()->back()->with("success", "Berhasil mengupdate harga yang bekaitan dengan dolar");
    }

    public function get_units(Request $request)
    {
        $units = ItemUnit::with('unit')->where('item_id', $request->item_id)->get();
        return response()->json($units);
    }

    public function sync_price()
    {
        foreach(Price::all() as $price) {

            $item = Item::where('id', $price->item_id)->first();

            if(!$item) {
                continue;
            }

            $unit  = Unit::where('name', $item->unit)->first();

            if(!$unit) {
                continue;
            }

            $supplier_item_price = SupplierItemPrice::where('supplier_id', $price->supplier_id)
            ->where('item_id', $price->item_id)
            ->where('unit_id', $unit->id)
            ->count();

            if($supplier_item_price == 0) {

                SupplierItemPrice::create([
                    'supplier_id'    => $price->supplier_id,
                    'item_id'        => $price->item_id,
                    'unit_id'        => $unit->id,
                    'price'          => $price->price,
                    'tax'            => $price->tax,
                    'tax_status'     => $price->tax_status,
                    'depend_usd'     => $price->depend_usd,
                    'old_idr_by_usd' => $price->old_idr_by_usd,
                    'created_by'     => $price->created_by,
                    'updated_by'     => $price->updated_by
                ]);
            }
        }

        return redirect()->route('prices.index')->with('success', 'Prices and Units have been successfully synced');
    }
}
