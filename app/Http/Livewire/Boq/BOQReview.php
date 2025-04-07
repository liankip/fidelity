<?php

namespace App\Http\Livewire\Boq;

use Auth;
use Carbon\Carbon;
use App\Models\BOQ;
use App\Roles\Role;
use App\Models\Item;
use App\Models\Unit;
use App\Models\User;
use App\Models\BOQEdit;
use App\Models\Project;
use App\Models\Setting;
use Livewire\Component;
use App\Models\ItemUnit;
use App\Models\BOQSpreadsheet;
use App\Notifications\BoqApproved;
use App\Mail\BOQ\BOQReviewApproved;
use App\Traits\NotificationManager;
use App\Models\BOQSpreadsheetReview;
use Illuminate\Support\Facades\Mail;
use App\Models\NotificationEmailType;
use App\Constants\EmailNotificationTypes;
use App\Notifications\BOQReviewSubmitted;

class BOQReview extends Component
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
        return view('livewire.boq.boq-review');
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
            $review = BOQSpreadsheetReview::create([
                'b_o_q_spreadsheet_id' => $this->boqs->id,
                'reviewed_by' => auth()->user()->id,
                'data' => json_encode($this->boqsData)
            ]);
        }

        return redirect()->route('boq.review.detail', ['projectId' => $this->project->id, 'boqId' => $this->boqs->id]);
    }

    public function delete($index)
    {
        unset($this->boqsData[$index]);

        $review = BOQSpreadsheetReview::find($this->review->id);

        if ($review) {
            $review->update([
                'data' => $this->boqsData
            ]);
        }

        return redirect()->route('boq.review.detail', [$this->project->id, $this->boqs->id])->with('success', 'BOQ deleted successfully');
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
            'notes' => $this->boqsData[$this->selectedItem]['notes']
        ];

        $review = BOQSpreadsheetReview::find($this->review->id);

        if ($review) {
            $review->update([
                'data' => json_encode($this->boqsData)
            ]);
        }

        return redirect()->route('boq.review.detail', [$this->project->id, $this->boqs->id])->with('success', 'BOQ updated successfully');
    }

    public function submitReview()
    {
        $this->boqs->update([
            'status' => 'Reviewed'
        ]);

        $data = [
            'project_name' => $this->project->name,
            'project_id' => $this->project->id,
            'boq_id' => $this->boqs->id,
            'reviewer' => auth()->user()->name,
            'editor' => auth()->user()->name,
        ];

        $this->sendNotification($data, $this->boqs->user, BOQReviewSubmitted::class);

        return redirect()->route('boq.review.index', $this->project->id)->with('success', 'BOQ reviewed successfully');
    }

    public function approve()
    {
        if ($this->setting->multiple_k3_approval) {
            if (auth()->user()->hasK3LevelAccess()) {
                if ($this->boqs->approved_by) {
                    $this->boqs->update([
                        'approved_by_2' => Auth::user()->id,
                        'date_approved_2' => date('Y-m-d'),
                        'status' => 'Approved'
                    ]);
                } else {
                    $this->boqs->update([
                        'approved_by_2' => Auth::user()->id,
                        'date_approved_2' => date('Y-m-d')
                    ]);
                }
            } else {
                if ($this->boqs->approved_by_2) {
                    $this->boqs->update([
                        'approved_by' => Auth::user()->id,
                        'date_approved' => date('Y-m-d'),
                        'status' => 'Approved'
                    ]);
                } else {
                    $this->boqs->update([
                        'approved_by' => Auth::user()->id,
                        'date_approved' => date('Y-m-d')
                    ]);
                }
            }
        } else {
            if ($this->boqs->approved_by == null) {
                $this->boqs->update([
                    'approved_by' => Auth::user()->id,
                    'date_approved' => date('Y-m-d'),
                    'status' => 'Approved'
                ]);
            }
        }

        $boqs = json_decode($this->boqs->data);

        $data = [
            'project_name' => $this->project->name,
            'approver' => auth()->user()->name,
            'boqs' => $boqs,
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

        $receivers = [
            $this->boqs->user,
            User::role([Role::TOP_MANAGER, Role::IT])->get()
        ];
        $this->sendNotification($dataNotif, $receivers, BoqApproved::class);

        return redirect()->route('boq.review.detail', [$this->project->id, $this->boqs->id])->with('success', 'BOQ approved successfully');
    }

    public function reject()
    {

        $review = BOQSpreadsheetReview::where('b_o_q_spreadsheet_id', $this->boqs->id)->first();

        if (is_null($review)) {
            $review = BOQSpreadsheetReview::create([
                'b_o_q_spreadsheet_id' => $this->boqs->id,
                'reviewed_by' => auth()->user()->id,
                'data' => json_encode($this->boqsData)
            ]);
        }

        $this->boqs->update([
            'rejected_by' => Auth::user()->id,
            'date_rejected' => date('Y-m-d'),
            'status' => 'Reviewed'
        ]);
        return redirect()->route('boq.review.index', [$this->project->id, $this->boqs->id])->with('success', 'BOQ Rejected successfully');

    }

    public function submitBOQ()
    {
        $this->boqs->update([
            'status' => 'Finalized'
        ]);

        if (!$this->project->boq_verification) {
            $this->project->update([
                'status_boq' => 1
            ]);
        }

        $boqs = json_decode($this->boqs->data);
        $count = 0;

        $max_revision = $this->project->maxEditRevision();
        foreach ($boqs as $boq) {
            $unit = Unit::where('name', $boq[1])->first();
            $itemUnit = ItemUnit::with('unit')->where('item_id', $boq[0])->where('unit_id', $unit->id)->first();
            $itemAvailable = Item::available()->where('id', $boq[0])->first();

            try {
                if ($max_revision == 0 || $max_revision == null) {
                    $itemExist = $this->project->boqs_not_approved()->where('item_id', $boq[0])->where('deleted_at', null)->first();

                    if (is_null($unit) || is_null($itemAvailable) || is_null($itemUnit)) {
                        continue;
                    }

                    if ($itemExist) {
                        BOQ::where('item_id', $boq[0])->where('project_id', $this->project->id)->where('deleted_at', null)->update([
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
                        ]);
                    }

                } else {
                    $itemExist = $this->project->boqs_edit_not_approved()->where('item_id', $boq[0])->where('deleted_at', null)->where('revision', $max_revision)->first();

                    if (is_null($unit) || is_null($itemAvailable)) {
                        continue;
                    }

                    if ($itemExist) {
                        BOQEdit::where('item_id', $boq[0])->where('project_id', $this->project->id)->where('deleted_at', null)->where('revision', $max_revision)->update([
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
                        ]);
                    } else {
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

        return redirect()->route('boq.index', $this->project->id)->with('success', 'BOQ with ' . $count . ' items are submitted successfully');
    }
}
