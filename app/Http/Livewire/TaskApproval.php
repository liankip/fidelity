<?php

namespace App\Http\Livewire;

use App\Models\Setting;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TaskApproval extends Component
{
    public $tasks;
    public $task = [];
    public $setting;

    protected $rules = [
        'task.*' => 'required|string|max:150',
    ];

    protected $messages = [
        'task.*.required' => 'Nama task tidak boleh kosong',
        'task.*.string' => 'Nama task harus berupa teks dan kalimat',
        'task.*.max' => 'Nama task tidak boleh lebih dari 150 karakter',
    ];

    public function mount()
    {
        $this->setting = Setting::first();

        $this->tasks = Task::where('status', 'Pending')->where('is_chart_submitted', 'true')->get();

        foreach ($this->tasks as $singleTask) {
            $this->task[$singleTask->id] = $singleTask->task;
        }
    }

    public function updateTaskName($taskId)
    {
        $this->validate();

        $task = Task::find($taskId);

        if ($task && isset($this->task[$taskId])) {
            $task->task = $this->task[$taskId];
            $task->save();
        }

        $this->tasks = Task::where('status', 'Pending')->get();
    }

    public function render()
    {
        return view('livewire.task-approval', [
            'groupedTasks' => $this->tasks->groupBy('project.id'),
        ]);
    }

    public function approve($id)
    {
        $tasks = Task::where('project_id', $id)->get();

        foreach ($tasks as $task) {
            $task->status = 'Approved';
            $task->approved_by_user_1 = auth()->user()->id;
            $task->approved_date_user_1 = Carbon::now();
            $task->approved_by_user_2 = auth()->user()->id;
            $task->approved_date_user_2 = Carbon::now();
            $task->save();
        }

        return redirect()->route('task-approval.index')->with('success', 'Tasks have been approved');
    }

    public function revertFunction($id)
    {
        DB::beginTransaction();
        try {
            $tasks = Task::where('project_id', $id)->get();

            foreach ($tasks as $task) {
                $task->status = 'Rejected';
                $task->save();
            }

            DB::commit();

            return redirect()->route('task-approval.index')->with('success', 'Tasks have been reverted');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('task-approval.index')->with('error', 'Failed to revert tasks');
        }
    }
}
