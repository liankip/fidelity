<?php

namespace App\Http\Livewire;

use App\Models\Project;
use App\Models\PurchaseRequest;
use Livewire\Component;
use Livewire\WithPagination;

class NewMonitoring extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $projectDropdown;

    public function render()
    {
        $projectList = Project::all();
        $prList = $this->prList();

        return view('livewire.new-monitoring', [
            'projectList' => $projectList,
            'prList' => $prList,
        ]);
    }

    public function updatedProjectDropdown()
    {
        $this->resetPage();
    }

    public function prList()
    {
        if ($this->projectDropdown) {
            return PurchaseRequest::where('project_id', $this->projectDropdown)
                ->where('pr_no', '!=', '')
                ->paginate(15);
        }

        return collect();
    }
}
