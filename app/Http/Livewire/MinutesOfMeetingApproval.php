<?php

namespace App\Http\Livewire;

use App\Jobs\MinutesOfMeetingApproval as JobsMinutesOfMeetingApproval;
use App\Mail\MinuteOfMeetingApproval;
use App\Models\MinutesOfMeeting;
use App\Models\MinutesOfMeeting as ModelsMinutesOfMeeting;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class MinutesOfMeetingApproval extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all';
    public $comment;
    public $comments = [];
    public $isMultipleApproval = false;
    protected $paginationTheme = 'bootstrap';

    public $setting;

    protected $listeners = ['openModal' => 'show'];

    public function approve($meetingId)
    {
        $meeting = MinutesOfMeeting::find($meetingId);

        if ($this->setting->multiple_mom_approval) {
            if (is_null($meeting->approved_by) && is_null($meeting->approved_at)) {
                $meeting->approved_by = auth()->user()->id;
                $meeting->approved_at = now();
            } elseif (!is_null($meeting->approved_by) && !is_null($meeting->approved_at) && is_null($meeting->approved_by_2) && is_null($meeting->approved_at_2)) {
                if ($meeting->approved_by != auth()->user()->id) {
                    $meeting->approved_by_2 = auth()->user()->id;
                    $meeting->approved_at_2 = now();
                    $meeting->status = 'approved';
                } else {
                    session()->flash('error', 'Anda tidak dapat memberikan persetujuan kedua karena Anda sudah melakukan persetujuan pertama.');
                    return;
                }
            }
        } else {
            $meeting->approved_by = auth()->user()->id;
            $meeting->approved_at = now();
            $meeting->approved_by_2 = auth()->user()->id;
            $meeting->approved_at_2 = now();
            $meeting->status = 'approved';
        }

        $comments = $meeting->comment ? json_decode($meeting->comment, true) : [];
        $comments[] = [
            'user_id' => auth()->user()->id,
            'name' => auth()->user()->name,
            'comment' => $this->comment,
            'timestamp' => now()->toDateTimeString(),
        ];
        $meeting->comment = json_encode($comments);

        $meeting->save();

        if ($meeting->status === 'approved') {
            JobsMinutesOfMeetingApproval::dispatch($meeting);
            session()->flash('success', 'Approval and notification email sent.');
        }

        $this->reset();

        return redirect()->route('minutes-of-meeting-approval.index')->with('success', 'Meeting successfully approved.');
    }

    public function reject($meetingId)
    {
        $meeting = MinutesOfMeeting::find($meetingId);

        if ($meeting && $meeting->status !== 'approved') {
            $meeting->update([
                'status' => 'rejected',
                'rejected_by' => auth()->user()->id,
                'rejected_at' => now(),
            ]);

            session()->flash('success', 'Meeting successfully rejected.');
        } else {
            session()->flash('danger', 'Action not allowed.');
        }
    }

    public function render()
    {
        $this->setting = Setting::first();

        $meetings = ModelsMinutesOfMeeting::query();

        if ($this->filter === 'approved') {
            $meetings->where('status', 'approved');
        } else {
            $meetings->where('status', 'waiting approval');
        }

        if (!empty($this->search)) {
            $meetings->where(function ($query) {
                $query->where('meeting_title', 'like', '%' . $this->search . '%')->orWhere('project_name', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.minutes-of-meeting-approval', [
            'meetings' => $meetings->orderBy('created_at', 'desc')->paginate(10),
        ]);
    }
}
