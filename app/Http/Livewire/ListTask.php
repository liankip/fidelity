<?php

namespace App\Http\Livewire;

use App\Exports\TaskExport;
use App\Imports\TaskImport;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskApproval;
use App\Models\TaskFilePath;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ListTask extends Component
{
    use WithFileUploads;

    public $file_upload;

    public $tasks;
    public $allApproved = false;
    public Project $project;
    public $projectName;
    public $projectCode;

    public $earliestStart = [];
    public $earliestFinish = [];
    public $duration = [];
    public $startDate = [];
    public $finishDate = [];

    public $uploadedFiles = [];
    public $uploadedFileName = null;

    public $type;
    public $requester;
    public $note;
    public $comment;

    protected $listeners = [
        'refresh' => '$refresh'
    ];

    protected $rules = [
        'file_upload' => 'required|file|mimes:xlsx,xls',
    ];

    protected $messages = [
        'file_upload.required' => 'File upload tidak boleh kosong',
        'file_upload.file' => 'File upload harus berupa file',
        'file_upload.mimes' => 'File upload harus berupa file excel',
    ];

    public function mount(Project $project): void
    {
        $specificDate = Carbon::parse('2025-01-25');

        $tasksQuery = Task::where('project_id', $project->id)
            ->with(['purchaseRequest' => function ($query) {
                $query->whereNotNull('pr_no');
            }]);

            if ($project->created_at >= $specificDate) {
                $tasksQuery = $tasksQuery->orderBy('parent', 'asc')->orderBy('sortorder', 'asc');
            }

        $tasks = $tasksQuery->get()->each(function ($task) {
            $task->isSpecialTask = Str::endsWith($task->task_number, '/00');
            $task->purchaseRequestExists = $task->purchaseRequest()->exists();
        });

        foreach ($tasks as $task) {
            $this->earliestStart[$task->id] = $task->earliest_start;
            $this->earliestFinish[$task->id] = $task->earliest_finish;
            $this->duration[$task->id] = $task->duration;
            $this->startDate[$task->id] = $task->start_date;
            $this->finishDate[$task->id] = $task->finish_date;
        }

        $this->tasks = $tasks;

        $this->projectName = $project->name;
        $this->projectCode = $project->project_code;

        $this->loadUploadedFiles();
    }

    public function calculateDuration($taskId)
    {
        $start = $this->earliestStart[$taskId];
        $finish = $this->earliestFinish[$taskId];

        $duration = $finish - $start;

        $this->duration[$taskId] = $duration;

        if ($duration < 0) {
            $this->addError('duration.' . $taskId, 'Durasi tidak boleh negatif.');
            $this->duration[$taskId] = 0;
        } else {
            $this->resetErrorBag('duration.' . $taskId);
            $this->duration[$taskId] = $duration;
        }
    }

    public function checkApprovalStatus()
    {
        $this->allApproved = $this->tasks->every(fn($t) => $t->approved_by_user_1 && $t->approved_by_user_2);
    }

    public function loadUploadedFiles(): void
    {
        $this->uploadedFiles = TaskFilePath::where('project_id', $this->project->id)->get();
    }

    public function download(): BinaryFileResponse
    {
        return Excel::download(new TaskExport(), 'tasks' . Carbon::now()->format('_d_m_Y') . '.xlsx');
    }

    public function import()
    {
        try {
            $this->validate();

            $filePath = $this->file_upload->store('tasks', 'public');

            if (!Storage::disk('public')->exists($filePath)) {
                session()->flash('error', 'File tidak ditemukan.');
                return;
            }

            $fullPath = Storage::disk('public')->path($filePath);

            Excel::import(new TaskImport($this->project->id), $fullPath);

            $this->uploadedFileName = $filePath;
            $this->file_upload = null;

            Task::where('project_id', $this->project->id)
                ->where('status', 'Rejected')
                ->delete();

            TaskFilePath::create([
                'project_id' => $this->project->id,
                'file_path' => $filePath,
            ]);

            return redirect()->route('task.chart', $this->project->id);
        } catch (\Exception $e) {
            return redirect()->route('project.task', $this->project->id)
                ->with('error', 'File Upload Gagal: ' . $e->getMessage());
        }
    }

    public function taskRevision()
    {
        try {
            Task::where('project_id', $this->project->id)
                ->update([
                    'status' => 'Revision',
                    'revision' => true,
                    'comment' => $this->comment,
                    'revision_by_user_1' => null,
                    'revision_date_user_1' => null,
                    'revision_by_user_2' => null,
                    'revision_date_user_2' => null
                ]);

            $this->comment = '';

            return redirect()->route('project.task', $this->project->id)->with('success', 'Task Revision Success');
        } catch (\Exception $e) {
            return redirect()->route('project.task', $this->project->id)->with('error', 'Task Revision Failed');
        }
    }

    public function taskRevisonConfirm()
    {
        try {
            $this->validate(
                [
                    'startDate.*' => 'required|date',
                    'finishDate.*' => 'required|date',
                ],
                [
                    'startDate.*.required' => 'Tanggal mulai tidak boleh kosong',
                    'startDate.*.date' => 'Tanggal mulai harus berupa tanggal',
                    'finishDate.*.required' => 'Tanggal selesai tidak boleh kosong',
                    'finishDate.*.date' => 'Tanggal selesai harus berupa tanggal',
                ]
            );

            $tasks = Task::where('project_id', $this->project->id)->get();

            foreach ($tasks as $task) {
                if (isset($this->startDate[$task->id]) && isset($this->finishDate[$task->id])) {
                    $duration = $this->earliestFinish[$task->id] - $this->earliestStart[$task->id];

                    $task->update([
                        'earliest_start' => $this->earliestFinish[$task->id],
                        'earliest_start' => $this->earliestFinish[$task->id],
                        'duration' => $duration,
                        'start_date' => Carbon::parse($this->startDate[$task->id])->format('Y-m-d'),
                        'finish_date' => Carbon::parse($this->finishDate[$task->id])->format('Y-m-d'),
                        'status' => 'Approved',
                        'revision' => false,
                    ]);
                }
            }

            $this->startDate = [];
            $this->finishDate = [];

            return redirect()->route('project.task', $this->project->id)->with('success', 'Task dates updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('project.task', $this->project->id)->with('error', 'Task dates update failed!');
        }
    }

    public function render()
    {
        return view('livewire.list-task');
    }
}
