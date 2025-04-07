<?php

namespace App\Http\Livewire;

use App\Models\PurchaseRequestDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ChooseItemRawMaterial extends Component
{
    public $qty, $notes;
    public $prId;
    public $selectedUnit;

    public function mount($id)
    {
        $data = $this->getData();
        $this->prId = $id;

        if(empty($data)){ 
            return redirect()->route('raw-material.index');
        }
    }

    public function render()
    {
        $data = $this->getData();
        return view('livewire.choose-item-raw-material', compact('data'));
    }

    public function getData()
    {
        $sessionData = Session::get('checkedItems');
        return $sessionData;
    }

    public function addItem($items)
    {
        DB::beginTransaction();

        try {
            foreach ($items as $index => $item) {
                $newPrDetail = PurchaseRequestDetail::create([
                    'pr_id' => $this->prId,
                    'item_id' => $item['id'],
                    'item_name' => $item['name'],
                    'type' => $item['type'],
                    'unit' => $this->selectedUnit[$index] ?? $item['unit'], 
                    'qty' => $this->qty[$index],
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'status' => 'baru',
                    'notes' => $this->notes[$index] ?? '',
                    'is_raw_materials' => 1
                ]);
                $newItem[] = $newPrDetail;
            }

            DB::commit();

            return redirect()->to('purchase-requests')->with('success', 'Purchase Request Created Successfully.');
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
