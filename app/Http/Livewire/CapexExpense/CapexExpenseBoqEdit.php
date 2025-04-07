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

class CapexExpenseBoqEdit extends Component
{
    use NotificationManager;

    public Project $project;
    public $project_id;
    public $boq_id;
    public $boq;
    public $items;
    public $listeners = ['submitForReview', 'export', 'save', 'autosave'];
    public $boqs = [];
    public $currentBOQ;
    public $saving = false;
    public $reviewResult = [];

    public function mount($project_id, $id)
    {
        $this->project_id = $project_id;
        $this->boq_id = $id;

        $this->project = \App\Models\Project::find($project_id);

        $existingItemIds = $this->project->b_o_q_s()->pluck('item_id')->toArray();
        $this->items = Item::available()->whereNotIn('id', $existingItemIds)->select('id', 'name as label')->get();

        $this->items = Item::available()->select('id', 'name as label')->get();

        $boqs = BOQSpreadsheet::find($id);

        $this->currentBOQ = $boqs;

        if ($boqs) {
            $this->boqs = json_decode($boqs->data);
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

        if ($boqSpreadsheet) {
            $boqSpreadsheet->data = json_encode($filtered);
            $boqSpreadsheet->save();
        }
    }

    public function submitForReview($data)
    {
        $boqSpreadsheet = BOQSpreadsheet::find($this->currentBOQ?->id);
        $filtered = collect(json_decode($data))->unique('0')->toArray();

        if ($boqSpreadsheet) {
            $boqSpreadsheet->data = json_encode($filtered);
            $boqSpreadsheet->status = 'Submitted';
            $boqSpreadsheet->is_task = 0;
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
                'is_task' => 0,
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

        return redirect()
            ->route('capex-expense.boq.list', ['project_id' => $this->project->id])
            ->with('success', 'BOQ submitted successfully and waiting for review');
    }

    public function resetTable()
    {
        $this->dispatchBrowserEvent('reset-table');
    }

    public function render()
    {
        return view('livewire.capex-expense.capex-expense-boq-edit');
    }
}
