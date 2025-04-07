<?php

namespace App\Http\Controllers;

use App\Models\LinkModel;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GanttControlller extends Controller
{

    public function get($id)
    {
        $project = Project::find($id);
        $specificDate = Carbon::parse('2025-01-25'); 

        if ($project && $project->created_at < $specificDate) {
            $data = Task::where('project_id', $id)->get();
        } else {
            $data = Task::where('project_id', $id)->orderby('sortorder')->get();
        }

        $ganttData = [];

        foreach ($data as $task) {
            if($task->task == 'Consumables' || $task->task == 'Indent'){
                continue;
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
                'projectId' => $id,
                'open' => true,
                'project_weight' => $task->bobot,
                'cost_weight' => $task->bobot_cost,
                'consumables' => $task->is_consumables
            ];
        }

        $links = LinkModel::where('project_id', $id)->get();

        return response()->json([
            'data' => $ganttData,
            'links' => $links]);
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $checkExistTask = Task::where('project_id', $request->projectParam)->first();

            $projectCode = Project::find($request->projectParam)->project_code;
            $lastTaskNumber = Task::where('project_id', $request->projectParam)
                ->orderBy('id', 'desc')
                ->value('task_number');

            $lastIndex = $lastTaskNumber 
                ? (int)substr($lastTaskNumber, strrpos($lastTaskNumber, '/') + 1) 
                : 0;

            $newIndex = str_pad($lastIndex + 1, 2, '0', STR_PAD_LEFT);

            $section = null;

            if($request->parent !== null && $request->parent !== '0'){
                $parentTaskNumber = Task::find($request->parent)->task_number;
                $segments = explode('/', $parentTaskNumber);
                $middleSegment = str_pad((int)$segments[1], 2, '0', STR_PAD_LEFT);

                $section = $middleSegment;
            }

            if ($request->type === 'project') {
                $lastSection = Task::where('project_id', $request->projectParam)
                    ->orderBy('id', 'desc')
                    ->value('task_number');

                $segments = $lastSection ? explode('/', $lastSection) : null;
                $lastSection = $segments ? (int)$segments[1] + 1 : 1;
                
                $section = $lastSection;
                $newIndex = str_pad($lastIndex, 2, '0', STR_PAD_LEFT);
            }

            $formattedSection = str_pad($section, 2, '0', STR_PAD_LEFT);

            $taskNumber = "{$projectCode}/{$formattedSection}/{$newIndex}";

            if($checkExistTask == null){ 
                $consummableNumber = "{$projectCode}/00/00";
                $indentNumber = "{$projectCode}/00/00-2";
                
                $consummableData = [
                    'project_id' => $request->projectParam,
                    'task_number' => $consummableNumber,
                    'section' => '',
                    'task' => 'Consumables',
                    'bobot' => 0,
                    'bobot_cost' => 0,
                    'earliest_start' => 0,
                    'earliest_finish' => 0,
                    'text' => 'Consumables',
                    'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                    'finish_date' => Carbon::parse($request->end_date)->format('Y-m-d'),
                    'duration' => $request->duration,
                    'progress' => 0,
                    'parent' => null,
                    'type' => 'project'
                ];

                $indentData = [
                    'project_id' => $request->projectParam,
                    'task_number' => $indentNumber,
                    'section' => '',
                    'task' => 'Indent',
                    'bobot' => 0,
                    'bobot_cost' => 0,
                    'earliest_start' => 0,
                    'earliest_finish' => 0,
                    'text' => 'Indent',
                    'start_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
                    'finish_date' => Carbon::parse($request->end_date)->format('Y-m-d'),
                    'duration' => $request->duration,
                    'progress' => 0,
                    'parent' => null,
                    'type' => 'project'
                ];

                Task::insert([$consummableData, $indentData]);
            }

            $parentSection = null;
            $isConsumables = $request->consumables;

            if($request->type === 'project'){
                $parentSection = $request->text;
                $isConsumables = 0;
            }else{
                $parentSection = $request->parent == 0 ? null : $request->parent;
            }
            
            $task = new Task();

            $task->project_id = $request->projectParam;
            $task->task_number = $taskNumber;
            $task->task = $request->text;
            $task->bobot = $request->project_weight;
            $task->bobot_cost = $request->cost_weight;
            $task->earliest_start = 0;
            $task->earliest_finish = 0;

            $task->text = $request->text;
            $task->start_date = Carbon::parse($request->start_date)->format('Y-m-d');
            $task->finish_date = Carbon::parse($request->end_date)->format('Y-m-d');
            $task->duration = $request->duration;
            $task->progress = $request->has("progress") ? $request->progress : 0;
            $task->parent = $request->parent == 0 ? null : $request->parent;
            $task->section = $parentSection;
            $task->type = $request->type;
            $task->is_consumables = $isConsumables == 'true' ? 1 : 0;
            $task->sortorder = Task::max("sortorder") + 1;

            $task->save();

            DB::commit();
            return response()->json([
                "action" => "inserted",
                "tid" => $task->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $task = Task::find($id);

            $task->task = $request->text;
            $task->text = $request->text;
            $task->start_date = Carbon::parse($request->start_date)->format('Y-m-d');
            $task->duration = $request->duration;
            $task->progress = $request->has("progress") ? $request->progress : 0;
            $task->parent = $request->parent;

            $task->bobot = $request->project_weight !== '0' ? $request->project_weight : $task->bobot;
            $task->bobot_cost = $request->cost_weight !== '0' ? $request->cost_weight : $task->bobot_cost;

            if ($request->type === 'project') {
                LinkModel::where('source', $task->id)->orWhere('target', $task->id)->delete();
            }

            $task->type = $request->type ?? $task->type;

            $isConsumables = $request->consumables == 'true' ? 1 : 0;
            $task->is_consumables = $request->type == 'project' ? 0 : $isConsumables;

            if($request->has("target")){
                $this->updateOrder($id, $request->target);
            }

            $task->save();

            DB::commit();

            return response()->json([
                "action" => "updated"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();

        return response()->json([
            "action" => "deleted"
        ]);
    }

    private function updateOrder($taskId, $target){
        $nextTask = false;
        $targetId = $target;
     
        if(strpos($target, "next:") === 0){
            $targetId = substr($target, strlen("next:"));
            $nextTask = true;
        }
     
        if($targetId == "null")
            return;
     
        $targetOrder = Task::find($targetId)->sortorder;
        if($nextTask)
            $targetOrder++;
     
        Task::where("sortorder", ">=", $targetOrder)->increment("sortorder");
     
        $updatedTask = Task::find($taskId);
        $updatedTask->sortorder = $targetOrder;
        $updatedTask->save();
    }
}
