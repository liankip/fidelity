<?php

namespace App\Http\Livewire;

use App\Constants\EmailNotificationTypes;
use App\Mail\BOQ\BOQReviewApproved;
use App\Models\BOQ;
use App\Models\BOQEdit;
use App\Models\BOQSpreadsheet;
use App\Models\BOQSpreadsheetReview;
use App\Models\Item;
use App\Models\ItemUnit;
use App\Models\NotificationEmailType;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Task;
use App\Models\TaskEngineerDrawing;
use App\Models\Unit;
use App\Models\User;
use App\Notifications\BoqApproved;
use App\Notifications\BOQProjectSubmitted;
use App\Roles\Role;
use App\Traits\NotificationManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class BoqProject extends Component
{
    use NotificationManager;

    public $project;
    public $boqs;
    public $review;
    public $spreadsheet;
    public $boqsData = [];
    public $item_name, $unit_name, $price, $qty, $shipping_cost;
    public $selectedItem;
    public Setting $setting;
    public $comment;

    public function mount($projectId, $boqId)
    {
        $this->project = Project::findOrFail($projectId);
        $this->boqs = BOQSpreadsheet::findOrFail($boqId);
        $this->review = $this->boqs->review;
        $this->setting = Setting::first();

        if ($this->boqs->review) {
            $this->boqsData = $this->boqs->review->getJsonDataAsObjectArray();
        } else {
            $this->boqsData = $this->boqs->getJsonDataAsObjectArray();
        }
    }

    public function render()
    {
        return view('livewire.boq-project');
    }

    public function edit($index)
    {
        $data = $this->boqsData[$index];
        $this->selectedItem = $index;

        $this->item_name = $data['item_name'];
        $this->unit_name = $data['unit'];
        $this->price = $data['price'];
        $this->qty = $data['quantity'];
        $this->shipping_cost = $data['shipping_cost'];
    }

    public function review()
    {
        $review = BOQSpreadsheetReview::where('b_o_q_spreadsheet_id', $this->boqs->id)->first();

        if (is_null($review)) {
            BOQSpreadsheetReview::create([
                'b_o_q_spreadsheet_id' => $this->boqs->id,
                'reviewed_by' => auth()->user()->id,
                'data' => json_encode($this->boqsData),
            ]);
        }

        return redirect()->route('boq.project.detail', ['projectId' => $this->project->id, 'boqId' => $this->boqs->id]);
    }

    public function delete($index)
    {
        unset($this->boqsData[$index]);

        $review = BOQSpreadsheetReview::find($this->review->id);

        if ($review) {
            $review->update([
                'data' => $this->boqsData,
            ]);
        }

        return redirect()
            ->route('boq.project.detail', [$this->project->id, $this->boqs->id])
            ->with('success', 'BOQ deleted successfully');
    }

    public function update()
    {
        $this->boqsData[$this->selectedItem] = [
            'item_id' => $this->boqsData[$this->selectedItem]['item_id'],
            'item_name' => $this->item_name,
            'unit' => $this->unit_name,
            'price' => $this->price,
            'quantity' => $this->qty,
            'shipping_cost' => $this->shipping_cost,
            'notes' => $this->boqsData[$this->selectedItem]['notes'],
        ];

        $review = BOQSpreadsheetReview::find($this->review->id);

        if ($review) {
            $review->update([
                'data' => json_encode($this->boqsData),
            ]);
        }

        return redirect()
            ->route('boq.project.detail', [$this->project->id, $this->boqs->id])
            ->with('success', 'BOQ Project updated successfully');
    }

    public function submitReview()
    {
        $this->boqs->update([
            'status' => 'Reviewed',
        ]);

        $data = [
            'project_name' => $this->project->name,
            'project_id' => $this->project->id,
            'boq_id' => $this->boqs->id,
            'reviewer' => auth()->user()->name,
            'editor' => auth()->user()->name,
        ];

        $this->sendNotification($data, $this->boqs->user, BOQProjectSubmitted::class);

        return redirect()
            ->route('boq.project.index', ['projectId' => $this->project->id, 'taskId' => $this->boqs->task_id])
            ->with('success', 'BOQ reviewed successfully');
    }

    public function approve()
    {
        if ($this->setting->multiple_k3_approval) {
            if (auth()->user()->hasK3LevelAccess()) {
                if ($this->boqs->approved_by) {
                    $this->boqs->update([
                        'approved_by_2' => Auth::user()->id,
                        'date_approved_2' => date('Y-m-d'),
                        'status' => 'Approved',
                        'task_number' => $this->boqs->task_number,
                        'comment' => $this->comment,
                    ]);
                } else {
                    $this->boqs->update([
                        'approved_by_2' => Auth::user()->id,
                        'date_approved_2' => date('Y-m-d'),
                        'task_number' => $this->boqs->task_number,
                        'comment' => $this->comment,
                    ]);
                }
            } else {
                if ($this->boqs->approved_by_2) {
                    $this->boqs->update([
                        'approved_by' => Auth::user()->id,
                        'date_approved' => date('Y-m-d'),
                        'status' => 'Approved',
                        'task_number' => $this->boqs->task_number,
                    ]);
                } else {
                    $this->boqs->update([
                        'approved_by' => Auth::user()->id,
                        'date_approved' => date('Y-m-d'),
                        'task_number' => $this->boqs->task_number,
                    ]);
                }
            }
        } else {
            if ($this->boqs->approved_by == null) {
                $this->boqs->update([
                    'approved_by' => Auth::user()->id,
                    'date_approved' => date('Y-m-d'),
                    'status' => 'Approved',
                    'task_number' => $this->boqs->task_number,
                ]);
            }
        }

        $boqs = json_decode($this->boqs->data);

        $data = [
            'project_name' => $this->project->name,
            'approver' => auth()->user()->name,
            'boqs' => $boqs,
            'task_number' => $this->boqs->task_number,
        ];

        $types = NotificationEmailType::where('name', EmailNotificationTypes::BOQ_REVIEW_APPROVED)->first();

        if ($types) {
            foreach ($types->emails as $receiver) {
                Mail::to($receiver->email)->send(new BOQReviewApproved($data));
            }
        }

        $dataNotif = [
            'project_name' => $this->project->name,
            'location' => $this->project->id . '/review/' . $this->boqs->id,
        ];

        $receivers = [$this->boqs->user, User::role([Role::TOP_MANAGER, Role::IT])->get()];
        $this->sendNotification($dataNotif, $receivers, BoqApproved::class);

        return redirect()
            ->route('boq.project.detail', [$this->project->id, $this->boqs->id])
            ->with('success', 'BOQ approved successfully');
    }

    public function reject()
    {
        $this->validate(
            [
                'comment' => 'required',
            ],
            [
                'comment.required' => 'The comment field is required',
            ],
        );

        $review = BOQSpreadsheetReview::where('b_o_q_spreadsheet_id', $this->boqs->id)->first();

        if (is_null($review)) {
            BOQSpreadsheetReview::create([
                'b_o_q_spreadsheet_id' => $this->boqs->id,
                'reviewed_by' => auth()->user()->id,
                'data' => json_encode($this->boqsData),
            ]);
        }

        $this->boqs->update([
            'rejected_by' => Auth::user()->id,
            'date_rejected' => date('Y-m-d'),
            'status' => 'Reviewed',
            'comment' => $this->comment,
        ]);

        return redirect()
            ->route('boq.project.index', ['projectId' => $this->project->id, 'taskId' => $this->boqs->task_id])
            ->with('success', 'BOQ Rejected successfully');
    }

    public function submitBOQ()
    {
        $this->boqs->update([
            'status' => 'Finalized',
        ]);

        if (!$this->project->boq_verification) {
            $this->project->update([
                'status_boq' => 1,
            ]);
        }

        $boqs = json_decode($this->boqs->data);
        $count = 0;
        $taskSection = Task::find($this->boqs->task_id)->section;
        $isTaskConsumbales = str_contains($this->boqs->task_number, '/00/00') || $taskSection == 'Consumables' || Task::find($this->boqs->task_id)->is_consumables == 1;

        if ($isTaskConsumbales) {
            $sectionCount = BOQ::where('task_number', $this->boqs->task_number)->max('section');

            $checkSection = BOQ::where('task_number', $this->boqs->task_number)->count();

            if ($checkSection > 0) {
                $sectionCount = $sectionCount === null ? 1 : $sectionCount + 1;
            } else {
                $sectionCount = 0;
            }
        } else {
            $sectionCount = TaskEngineerDrawing::where('task_id', $this->boqs->task_id)->orderBy('section', 'desc')->first()->section;
        }

        $max_revision = $this->project->maxEditRevision();

        $currentSection = $sectionCount;

        foreach ($boqs as $boq) {
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
                        ->where('task_number', $this->boqs->task_number)
                        ->first();

                    if (is_null($unit) || is_null($itemAvailable) || is_null($itemUnit)) {
                        continue;
                    }

                    if ($itemExist) {
                        if ($isTaskConsumbales) {
                            BOQ::where('item_id', $boq[0])
                                ->where('project_id', $this->project->id)
                                ->where('deleted_at', null)
                                ->where('task_number', $this->boqs->task_number)
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
                                    'task_number' => $this->boqs->task_number,
                                    'comment' => $this->boqs->comment,
                                    'section' => $itemExist->section,
                                ]);
                        } else {
                            BOQ::create([
                                'no_boq' => $this->project->id,
                                'project_id' => $this->project->id,
                                'item_id' => $boq[0],
                                'unit_id' => $unit->id,
                                'qty' => $boq[3],
                                'price_estimation' => $boq[2],
                                'shipping_cost' => $boq[4],
                                'note' => $boq[5],
                                'revision' => 0,
                                'created_by' => auth()->user()->id,
                                'task_number' => $this->boqs->task_number,
                                'comment' => $this->boqs->comment,
                                'section' => $currentSection,
                            ]);
                        }
                    } else {
                        $currentSection = $isTaskConsumbales ? 0 : $currentSection;

                        BOQ::create([
                            'no_boq' => $this->project->id,
                            'project_id' => $this->project->id,
                            'item_id' => $boq[0],
                            'unit_id' => $unit->id,
                            'qty' => $boq[3],
                            'price_estimation' => $boq[2],
                            'shipping_cost' => $boq[4],
                            'note' => $boq[5],
                            'revision' => 0,
                            'created_by' => auth()->user()->id,
                            'task_number' => $this->boqs->task_number,
                            'comment' => $this->boqs->comment,
                            'section' => $currentSection,
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
                            ->where('project_id', $this->project->id)
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
                                'task_numbers' => $this->boqs->task_numbers,
                                'comment' => $this->boqs->comment,
                                'section' => $itemExist->section,
                            ]);
                    } else {
                        if ($isTaskConsumbales) {
                            $currentSection = 0;
                        } else {
                            $currentSection = $currentSection;
                        }

                        BOQEdit::create([
                            'no_boq' => $this->project->id,
                            'project_id' => $this->project->id,
                            'item_id' => $boq[0],
                            'unit_id' => $unit->id,
                            'qty' => $boq[3],
                            'price_estimation' => $boq[2],
                            'shipping_cost' => $boq[4],
                            'note' => $boq[5],
                            'revision' => $max_revision,
                            'created_by' => auth()->user()->id,
                            'task_numbers' => $this->boqs->task_numbers,
                            'comment' => $this->boqs->comment,
                            'section' => $currentSection,
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

        TaskEngineerDrawing::where('task_id', $this->boqs->task_id)->update([
            'status_uploaded' => 1,
        ]);

        return redirect()
            ->route('task-monitoring.index', $this->boqs->task_id)
            ->with('success', 'BOQ with ' . $count . ' items are submitted successfully');
    }
}
