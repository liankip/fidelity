<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Mcu as ModelMCU;

class MCU extends Component
{
    public $search, $data_mcu, $delete_id;
    public function render()
    {
        $this->data_mcu = ModelMCU::whereHas('user', function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })->get();
        return view('livewire.m-c-u');
    }

    public function setDelete($id)
    {
        $data = ModelMCU::find($id);
        $this->delete_id = $data->id;
    }

    public function handleDelete($paramId)
    {
        DB::beginTransaction();

        try {
            ModelMCU::where('id', intval($paramId))->delete();

            DB::commit();

            return redirect()->route('k3.mcu')->with('success', 'MCU has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
