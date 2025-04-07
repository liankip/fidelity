<?php

namespace App\Http\Livewire\K3\List;

use App\Models\Hiradc;
use App\Models\HiradcList;
use Livewire\Component;

class CreateList extends Component
{
    public $hiradc;
    public $cases = [];

    public $sub_name;
    public $activity;
    public $threat;
    public $situation;
    public $aspect;
    public $impact;
    public $risk_k;
    public $risk_p;
    public $risk_tnr;
    public $current_control;
    public $risk_k_after;
    public $risk_p_after;
    public $risk_tnr_after;
    public $related_rules;
    public $risk_level;
    public $further_control;

    protected $rules = [
        'sub_name' => 'nullable',
        'activity' => 'required',
        'threat' => 'required',
        'situation' => 'required',
        'aspect' => 'required',
        'impact' => 'required',
        'risk_k' => 'required',
        'risk_p' => 'required',
        'risk_tnr' => 'required',
        'current_control' => 'required',
        'risk_k_after' => 'required',
        'risk_p_after' => 'required',
        'risk_tnr_after' => 'required',
        'related_rules' => 'required',
        'risk_level' => 'required',
        'further_control' => 'nullable',
    ];


    public function mount(Hiradc $hiradc)
    {
        $this->hiradc = $hiradc;
    }

    public function store()
    {
        $this->validate();
        $allCases = [];

        $allCases[] = [
            'threat' => $this->threat,
            'situation' => $this->situation,
            'aspect' => $this->aspect,
            'impact' => $this->impact,
            'risk_k' => $this->risk_k,
            'risk_p' => $this->risk_p,
            'risk_tnr' => $this->risk_tnr,
            'current_control' => $this->current_control,
            'risk_k_after' => $this->risk_k_after,
            'risk_p_after' => $this->risk_p_after,
            'risk_tnr_after' => $this->risk_tnr_after,
            'related_rules' => $this->related_rules,
            'risk_level' => $this->risk_level,
            'further_control' => $this->further_control ? $this->further_control : '',
        ];
        foreach ($this->cases as $caseId => $case) {
            $allCases[] = [
                'threat' => $case['threat'],
                'situation' => $case['situation'],
                'aspect' => $case['aspect'],
                'impact' => $case['impact'],
                'risk_k' => $case['risk_k'],
                'risk_p' => $case['risk_p'],
                'risk_tnr' => $case['risk_tnr'],
                'current_control' => $case['current_control'],
                'risk_k_after' => $case['risk_k_after'],
                'risk_p_after' => $case['risk_p_after'],
                'risk_tnr_after' => $case['risk_tnr_after'],
                'related_rules' => $case['related_rules'],
                'risk_level' => $case['risk_level'],
                'further_control' => isset($case['further_control']) ? $case['further_control'] : '',
            ];
        }
        HiradcList::create([
            'hiradc_id' => $this->hiradc->id,
            'sub_name' => $this->sub_name,
            'activity' => $this->activity,
            'data' => json_encode($allCases),
        ]);

        return redirect('/k3/hiradc/' . $this->hiradc->id . '/list')->with('success', 'Berhasil menambahkan item');
    }

    public function addCase()
    {
        $this->cases[] = [
            'id' => uniqid(), // Generate a unique identifier for each case
            'sub_name' => '',
            'activity' => '',
            'threat' => '',
            'situation' => '',
            'aspect' => '',
            'impact' => '',
            'risk_k' => '',
            'risk_p' => '',
            'risk_tnr' => '',
        ];
    }

    public function removeCase($id)
    {
        $this->cases = array_filter($this->cases, function ($case) use ($id) {
            return $case['id'] !== $id;
        });
    }

    public function render()
    {
        return view('livewire.k3.list.create-list');
    }
}
