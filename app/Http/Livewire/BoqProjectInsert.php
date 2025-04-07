<?php

namespace App\Http\Livewire;

use App\Exports\BOQTableExport;
use App\Models\BOQSpreadsheet;
use App\Models\Item;
use App\Models\Project;
use App\Models\Task;
use App\Notifications\BOQSubmitted;
use App\Traits\NotificationManager;
use Illuminate\Http\Request;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class BoqProjectInsert extends Component
{
    use NotificationManager;

    public Project $project;
    public Task $task;
    public $items;
    public $listeners = ['submitForReview', 'export', 'save', 'autosave'];
    public $boqs = [];
    public $currentBOQ;
    public $saving = false;
    public $jobName;

    public $reviewResult = [];

    public function mount(Request $request, $projectId, $taskId)
    {
        $this->project = Project::findOrFail($projectId);
        $this->task = Task::find($taskId);
        $this->jobName = $this->task->task;

        $existingItemIds = $this->project->b_o_q_s()->pluck('item_id')->toArray();
        $this->items = Item::available()->whereNotIn('id', $existingItemIds)->select('id', 'name as label')->get();

        // $this->items = Item::available()->select('id', 'name as label')->get();
        $this->items = Item::available()
        ->select('id', 'name', 'brand')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'label' => $item->name . ' (Brand: ' . ($item->brand ?? '-') . ')', // Use '-' if brand is null
                'name' => $item->name,
                'brand' => $item->brand ?? '-', // Ensure brand is not null
            ];
        });

        if ($request->input('id')) {
            $boqs = BOQSpreadsheet::where('project_id', $this->project->id)->where('id', $request->input('id'))->where('user_id', auth()->user()->id)->first();

            if (is_null($boqs)) {
                abort(404);
            }

            if ($boqs->status !== 'Reviewed') {
                abort(404);
            }

            $review = $boqs->review;
            $currentBOQ = $boqs->getJsonDataAsObjectArray();
            $reviewBOQ = collect($review->getJsonDataAsObjectArray());

            $results = [];

            foreach ($currentBOQ as $item) {
                $reviewItem = $reviewBOQ->where('item_id', $item->item_id)->first();

                $results[] = [
                    'item_name' => [
                        'reviewed' => $reviewItem?->item_name,
                        'current' => $item->item_name
                    ],
                    'unit' => [
                        'reviewed' => $reviewItem?->unit,
                        'current' => $item->unit
                    ],
                    'price' => [
                        'reviewed' => $reviewItem?->price,
                        'current' => $item->price
                    ],
                    'quantity' => [
                        'reviewed' => $reviewItem?->quantity,
                        'current' => $item->quantity
                    ],
                    'shipping_cost' => [
                        'reviewed' => $reviewItem?->shipping_cost,
                        'current' => $item->shipping_cost
                    ],
                ];
            }

            $this->reviewResult = $results;
        } else {
            $boqs = BOQSpreadsheet::where('project_id', $this->project->id)->where('task_id', $this->task->id)->where('user_id', auth()->user()->id)->where('status', 'Draft')->where('save', 1)->first();
        }

        $this->currentBOQ = $boqs;

        if ($boqs) {
            $this->boqs = json_decode($boqs->data);
        }
    }

    public function render()
    {
        return view('livewire.boq-project-insert');
    }

    public function export($data)
    {
        $boqs = json_decode($data);

        $invalidCharacters = ['/', '\\', ':', '*', '?', '"', '\'', '|', '<', '>'];
        $projectName = str_replace($invalidCharacters, '-', $this->project->name);
        $fileName = 'BOQ - ' . $projectName . '.xlsx';
        return Excel::download(new BOQTableExport($boqs), $fileName, \Maatwebsite\Excel\Excel::XLSX,);
    }

    public function save($data)
    {
        $this->saveToDatabase($data);
        $this->emit('showAlert', ['message' => 'Data berhasil disimpan', 'type' => 'success']);
    }

    public function autosave($data)
    {
        $this->saving = true;
        $this->saveToDatabase($data);
        $this->saving = false;
    }

    private function saveToDatabase($data)
    {
        $boqSpreadsheet = BOQSpreadsheet::find($this->currentBOQ?->id);

        $filtered = collect(json_decode($data))->unique(0)->values()->toArray();

        if (count($filtered) == 0) {
            $this->emit('showAlert', ['message' => 'Data tidak boleh kosong', 'type' => 'danger']);
            return;
        }

        if ($boqSpreadsheet && $boqSpreadsheet->status === 'Draft') {
            $boqSpreadsheet->data = json_encode($filtered);
            $boqSpreadsheet->save();
        } else {
            $this->currentBOQ = BOQSpreadsheet::create([
                'project_id' => $this->project->id,
                'user_id' => auth()->user()->id,
                'data' => json_encode($filtered),
                'status' => 'Draft',
                'task_id' => $this->task->id,
                'task_number' => $this->task->task_number,
                'is_task' => 1,
                'save' => !(count($filtered) == 0),
            ]);
        }
    }

    public function submitForReview($data)
    {
        $boqSpreadsheet = BOQSpreadsheet::find($this->currentBOQ?->id);
        $filtered = collect(json_decode($data))->unique('0')->toArray();

        if ($boqSpreadsheet && $boqSpreadsheet->status === 'Draft') {
            $boqSpreadsheet->data = json_encode($filtered);
            $boqSpreadsheet->status = 'Submitted';
            $boqSpreadsheet->task_id = $this->task->id;
            $boqSpreadsheet->task_number = $this->task->task_number;
            $boqSpreadsheet->is_task = 1;
            $boqSpreadsheet->save();
        } else {
            if (!is_null($boqSpreadsheet)) {
                $boqSpreadsheet->is_closed = true;
                $boqSpreadsheet->save();
            }

            $boqSpreadsheet = BOQSpreadsheet::create([
                'project_id' => $this->project->id,
                'user_id' => auth()->user()->id,
                'data' => json_encode($filtered),
                'status' => 'Draft',
                'task_id' => $this->task->id,
                'task_number' => $this->task->task_number,
                'is_task' => 1,
            ]);
        }

        if (!auth()->user()->hasTopLevelAccess()) {
            $data = [
                'project_name' => $this->project->name,
                'project_id' => $this->project->id,
                'boq_id' => $boqSpreadsheet->id,
                'editor' => auth()->user()->name,
            ];
            $this->sendNotificationToManager($data, BOQSubmitted::class);
        }

        return redirect()->route('boq.project.index', ['projectId' => $this->project->id, 'taskId' => $this->task->id])->with('success', 'BOQ submitted successfully and waiting for review');
    }

    public function resetTable()
    {
        $this->dispatchBrowserEvent('reset-table');
    }
}
