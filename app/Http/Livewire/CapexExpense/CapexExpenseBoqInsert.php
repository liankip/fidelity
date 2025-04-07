<?php

namespace App\Http\Livewire\CapexExpense;

use App\Exports\BOQTableExport;
use App\Models\BOQSpreadsheet;
use App\Models\Item;
use App\Models\Project;
use App\Notifications\BOQSubmitted;
use App\Traits\NotificationManager;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class CapexExpenseBoqInsert extends Component
{
    use NotificationManager;
    public $project;

    public $project_id;

    public $items;

    public $listeners = ['submitForReview', 'export', 'save', 'autosave'];

    public $boqs = [];

    public $currentBOQ;

    public $reviewResult = [];

    public function mount($project_id)
    {
        $this->project_id = $project_id;
        $this->project = Project::findOrFail($project_id);

        $existingItemIds = $this->project->b_o_q_s()->pluck('item_id')->toArray();
        $this->items = Item::available()->whereNotIn('id', $existingItemIds)->select('id', 'name as label')->get();
        $this->items = Item::available()
            ->select('id', 'name', 'brand')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'label' => $item->name . ' (Brand: ' . ($item->brand ?? '-') . ')',
                    'name' => $item->name,
                    'brand' => $item->brand ?? '-',
                ];
            });

        $boqs = BOQSpreadsheet::where('project_id', $project_id)
            ->where('user_id', auth()->user()->id)
            ->where('status', 'Draft')
            ->where('save', 1)
            ->first();

        $this->currentBOQ = $boqs;

        if ($boqs) {
            $this->boqs = json_decode($boqs->data);
        }
    }

    public function save($data)
    {
        $this->saveToDatabase($data);

        if ($data !== '[]' && !empty(json_decode($data, true))) {
            $this->emit('showAlert', ['message' => 'Data berhasil disimpan', 'type' => 'success']);
        }
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
                'project_id' => $this->project_id,
                'user_id' => auth()->user()->id,
                'data' => json_encode($filtered),
                'status' => 'Draft',
                'is_task' => 0,
                'save' => !(count($filtered) == 0),
            ]);
        }
    }

    public function export($data)
    {
        $boqs = json_decode($data);

        $invalidCharacters = ['/', '\\', ':', '*', '?', '"', '\'', '|', '<', '>'];
        $projectName = str_replace($invalidCharacters, '-', $this->project->name);
        $fileName = 'BOQ - ' . $projectName . '.xlsx';

        return Excel::download(new BOQTableExport($boqs), $fileName, \Maatwebsite\Excel\Excel::XLSX);
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
                'project_id' => $this->project_id,
                'user_id' => auth()->user()->id,
                'data' => json_encode($filtered),
                'status' => 'Draft',
                'is_task' => 0,
            ]);
        }

        if (!auth()->user()->hasTopLevelAccess()) {
            $data = [
                'project_name' => 'Capex Expense ' . $this->project->project_name,
                'project_id' => $this->project_id,
                'boq_id' => $boqSpreadsheet->id,
                'editor' => auth()->user()->name,
            ];
            $this->sendNotificationToManager($data, BOQSubmitted::class);
        }

        return redirect()
            ->route('capex-expense.boq.list', ['project_id' => $this->project_id])
            ->with('success', 'BOQ submitted successfully and waiting for review');
    }

    public function render()
    {
        return view('livewire.capex-expense.capex-expense-boq-insert');
    }
}
