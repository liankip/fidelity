<?php

namespace App\Http\Livewire\Boq;

use App\Roles\Role;
use App\Models\Item;
use App\Models\User;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\BOQSpreadsheet;
use App\Exports\BOQTableExport;
use App\Notifications\BOQSubmitted;
use App\Traits\NotificationManager;
use Maatwebsite\Excel\Facades\Excel;

class TableInsert extends Component
{
    use NotificationManager;

    public Project $project;
    public $items;
    public $listeners = ['submitForReview', 'export', 'save', 'autosave'];
    public $boqs = [];
    public $currentBOQ;
    public $saving = false;

    public $reviewResult = [];

    public function mount(Request $request, $projectId)
    {
        $this->project = Project::findOrFail($projectId);
        $existingItemIds = $this->project->b_o_q_s()->pluck('item_id')->toArray();

        //The filter can only include items that are not yet in the BOQ
        // $this->items = Item::available()->whereNotIn('id', $existingItemIds)->select('id', 'name as label')->get();

        $this->items = Item::available()->select('id', 'name as label')->get();

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
            $boqs = BOQSpreadsheet::where('project_id', $this->project->id)->where('user_id', auth()->user()->id)->where('status', 'Draft')->first();
        }

        $this->currentBOQ = $boqs;

        if ($boqs) {
            $this->boqs = json_decode($boqs->data);
        }
    }

    public function render()
    {
        return view('livewire.boq.table-insert');
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

        if ($boqSpreadsheet && $boqSpreadsheet->status === 'Draft') {
            $boqSpreadsheet->data = json_encode($filtered);
            $boqSpreadsheet->save();
        } else {
            $this->currentBOQ = BOQSpreadsheet::create([
                'project_id' => $this->project->id,
                'user_id' => auth()->user()->id,
                'data' => json_encode($filtered),
                'status' => 'Draft',
                'wbs_type' => 0
            ]);
        }
    }

    public function submitForReview($data)
    {
        $boqSpreadsheet = BOQSpreadsheet::find($this->currentBOQ?->id);
        $filtered = collect(json_decode($data))->unique('0')->toArray();

        if (count($filtered) == 0) {
            $this->emit('showAlert', ['message' => 'Data tidak boleh kosong', 'type' => 'danger']);
            return;
        }

        if ($boqSpreadsheet && $boqSpreadsheet->status === 'Draft') {
            $boqSpreadsheet->data = json_encode($filtered);
            $boqSpreadsheet->status = 'Submitted';
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
                'status' => 'Submitted'
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

        return redirect()->route('boq.review.index', $this->project->id)->with('success', 'BOQ submitted successfully and waiting for review');
    }
    public function resetTable()
    {
        $this->dispatchBrowserEvent('reset-table');
    }
}
