<?php

namespace App\Http\Livewire\CapexExpense;

use App\Models\BOQ;
use App\Models\BOQEdit;
use App\Models\BOQSpreadsheet;
use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Unit;
use Livewire\Component;

class CapexExpenseBoqDetail extends Component
{
    public $project_id;
    public $project;
    public $boq_id;
    public $boqSpreadsheet;
    public $boqsData = [];
    public $countBoqList = 0;

    public function mount($project_id, $id)
    {
        $this->project_id = $project_id;
        $this->boq_id = $id;

        $this->project = Project::findOrFail($project_id);
        $this->boqSpreadsheet = BOQSpreadsheet::findOrFail($this->boq_id);
        $this->review = $this->boqSpreadsheet->review;
        $this->setting = Setting::first();

        if ($this->boqSpreadsheet->review) {
            $this->boqsData = $this->boqSpreadsheet->review->getJsonDataAsObjectArray();
        } else {
            $this->boqsData = $this->boqSpreadsheet->getJsonDataAsObjectArray();
        }
    }

    public function submitBOQ()
    {
        $this->boqSpreadsheet->update([
            'status' => 'Finalized',
        ]);

        if (!$this->project->boq_verification) {
            $this->project->update([
                'status_boq' => 1,
            ]);
        }

        $count = 0;
        $max_revision = $this->project->maxEditRevision();

        foreach (json_decode($this->boqSpreadsheet->data) as $boq) {
            $unit = Unit::where('name', $boq[1])->first();

            $itemUnit = ItemUnit::with('unit')
                ->where('item_id', $boq[0])
                ->where('unit_id', $unit->id)
                ->first();

            $itemAvailable = Item::available()
                ->where('id', $boq[0])
                ->first();

            try {
                if ($max_revision == 0 || $max_revision == null) {
                    $itemExist = $this->project
                        ->boqs_not_approved()
                        ->where('item_id', $boq[0])
                        ->where('deleted_at', null)
                        ->first();

                    if (is_null($unit) || is_null($itemAvailable) || is_null($itemUnit)) {
                        continue;
                    }

                    if ($itemExist) {
                        BOQ::where('item_id', $boq[0])
                            ->where('project_id', $this->project_id)
                            ->where('deleted_at', null)
                            ->update([
                                'unit_id' => $unit->id,
                                'qty' => $boq[3] + $itemExist->qty,
                                'price_estimation' => $boq[2],
                                'shipping_cost' => $boq[4],
                                'note' => $boq[5],
                                'rejected_by' => null,
                                'approved_by' => null,
                                'date_approved' => null,
                                'approved_by_2' => null,
                                'date_approved_2' => null,
                                'approved_by_3' => null,
                                'date_approved_3' => null,
                                'comment' => $this->boqSpreadsheet->comment,
                            ]);
                    } else {
                        BOQ::create([
                            'no_boq' => $this->project_id,
                            'project_id' => $this->project_id,
                            'item_id' => $boq[0],
                            'unit_id' => $unit->id,
                            'qty' => $boq[3],
                            'price_estimation' => $boq[2],
                            'shipping_cost' => $boq[4],
                            'note' => $boq[5],
                            'revision' => 0,
                            'created_by' => auth()->user()->id,
                            'comment' => $this->boqSpreadsheet->comment,
                        ]);
                    }
                } else {
                    $itemExist = $this->project
                        ->boqs_edit_not_approved()
                        ->where('item_id', $boq[0])
                        ->where('deleted_at', null)
                        ->where('revision', $max_revision)
                        ->first();

                    if (is_null($unit) || is_null($itemAvailable)) {
                        continue;
                    }

                    if ($itemExist) {
                        BOQEdit::where('item_id', $boq[0])
                            ->where('project_id', $this->project_id)
                            ->where('deleted_at', null)
                            ->where('revision', $max_revision)
                            ->update([
                                'unit_id' => $unit->id,
                                'qty' => $boq[3] + $itemExist->qty,
                                'price_estimation' => $boq[2],
                                'shipping_cost' => $boq[4],
                                'note' => $boq[5],
                                'rejected_by' => null,
                                'approved_by' => null,
                                'date_approved' => null,
                                'approved_by_2' => null,
                                'date_approved_2' => null,
                                'comment' => $this->boqSpreadsheet->comment,
                            ]);
                    } else {
                        BOQEdit::create([
                            'no_boq' => $this->project_id,
                            'project_id' => $this->project_id,
                            'item_id' => $boq[0],
                            'unit_id' => $unit->id,
                            'qty' => $boq[3],
                            'price_estimation' => $boq[2],
                            'shipping_cost' => $boq[4],
                            'note' => $boq[5],
                            'revision' => $max_revision,
                            'created_by' => auth()->user()->id,
                            'comment' => $this->boqSpreadsheet->comment,
                        ]);
                    }
                }

                $count++;
            } catch (\Exception $e) {
                continue;
            }
        }

        $this->project->boq_verification = 1;
        $this->project->status_boq = 0;
        $this->project->save();

        return redirect()
            ->route('capex-expense.boq', $this->project_id)
            ->with('success', 'BOQ with ' . $count . ' items are submitted successfully');
    }

    public function render()
    {
        return view('livewire.capex-expense.capex-expense-boq-detail');
    }
}
