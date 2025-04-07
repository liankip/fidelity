<?php

namespace App\Http\Livewire\CapexExpense;

use App\Models\CapexExpense;
use App\Models\Project;
use Livewire\Component;

class EditCapexExpense extends Component
{
    public $project_id;
    public $project_name;
    public $roi;
    public $custom_roi;
    public $total_budget;

    public function mount($id)
    {
        $this->project_id = $id;

        $project = Project::findOrFail($this->project_id);

        $this->project_name = $project->name;
        $this->total_budget = $project->value;
        $this->roi = $project->roi;

        if (preg_match('/^(\d+)\s*YEARS$/i', $this->roi, $matches)) {
            $this->custom_roi = $matches[1];
            $this->roi = '__YEARS';
        }
    }

    public function edit()
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
                'custom_roi' => 'Custom ROI is required.',
                'total_budget.required' => 'Total budget is required.',
            ],
        );

        $project = Project::find($this->project_id);
        $project->update([
            'project_name' => $this->project_name,
            'roi' => $this->roi === '__YEARS' ? $this->custom_roi . ' YEARS' : $this->roi,
            'total_budget' => $this->total_budget,
        ]);

        session()->flash('success', 'Capex Expense Updated Successfully.');

        return redirect()->route('capex-expense.index');
    }

    public function render()
    {
        return view('livewire.capex-expense.edit-capex-expense');
    }
}
