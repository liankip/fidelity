<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class ChooseItemList extends Component
{
    public $prId;
    public $selectedItems = [];
    public $prequest;

    public function mount($id)
    {
        $this->prId = $id;
        $this->prequest = PurchaseRequest::where("id", $id)->first();

    }

    public function render()
    {
        $itemList = Item::all();
        $prID = $this->prId;
        return view('livewire.choose-item-list', compact('itemList','prID'));
    }

    public function storeData(Request $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->input('dataBody');

            foreach ($data['datalist'] as $item) {
                $unit = Item::find($item['id'])->unit;

                $newItem = new PurchaseRequestDetail();

                $newItem->fill([
                    'pr_id' => $data['prID'],
                    'item_id' => $item['id'],
                    'type' => 'NA',
                    'item_name' => $item['name'],
                    'qty' => $item['qty'],
                    'notes' => null,
                    'unit' => $unit,
                    'created_by' => auth()->user()->id,
                ]);

                $newItem->save();
            }

            DB::commit();
            return response()->json(['message' => 'Data stored successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
