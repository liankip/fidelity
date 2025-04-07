<?php

namespace App\Http\Livewire\CapexExpense;

use App\Models\CapexExpense;
use App\Models\Project;
use Livewire\Component;

class InsertCapexExpense extends Component
{
    public $project_name;
    public $roi;
    public $custom_roi;
    public $total_budget;

    public function updateRoi($value)
    {
        if ($value !== '__YEARS') {
            $this->custom_roi = '';
        }
    }

    public function insert()
    {
        $this->validate(
            [
                'project_name' => 'required',
                'roi' => 'required',
                'custom_roi' => 'required_if:roi,__YEARS',
                'total_budget' => 'required',
            ],
            [
                'project_name.required' => 'Project name is required.',
                'roi.required' => 'ROI is required.',
                'custom_roi.required_if' => 'Custom ROI is required.',
                'total_budget.required' => 'Total budget is required.',
            ],
        );

        Project::create([
            'name' => $this->project_name,
            'address' => '',
            'value' => $this->total_budget,
            'project_type' => 'capex',
            'roi' => $this->roi === '__YEARS' ? $this->custom_roi . ' YEARS' : $this->roi,
        ]);

        session()->flash('success', 'Capex expense added successfully.');

        return redirect()->route('capex-expense.index');
    }

    public function render()
    {
        return view('livewire.capex-expense.insert-capex-expense');
    }
}
