<?php

namespace App\Http\Livewire\K3\List;

use App\Models\Hiradc;
use App\Models\HiradcList;
use Livewire\Component;

class EditList extends Component
{
    public $hiradc;
    public $hiradclist;

    public $sub_name;
    public $activity;
    public $cases = [];

    public function mount(Hiradc $hiradc, HiradcList $list)
    {
        $this->hiradc = $hiradc;

        $this->hiradclist = $list;
        $this->sub_name = $list->sub_name;
        $this->activity = $list->activity;
        $this->cases = json_decode($list->data, true);
    }

    public function addCase()
    {
        $newCase = [
            'id' => uniqid(), // Generate a unique identifier for each case
            "threat" => "",
            "situation" => "",
            "aspect" => "",
            "impact" => "",
            "risk_k" => "",
            "risk_p" => "",
            "risk_tnr" => "",
            "current_control" => "",
            "risk_k_after" => "",
            "risk_p_after" => "",
            "risk_tnr_after" => "",
            "related_rules" => "",
            "risk_level" => "",
            "further_control" => "",
        ];
    
        $this->cases[] = $newCase;
        // No need to dump here, as Livewire will handle the reactivity
    }

    public function removeCase($key)
    {
        if (isset($this->cases[$key])) {
            unset($this->cases[$key]);
            $this->cases = array_values($this->cases); // Re-index the array
        }

    }

    public function update()
    {
        $this->hiradclist->update([
            'sub_name' => $this->sub_name,
            'activity' => $this->activity,
            'data' => json_encode($this->cases),
        ]);

        return redirect('/k3/hiradc/' . $this->hiradc->id . '/list')->with('success', 'Berhasil mengedit item');
    }
    

    public function render()
    {
        return view('livewire.k3.list.edit-list');
    }
}
