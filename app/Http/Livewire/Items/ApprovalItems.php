<?php

namespace App\Http\Livewire\Items;

use App\Constants\EmailNotificationTypes;
use App\Models\Item;
use App\Models\NotificationEmail;
use App\Models\NotificationEmailType;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ApprovalItems extends Component
{
    public $items;
    public $items_checklist = [];
    public $select_all = false;
    public $setting;

    public function mount($items)
    {
        $this->items = $items;
        $allItems = Item::available()->get();

        foreach ($items as $item) {
            $similiar = $this->getSimiliarItem($item->name, $allItems);
            $item->similiar = $similiar;
        }

        $this->setting = Setting::first();
    }

    public function render()
    {
        return view('livewire.items.approval-items');
    }

    public function approve()
    {
        $checked = collect($this->items_checklist)->filter(function ($value, $key) {
            return $value === true;
        });

        $item_ids = $checked->keys();
        $items = Item::whereIn('id', $item_ids)->get();

        foreach ($items as $item) {
            if ((bool)$this->setting->multiple_item_approval) {
                if (is_null($item->approved_by)) {
                    $item->update([
                        'approved_by' => auth()->user()->id,
                    ]);
                } elseif (!is_null($item->approved_by) && is_null($item->approved_by_2)) {
                    if ($item->approved_by !== auth()->user()->id) {
                        $item->update([
                            'approved_by_2' => auth()->user()->id,
                            'is_approved' => true,
                        ]);
                    } else {
                        session()->flash('error', 'You cannot give second approval because you have already given the first approval.');
                        return redirect()->route('items.index', ['tab' => 'need-approval']);
                    }
                }
            } else {
                $item->update([
                    'approved_by' => auth()->user()->id,
                    'approved_by_2' => auth()->user()->id,
                    'is_approved' => true,
                ]);
            }
        }

        $this->sendEmail($items);

        return redirect()
            ->route('items.index', ['tab' => 'need-approval'])
            ->with('success', 'Items approved successfully');
    }

    public function reject()
    {
        $checked = collect($this->items_checklist)->filter(function ($value, $key) {
            return $value === true;
        });

        $item_ids = $checked->keys();
        $items = Item::whereIn('id', $item_ids)->get();

        foreach ($items as $item) {
            $item->delete();
        }

        return redirect()
            ->route('items.index', ['tab' => 'need-approval'])
            ->with('success', 'Items rejected successfully');
    }

    public function sendEmail($items)
    {
        $types = NotificationEmailType::where('name', EmailNotificationTypes::ITEM_APPROVED)->first();

        if ($types) {
            foreach ($types->emails as $receiver) {
                Mail::to($receiver->email)->send(new \App\Mail\ItemApproved($items));
            }
        }
    }
    public function checkAll()
    {
        foreach ($this->items as $item) {
            $this->items_checklist[$item->id] = $this->select_all;
        }
    }

    private function getSimiliarItem($itemName, $items)
    {
        $results = [];
        $percentage = null;

        foreach ($items as $candidate) {
            similar_text($itemName, $candidate->name, $percentage);

            if ($percentage > 80) {
                // Threshold

                $candidate->score = $percentage;
                $results[] = $candidate;
            }
        }

        usort($results, function ($a, $b) {
            return $b->score - $a->score;
        });

        $topMatched = array_slice($results, 0, 10);

        $similiar = collect($topMatched)->pluck('name');

        return $similiar;
    }
}
