<?php

namespace App\Http\Livewire\Forms;

use App\Models\MinutesOfMeeting as ModelsMinutesOfMeeting;
use Livewire\Component;
use Livewire\WithPagination;

class MinutesOfMeeting extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all';
    protected $paginationTheme = 'bootstrap';

    public $showModal = false;
    public $imageUrl;

    protected $listeners = ['showImage'];

    public function showImage($url)
    {
        $this->imageUrl = $url;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        $meetings = ModelsMinutesOfMeeting::query();

        if ($this->filter === 'approved') {
            $meetings->where('status', 'approved');
        }

        if (!empty($this->search)) {
            $meetings->where(function ($query) {
                $query->where('meeting_title', 'like', '%' . $this->search . '%')
                    ->orWhere('project_name', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.forms.minutes-of-meeting', [
            'meetings' => $meetings->orderBy('created_at', 'desc')->paginate(10),
        ]);
    }
}
