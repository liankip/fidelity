<?php

namespace App\Http\Livewire\K3\Ibpr\List;

use App\Models\Ibpr;
use App\Models\IbprList;
use Livewire\Component;

class CreateIbprList extends Component
{
    public $ibpr;
    public $cases = [];

    public $activity;
    public $related_rules;
    public $threat;
    public $situation;
    public $risk;
    public $condition;
    public $risk_l;
    public $risk_s;
    public $risk_rfn;
    public $risk_level;
    public $current_control;
    public $risk_l_left;
    public $risk_s_left;
    public $risk_rfn_left;
    public $risk_level_left;
    public $elimination;
    public $substitution;
    public $technical_control;
    public $warning_control;
    public $apd_usage;
    public $pic;
    public $status;
    public $target_achievement;
    public $min_effective;
    public $new_risk;
    public $monitoring;

    protected $rules = [
        'activity' => 'required',
        'related_rules' => 'required',
        'threat' => 'required',
        'situation' => 'required',
        'risk' => 'required',
        'condition' => 'required',
        'risk_l' => 'required',
        'risk_s' => 'required',
        'risk_rfn' => 'required',
        'risk_level' => 'required',
        'current_control' => 'required',
        'risk_l_left' => 'required',
        'risk_s_left' => 'required',
        'risk_rfn_left' => 'required',
        'risk_level_left' => 'required',
        'status' => 'required',
    ];

    public function mount(Ibpr $ibpr)
    {
        $this->ibpr = $ibpr;
    }

    public function addCase()
    {
        $this->cases[] = [
            'id' => uniqid(), // Generate a unique identifier for each case
        ];
    }

    public function removeCase($id)
    {
        $this->cases = array_filter($this->cases, function ($case) use ($id) {
            return $case['id'] !== $id;
        });
    }

    public function store()
    {
        $this->validate();
        $allCases = [];

        $allCases[] = [
            'threat' => $this->threat,
            'situation' => $this->situation,
            'risk' => $this->risk,
            'condition' => $this->condition,
            'risk_l' => $this->risk_l,
            'risk_s' => $this->risk_s,
            'risk_rfn' => $this->risk_rfn,
            'risk_level' => $this->risk_level,
            'current_control' => $this->current_control,
            'risk_l_left' => $this->risk_l_left,
            'risk_s_left' => $this->risk_s_left,
            'risk_rfn_left' => $this->risk_rfn_left,
            'risk_level_left' => $this->risk_level_left,
            'related_rules' => $this->related_rules,
            'elimination' => $this->elimination ? $this->elimination : '',
            'substitution' => $this->substitution ? $this->substitution : '',
            'technical_control' => $this->technical_control ? $this->technical_control : '',
            'warning_control' => $this->warning_control ? $this->warning_control : '',
            'apd_usage' => $this->apd_usage ? $this->apd_usage : '',
            'pic' => $this->pic ? $this->pic : '',
            'status' => $this->status ? $this->status : '',
            'target_achievement' => $this->target_achievement ? $this->target_achievement : '',
            'min_effective' => $this->min_effective ? $this->min_effective : '',
            'new_risk' => $this->new_risk ? $this->new_risk : '',
            'monitoring' => $this->monitoring ? $this->monitoring : '',
        ];
        foreach ($this->cases as $caseId => $case) {
            $allCases[] = [
                'threat' => $case['threat'],
                'situation' => $case['situation'],
                'risk' => $case['risk'],
                'condition' => $case['condition'],
                'risk_l' => $case['risk_l'],
                'risk_s' => $case['risk_s'],
                'risk_rfn' => $case['risk_rfn'],
                'risk_level' => $case['risk_level'],
                'current_control' => $case['current_control'],
                'risk_l_left' => $case['risk_l_left'],
                'risk_s_left' => $case['risk_s_left'],
                'risk_rfn_left' => $case['risk_rfn_left'],
                'risk_level_left' => $case['risk_level_left'],
                'related_rules' => $case['related_rules'],
                'elimination' => isset($case['elimination']) ? $case['elimination'] :'',
                'substitution' => isset($case['substitution']) ? $case['substitution'] :'',
                'technical_control' => isset($case['technical_control']) ? $case['technical_control'] : '',
                'warning_control' => isset($case['warning_control']) ? $case['warning_control'] : '',
                'apd_usage' => isset($case['apd_usage']) ? $case['apd_usage'] : '',
                'pic' => isset($case['pic']) ? $case['pic'] : '',
                'status' => isset($case['status']) ? $case['status'] : '',
                'target_achievement' => isset($case['target_achievement']) ? $case['target_achievement'] : '',
                'min_effective' => isset($case['min_effective']) ? $case['min_effective'] : '',
                'new_risk' => isset($case['new_risk']) ? $case['new_risk'] : '',
                'monitoring' => isset($case['monitoring']) ? $case['monitoring'] : '',
            ];
        }
        IbprList::create([
            'ibpr_id' => $this->ibpr->id,
            'activity' => $this->activity,
            'data' => json_encode($allCases),
        ]);

        return redirect('/k3/ibpr/' . $this->ibpr->id . '/list')->with('success', 'Berhasil menambahkan item');

    }

    public function render()
    {
        return view('livewire.k3.ibpr.list.create-ibpr-list');
    }
}
