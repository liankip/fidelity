<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Leave;
use App\Models\Project;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class LeaveRequest extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $search;
    public $filter = 0;
    public $createForm = 0;
    public $editForm = 0;
    public $projectmodel;
    public $projects;
    public $users;

    public $setting;
    
    // Data
    public $user_id;
    public $project_id;
    public $reason;
    public $attachment;
    public $attachment_update;
    public $notes;
    public $start_date;
    public $end_date;
    public $days_count;

    public $editing_id;
    public $editing_user_id;
    public $editing_project_id;
    public $editing_reason;
    public $editing_attachment;
    public $editing_new_attachment;
    public $editing_notes;
    public $editing_start_date;
    public $editing_end_date;
    public $editing_days_count;
    public $editing_created_at;

    public $selectedLeave;
    public $selectedRejectLeave;
    public $selectedUploadFile;

    protected $listeners = ['confirmApproval', 'confirmRejection'];

    protected $rules = [
        'user_id' => 'required',
        'project_id' => 'required',
        'reason' => 'required',
        'attachment' => 'nullable|sometimes|max:1024',
        'notes' => 'nullable',
        'start_date' => 'required',
        'end_date' => 'required',
    ];
    
    public function mount()
    {
        $this->setting = Setting::first();
    }

    public function filterHandler($category)
    {
        $this->filter = $category;
        // $this->reset();
    }

    public function handleCreateForm()
    {
        // Toggle the value of $this->createForm between 1 and 0
        $this->createForm = $this->createForm === 1 ? 0 : 1;
    }

    public function handleEditForm(Leave $leave)
    {
        // Toggle the value of $this->createForm between 1 and 0
        $this->editForm = $this->editForm === 1 ? 0 : 1;
        $this->editing_created_at = Carbon::parse($leave->created_at)->format('Y-m-d');
        $this->editing_id = $leave->id;
        $this->editing_user_id = $leave->user_id;
        $this->editing_project_id = $leave->project_id;
        $this->editing_reason = $leave->reason;
        $this->editing_attachment = $leave->attachment_file;
        $this->editing_notes = $leave->notes;
        $this->editing_start_date = Carbon::parse($leave->start_date)->format('Y-m-d');
        $this->editing_end_date = Carbon::parse($leave->end_date)->format('Y-m-d');
        $this->editing_days_count = $leave->days_count;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        if ($propertyName === 'start_date' || $propertyName === 'end_date') {
            $this->calculateDaysCount();
        }
        if ($propertyName === 'editing_start_date' || $propertyName === 'editing_end_date') {
            $this->calculateEditedDaysCount();
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function calculateDaysCount()
    {
        if ($this->start_date && $this->end_date) {
            $start = new \DateTime($this->start_date);
            $end = new \DateTime($this->end_date);
            $diff = $start->diff($end);

            $this->days_count = $diff->days + 1; // Add 1 to include both start and end dates
        } else {
            $this->days_count = null;
        }
    }

    public function calculateEditedDaysCount()
    {
        if ($this->editing_start_date && $this->editing_end_date) {
            $start = new \DateTime($this->editing_start_date);
            $end = new \DateTime($this->editing_end_date);
            $diff = $start->diff($end);

            $this->editing_days_count = $diff->days + 1; // Add 1 to include both start and end dates
        } else {
            $this->editing_days_count = null;
        }
    }

    public function store()
    {
        $validatedData = $this->validate();
        $existing_user = Leave::where('user_id', $validatedData['user_id'])->latest()->first();
        $validated['attachment'] = $this->attachment ? $this->attachment->store('leave-request', 'public') : null;

        Leave::create([
            'user_id' => $validatedData['user_id'],
            'project_id' => $validatedData['project_id'],
            'reason' => $validatedData['reason'],
            'notes' => $validatedData['notes'] ? $validatedData['notes'] : '-' ,
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'days_count' => $this->days_count,
            'remaining_days' => $existing_user ? $existing_user->remaining_days - $this->days_count : 12 - $this->days_count,
            'status' => 'New',
            'attachment_file' => $validated['attachment'],
        ]);

        
        
        $this->reset('user_id', 'project_id', 'reason', 'notes', 'start_date',  'end_date', 'days_count');
        session()->flash('success', 'Berhasil menambahkan data');
        $this->handleCreateForm();
    }

    public function update($editing_id)
    {
        $validatedData = $this->validate([
            'editing_new_attachment' => 'nullable|max:1024',
        ]);

        $validated['attachment'] = $this->editing_new_attachment ? $validatedData['editing_new_attachment']->store('leave-request', 'public') : $this->editing_attachment ;

        Leave::find($editing_id)->update([
            'user_id' => $this->editing_user_id,
            'project_id' => $this->editing_project_id,
            'reason' => $this->editing_reason,
            'attachment_file' => $validated['attachment'],
            'notes' => $this->editing_notes,
            'start_date' => $this->editing_start_date,
            'end_date' => $this->editing_end_date,
            'days_count' => $this->editing_days_count,
            'remaining_days' => 12 - $this->editing_days_count,
        ]);

        $this->reset('editForm','editing_id', 'editing_user_id', 'editing_project_id', 'editing_reason', 'editing_attachment', 'editing_new_attachment', 'editing_notes', 'editing_start_date', 'editing_end_date', 'editing_days_count');
        session()->flash('success', 'Berhasil mengedit data');
    }

    public function approveRequest(Leave $leave)
    {
        Leave::find($leave->id)->update([
            'status' => 'Approved',
            'approved_by' => Auth::user()->id,
            'date_approved' => date('Y-m-d H:i:s')
        ]);
        
        $this->reset('selectedLeave');
    }

    public function rejectRequest(Leave $leave)
    {
        Leave::find($leave->id)->update([
            'status' => 'Rejected',
            'approved_by' => Auth::user()->id,
            'date_approved' => date('Y-m-d H:i:s'),
            'remaining_days' => $leave->remaining_days + $leave->days_count
        ]);
        
        $this->reset('selectedRejectLeave');
    }

    public function uploadFile(Leave $leave)
    {
        $validatedData = $this->validate([
                        'attachment_update' => 'required|max:1024',
                    ]);
        $validated['attachment'] = $validatedData['attachment_update']->store('leave-request', 'public');
        Leave::find($leave->id)->update([
            'attachment_file' => $validated['attachment'],
        ]);

        session()->flash('success', 'Berhasil menambahkan bukti sakit');
        $this->reset('selectedUploadFile','attachment_update');
    }

    public function approveModal(Leave $leave)
    {
        $this->selectedLeave = $leave->id;
    }

    public function rejectModal(Leave $leave)
    {
        $this->selectedRejectLeave = $leave->id;
    }

    public function attachmentModal(Leave $leave)
    {
        $this->selectedUploadFile = $leave->id;
    }

    public function closeModal()
    {
        $this->reset('selectedLeave','selectedRejectLeave','selectedUploadFile');
    }

    public function render()
    {
        $this->projects = Project::where("status", "On going")->get();
        $this->users = User::where('active', 1)->get();
        $query = Leave::latest();

        if ($this->search) {
            $query->whereHas('user', function ($subquery) {
                $subquery->where('name', 'like', '%' . $this->search . '%');
            });
            $leaves = $query->get();
        } else {
            if ($this->filter == 1) {
                $query->where('status', 'New');
            } elseif ($this->filter == 2) {
                $query->where('status', 'Approved');
            } elseif ($this->filter == 3) {
                $query->where('status', 'Rejected');
            }
        }
        $leaves = $query->paginate(10);

        return view('livewire.leave-request',[
            'leaves' => $leaves
        ]);
    }
}
