<?php

namespace App\Http\Livewire\ProjectGroup;

use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectGroup;

class ItemList extends Component
{
    public $groupId;
    public $search;

    public function mount($groupId)
    {
        $this->groupId = $groupId;
    }

    public function render()
    {
        $search = $this->search;
        $projects = Project::where('project_group_id', $this->groupId)
            ->with(['boqs' => function ($query) use ($search) {
                $query->whereHas('project', function ($projectQuery) use ($search) {
                    $projectQuery->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('item', function ($itemQuery) use ($search) {
                    $itemQuery->where('name', 'like', '%' . $search . '%');
                });
            }])
            ->get();

        $groupedItem = [];

        foreach ($projects as $project) {
            foreach ($project->boqs as $boq) {
                $itemName = optional($boq->item)->name;

                if (!isset($groupedItem[$itemName][$project->name])) {
                    $groupedItem[$itemName][$project->name] = [
                        'quantity' => 0,
                        'unit' => '',
                    ];
                }

                $groupedItem[$itemName][$project->name]['quantity'] += $boq->qty;

                $groupedItem[$itemName][$project->name]['unit'] = $boq->unit->name;
            }
        }

        return view('livewire.project-group.item-list', [
            'groupedItem' => $groupedItem
        ]);
    }


}
