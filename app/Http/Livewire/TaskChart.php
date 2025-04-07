<?php

namespace App\Http\Livewire;

use App\Exports\CriticalTaskExport;
use App\Models\LinkModel;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class TaskChart extends Component
{

    public $projectIdParam;
    public $publicCritical;
    public $isTaskApproved;
    public $isLinkExist;
    public $readOnly = false;

    public $updatedData;
    public $updatedLinks;

    public $isTaskRevision;
    public $isTaskSubmitted;
    public $projectName;

    protected $listeners = ['refreshGanttData' => 'reloadData'];

    public function reloadData()
    {
        $formattedData = $this->getImportData();

        $this->updatedData = $formattedData;

        $this->updatedLinks = LinkModel::where('project_id', $this->projectIdParam)->get();

        $this->dispatchBrowserEvent('ganttDataRefreshed', [
            'data' => $this->updatedData,   
            'links' => $this->updatedLinks 
        ]);
    }


    public function mount($project, Request $request)
    {
        $this->projectIdParam = $project;
        $this->projectName = Project::where('id', $this->projectIdParam)->first()->name;
        $this->readOnly = $request->query('readOnly', false);
        if ($this->readOnly) {
            $this->getCriticalData();
        }

        $data = Task::where('project_id', $this->projectIdParam)->get();
        if($data->isNotEmpty()){
            $this->isTaskApproved = $data->every(fn($t) => $t->status == 'Approved');
            $this->isTaskRevision = $data->every(fn($t) => $t->status == 'Revision');
            $this->isTaskSubmitted = $data->every(fn($t) => $t->is_chart_submitted == 'true' && $t->status == 'Pending');
            
            if($this->isTaskApproved || $this->isTaskSubmitted) {
                $this->getCriticalData();
            }
        }

    }
    public function render()
    {
        // $data = $this->getImportData();
        // $links = LinkModel::where('project_id', $this->projectIdParam)->get();
        return view('livewire.task-chart');
    }

    public function getImportData()
    {
        $data = Task::where('project_id', $this->projectIdParam)->get();
        if($data->isNotEmpty()){
            $this->isTaskApproved = $data->every(fn($t) => $t->status == 'Approved');
            $this->isTaskRevision = $data->every(fn($t) => $t->status == 'Revision');
        }

        $ganttData = [];
        $taskIds = [];
        $lastValidSection = null;

        foreach ($data as $task) {
            // $section = $task->section !== '' ? $task->section : $lastValidSection;
            if($task->task == 'Consumables'){
                continue;
            }

            // if (!in_array($section, $taskIds) && $task->id !== null) {
            //     $ganttData[] = [
            //         'id' => $section,
            //         'text' => $section,
            //         // "type" => "project",
            //         'start_date' => '',
            //         'duration' => '',
            //         'open' => true,
            //         'color' => '#00A65A',
            //         'taskId' => $task->id
            //     ];
            //     $taskIds[] = $section;
            // }

            if ($task->section !== '') {
                $lastValidSection = $task->section;
            }

            $startDate = Carbon::parse($task->start_date);

            $bgColor = '';

            if ($task->slack !== null) {
                $bgColor = $task->slack == 0.00 ? 'red' : '';
            }

            if($task->type == 'project'){
                $bgColor = '#00A65A';
            }

            $ganttData[] = [
                'id' => $task->id,
                'text' => $task->text ?: $task->task,
                'type' => $task->type,
                'start_date' => $startDate->format('d-m-Y'),
                'duration' => $task->duration,
                'parent' => $task->parent,
                'taskId' => $task->id,
                'color' => $bgColor,
                'projectId' => $this->projectIdParam,
                'open' => true,
                'project_weight' => $task->bobot,
                'cost_weight' => $task->bobot_cost
            ];
        }

        return $ganttData;
    }

    public function getCriticalData($isSubmit = false)
    {
            DB::beginTransaction();
            try {
                $taskDatas = Task::where('project_id', $this->projectIdParam)
                    ->orderBy('start_date', 'asc')
                    ->get()
                    ->keyBy('id');

                $linkData = LinkModel::where('project_id', $this->projectIdParam)->get();

                // Only consider tasks that appear in LinkModel, either as source or target
                $linkedTaskIds = $linkData->pluck('source')->merge($linkData->pluck('target'))->unique();
                $taskData = $taskDatas->whereIn('id', $linkedTaskIds);

                // Forward pass - calculate earliest start and earliest finish
                foreach ($taskData as $task) {
                    $predecessorLinks = $linkData->where('target', $task->id);

                    if ($predecessorLinks->isNotempty()) {
                        // Set earliest start based on predecessors' earliest finishes
                        $task->earliest_start = $predecessorLinks->map(function ($link) use ($taskData) {
                            $predecessorTask = $taskData[$link->source];
                            return $predecessorTask->earliest_finish;
                        })->max();
                    } else {
                        // No predecessors
                        $task->earliest_start = 0;
                    }

                    // Calculate earliest finish
                    $task->earliest_finish = $task->earliest_start + $task->duration;
                }


                // Backward pass - calculate latest start and latest finish
                foreach ($taskData->reverse() as $task) {
                    $successorLinks = $linkData->where('source', $task->id);

                    if ($successorLinks->isEmpty()) {
                        // No successors, set latest finish equal to earliest finish
                        $task->latest_finish = $taskData->max('earliest_finish');
                    } else {
                        // Set latest finish based on successors' latest starts
                        $task->latest_finish = $successorLinks->map(function ($link) use ($taskData) {
                            $successorTask = $taskData[$link->target];
                            return $successorTask->latest_start;
                        })->min();
                    }

                    // Calculate latest start
                    $task->latest_start = $task->latest_finish - $task->duration;

                    // Calculate slack time
                    $task->slack = $task->latest_finish - $task->earliest_finish;

                    $task->save();
                }

                if ($isSubmit != true) {
                    // $lastValidSection = '';
                    // foreach($taskData as $task) {
                    //     if (($task->section === '')) {
                    //         $task->section = $lastValidSection;
                    //     } else {
                    //         $lastValidSection = $task->section;
                    //     }
                    // }
                    $standaloneData = $taskDatas->whereNotIn('id', $linkedTaskIds)->where('type', '!=', 'project')->where('task', '!=', 'Consumables');
                    $combinedData = $taskData->merge($standaloneData);
                    
                    $this->publicCritical = $combinedData->sortBy('slack')->groupBy('section')->toArray();
                    // $this->reloadData();
                    $this->dispatchBrowserEvent('refreshCriticalData');
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e);
            }
        // }
    }

    public function saveChart()
    {
        DB::beginTransaction();
        try {
            $isLinkExist = $this->checkLinksExist();

            if ($isLinkExist == false) {
                return redirect()->back()->with('error', 'Please add a link before submitting chart');
            }

            $this->getCriticalData(true);
            Task::where('project_id', $this->projectIdParam)->update([
                    'is_chart_submitted' => true, 
                    'revision' => 0, 
                    "status" => "Pending",
                    "approved_by_user_1" => null,
                    "approved_date_user_1" => null,
                    "approved_by_user_2" => null,
                    "approved_date_user_2" => null,
                    "revision_by_user_1" => null,
                    "revision_date_user_1" => null,
                    "revision_by_user_2" => null,
                    "revision_date_user_2" => null
                ]
            );
    
            DB::commit();
            return redirect()->route('project.task', $this->projectIdParam)
            ->with('success', 'Chart Berhasil Disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function checkLinksExist()
    {
        $taskData = Task::where('project_id', $this->projectIdParam)
        ->orderBy('start_date', 'asc')
        ->get()
        ->keyBy('id');

        $linkData = LinkModel::where('project_id', $this->projectIdParam)->get();

        $linkedTaskIds = $linkData->pluck('source')->merge($linkData->pluck('target'))->unique();
        $taskData = $taskData->whereIn('id', $linkedTaskIds);

        return count($taskData) > 0;
    }

    public function criticalTaskExport()
    {
        $projectName = Task::where('project_id', $this->projectIdParam)->first()->project->name;
        return Excel::download(new CriticalTaskExport($this->projectIdParam), 'Critical Task - ' . $projectName . '.xlsx');
    }
}
