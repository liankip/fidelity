<?php

namespace App\Http\Livewire;

use App\Models\Setting;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class TaskRevisionApproval extends Component
{
    public $tasks;
    public $checkAll = false;
    public $revision = [];
    public $setting;

    public function mount()
    {
        $this->setting = Setting::first();

        $this->tasks = Task::where('status', 'Revision')->where('revision', 1)->get();
    }

    public function approveMultipleWbsRevision($id)
    {
        try {
            $tasks = Task::where('project_id', $id)->get();

            foreach ($tasks as $task) {
                if (is_null($task->revision_by_user_1) && is_null($task->revision_date_user_1)) {
                    $task->update([
                        'revision_date_user_1' => Carbon::now(),
                        'revision_by_user_1' => auth()->user()->id,
                    ]);
                } else {
                    $task->update([
                        'revision_by_user_2' => auth()->user()->id,
                        'revision_date_user_2' => Carbon::now(),
                        'status' => 'Revision Approved',
                    ]);
                }
            }

            return redirect()
                ->route('task-revision-approval.index')
                ->with('success', new HtmlString('Anda Telah Me-Approve Task pada project ' . '<a href="' . route('project.task', $this->tasks->first()->project_id) . '">' . $this->tasks->first()->project->name . '</a>' . ' untuk direvisi'));
        } catch (\Exception $e) {
            return redirect()->route('task-revision-approval.index')->with('danger', 'Gagal Me-Approve Task pada project untuk direvisi');
        }
    }

    public function approveWbsRevision($id)
    {
        $tasks = Task::where('project_id', $id)->get();

        foreach ($tasks as $task) {
            $task->status = 'Approved';
            $task->revision_by_user_1 = auth()->user()->id;
            $task->revision_date_user_1 = Carbon::now();
            $task->revision_by_user_2 = auth()->user()->id;
            $task->revision_date_user_2 = Carbon::now();
            $task->status = 'Revision Approved';
            $task->save();
        }

        return redirect()
            ->route('task-revision-approval.index')
            ->with('success', new HtmlString('Anda Telah Me-Approve Task pada project ' . '<a href="' . route('project.task', $this->tasks->first()->project_id) . '">' . $this->tasks->first()->project->name . '</a>' . ' untuk direvisi'));
    }

    public function render()
    {
        return view('livewire.task-revision-approval', [
            'groupedTasks' => $this->tasks->groupBy('project_id'),
        ]);
    }
}
