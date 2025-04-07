<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\JSAModel;

use Illuminate\Support\Facades\DB;

class JSAList extends Component
{
    public $paramId;
    public $jsaTitle;
    public $tes = [];
    public $jsaData;

    public function mount($id)
    {
        $this->paramId = $id;
        $this->jsaTitle = JSAModel::where('id', intval($this->paramId))->first()->no_jsa;

        $this->jsaData = JSAModel::where('id', intval($this->paramId))->first();

        if ($this->jsaData && isset($this->jsaData['details_data'])) {
            $this->tes = json_decode($this->jsaData['details_data'], true);
        } else {
            $this->tes[] = [
                'urutan' => '',
                'risiko' => [
                    ['risiko_item' => '', 'tindakan' => ['']]
                ],
            ];
        }

    }

    public function addTes()
    {
        $this->tes[] = [
            'urutan' => '',
            'risiko' => [
                ['risiko_item' => '', 'tindakan' => ['']]
            ],
        ];
    }

    public function addRisikoFieldTes($tesIndex)
    {
        $this->tes[$tesIndex]['risiko'][] = [
            'risiko_item' => '',
            'tindakan' => ['']
        ];
    }

    public function addTindakanFieldTes($tesIndex, $risikoIndex)
    {
        $this->tes[$tesIndex]['risiko'][$risikoIndex]['tindakan'][] = '';
    }

    public function removeRisikoFieldTes($tesIndex)
    {
        array_pop($this->tes[$tesIndex]['risiko']);
    }

    public function removeTindakanFieldTes($tesIndex, $risikoIndex)
    {
        array_pop($this->tes[$tesIndex]['risiko'][$risikoIndex]['tindakan']);
    }

    public function removeStepTes()
    {
        array_pop($this->tes);
    }

    public function render()
    {
        return view('livewire.j-s-a-list');
    }

    public function handlePost(){
        DB::beginTransaction();

        try {
            
            $detailsData = json_encode($this->tes);
            
            $updateData = JSAModel::where('id', intval($this->paramId))
            ->update(['details_data' => $detailsData]);
            
            DB::commit();
            return redirect()->route('jsa-view.index')->with('success','JSA Details has been added');
        } catch (\Exception $e) {
            DB::rollBack();    
            dd($e);
        }

    }
}
