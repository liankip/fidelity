<?php

namespace App\Http\Livewire\Boq;

use App\Imports\BOQImport;
use App\Models\BOQ;
use App\Models\BOQEdit;
use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\Project;
use App\Models\Unit;
use App\Traits\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class UploadBOQ extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public $boqFile;
    public $boqList;
    public $projectId;
    public $isError = false;

    public function mount($projectId)
    {

        $this->projectId = $projectId;
        $project = Project::findOrFail($this->projectId);

        $this->authorizeBOQFromLivewire($project);

        if ($project->boq_verification == 1) {
            redirect()->route('boq.index', $project->id)->with('danger', 'BOQ still waiting for approval.');
        }
    }

    public function render()
    {
        return view('livewire.boq.upload-b-o-q');
    }

    public function upload()
    {
        $this->validate([
            'boqFile' => 'required|mimes:xls,xlsx'
        ]);

        $file = Excel::toCollection(new BOQImport, $this->boqFile);
        $boqList = collect([]);
        $count = 1;
        foreach ($file[0] as $key => $value) {
            if ($key > 0) {

                $boq = [
                    'item_id' => $value[0],
                    'item_name' => $value[1],
                    'unit' => $value[2],
                    'qty' => $value[3],
                    'price_estimation' => $value[4],
                    'shipping_cost' => $value[5],
                    'origin' => $value[6],
                    'destination' => $value[7],
                    'note' => $value[8],
                ];
                $validate = Validator::make($boq, [
                    'item_id' => 'required|numeric|exists:items,id',
                    'item_name' => 'required',
                    'unit' => 'required|exists:units,name',
                    'qty' => 'required|numeric',
                    'price_estimation' => 'required|numeric',
                    'shipping_cost' => 'required|numeric',
                ]);

                $errors = $validate->errors()->messages();
                $unit = Unit::where('name', $boq['unit'])->first();
                if (!is_null($unit)) {
                    $itemUnit = ItemUnit::with('unit')->where('item_id', $boq['item_id'])->where('unit_id', $unit->id)->first();

                    if (is_null($itemUnit)) {
                        $errors['unit'] = ['Unit not match with item'];
                    }
                }

                $boq['error'] = [
                    'no' => $count,
                    'list' => $errors
                ];

                $boqList->push($boq);
                $count++;
            }
        }

        $this->boqList = $boqList;
        $this->isError = $boqList->pluck('error.list')->flatten()->count() > 0;
    }

    public function submitBOQ()
    {
        $project = Project::find($this->projectId);
        $max_revision = $project->maxEditRevision();
        $success = 0;

        foreach ($this->boqList as $boq) {
            $unit = Unit::where('name', $boq['unit'])->first();
            $itemAvailable = Item::available()->where('id', $boq['item_id'])->first();

            $isError = count($boq['error']['list']) > 0;

            if ($max_revision == 0 || $max_revision == null) {
                $itemExist = $project->boqs_not_approved()->where('item_id', $boq['item_id'])->where('deleted_at', null)->first();

                if ($itemExist || $isError || is_null($unit) || is_null($itemAvailable)) {
                    continue;
                }

                BOQ::create([
                    'no_boq' => $project->id,
                    'project_id' => $project->id,
                    'item_id' => $boq['item_id'],
                    'unit_id' => $unit->id,
                    'qty' => $boq['qty'],
                    'price_estimation' => $boq['price_estimation'],
                    'shipping_cost' => $boq['shipping_cost'],
                    'origin' => $boq['origin'],
                    'destination' => $boq['destination'],
                    'note' => $boq['note'],
                    'revision' => 0,
                    'created_by' => auth()->user()->id,
                ]);
            } else {
                $itemExist = $project->boqs_edit_not_approved()->where('item_id', $boq['item_id'])->where('deleted_at', null)->where('revision', $max_revision)->first();

                if ($itemExist || $isError || is_null($unit) || is_null($itemAvailable)) {
                    continue;
                }

                BOQEdit::create([
                    'no_boq' => $project->id,
                    'project_id' => $project->id,
                    'item_id' => $boq['item_id'],
                    'unit_id' => $unit->id,
                    'qty' => $boq['qty'],
                    'price_estimation' => $boq['price_estimation'],
                    'shipping_cost' => $boq['shipping_cost'],
                    'origin' => $boq['origin'],
                    'destination' => $boq['destination'],
                    'note' => $boq['note'],
                    'revision' => $max_revision,
                    'created_by' => auth()->user()->id,
                ]);
            }
            $success++;
        }

        $message = $success . ' BOQ berhasil diupload dan ' . ($this->boqList->count() - $success) . ' BOQ gagal diupload';
        return redirect()->route('boq.index', $project->id)->with('success', $message);
    }
}
