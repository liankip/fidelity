<?php

namespace App\Http\Livewire\CapexExpense;

use Livewire\Component;

class CapexExpense extends Component
{
    public function render()
    {
        return view('livewire.capex-expense.capex-expense', [
            'data' => \App\Models\Project::where('project_type', '!=', 'project')->paginate(10),
        ]);
    }
}
