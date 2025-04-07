<?php

namespace App\Http\Livewire\ProjectGroup;

use Livewire\Component;

use App\Models\BOQ;
use App\Models\Project;
use App\Models\ProjectGroup;
use App\Models\Setting;

class ApprovedItemsGroup extends Component
{
    public $groupId;

    public function mount($groupId)
    {
        $this->groupId = $groupId;
        $this->setting = Setting::first();
    }

    public function render()
{
    $projects = Project::where('project_group_id', $this->groupId)->get();
    $groupName = ProjectGroup::where('id', $this->groupId)->first()->name;

    $groupedItem = [];

    foreach ($projects as $project) {
        $boqs = $project->boqs_list();

        if ($project->status_boq) {
            $this->adendum = true;
        }

        $boqList = $boqs->filter(function ($boq) {
            return $this->setting->multiple_approval ?
                ($boq->approved_by != null && $boq->approved_by_2 != null) :
                ($boq->approved_by != null);
        });

        foreach ($boqList as $boq) {
            $itemName = optional($boq->item)->name;
            $projectName = $boq->project->name;
        
            if (!isset($groupedItem[$itemName])) {
                $groupedItem[$itemName] = [
                    'quantity' => 0,
                    'unit' => '',
                    'poList' => [],
                    'projectNames' => [],
                ];
            }
        
            $groupedItem[$itemName]['quantity'] += $boq->qty;
            $groupedItem[$itemName]['unit'] = $boq->unit->name;
            $groupedItem[$itemName]['projectNames'][] = $projectName; // Store project names
        
            $po_status = $boq->po_status ?? null;
        
            if ($po_status && auth()->user()->hasAnyRole('it|top-manager|manager|purchasing')) {
                foreach ($po_status['list'] as $po) {
                    $groupedItem[$itemName]['poList'][] = [
                        'po_id' => $po['po_id'],
                        'po_no' => $po['po_no'],
                    ];
                }
            }
        }
        
    }

    return view('livewire.project-group.approved-items', [
        'groupedItem' => $groupedItem,
        'groupName' => $groupName
    ]);
}

}
