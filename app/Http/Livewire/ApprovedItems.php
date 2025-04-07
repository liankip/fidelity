<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\BOQ;
use App\Models\Project;
use App\Models\Setting;


use Illuminate\Http\Request;


class ApprovedItems extends Component
{

    public $project, $boqs, $version = [], $show_version, $max_version, $check_approval = [];
    public $adendum;

    public $showModal = false;

    public $boqTable;

    public Setting $setting;
    public $select_all;
    public $sortBy, $filter;
    public $needToApprove = false;
    public $boqsArray = [];

    public $loading = false;

    public function mount(Project $project)
    {
        $this->setting = Setting::first();
        $this->project = $project;
        $this->boqs = $project->boqs_list();
        $this->boqsArray = collect($this->boqs->toArray());
    }

    public function render()
    {
        if ($this->project->status_boq) {
            $this->adendum = true;
        }

        $boqList = $this->boqs;

        if ($this->project->boq_verification === 1 && $this->sortBy === null) {
            $boqList = $boqList->sortByDesc(function ($item) {
                $hasNull = is_null($item['approved_by']) || is_null($item['approved_by_2']);
                return [$hasNull, $item['approved_by'], $item['approved_by_2']];
            });
        } else if ($this->sortBy == 'created_at') {
            $boqList = $this->boqs->sortByDesc(function ($boq, $key) {
                return $boq->created_at;
            });
        } else {
            $boqList = $this->boqs->sortBy(function ($boq, $key) {
                if ($boq->item) {
                    return $boq->item->name;
                }
                return $boq->id;
            });
        }

        $boqList = $this->boqs->filter(function ($boq, $key) {
            if ($this->setting->multiple_approval)
                return $boq->approved_by != null && $boq->approved_by_2 != null;
            else
                return $boq->approved_by != null;
        });
                
        return view('livewire.approved-items', [
            'boqList' => $boqList,
        ]);
    }
}
