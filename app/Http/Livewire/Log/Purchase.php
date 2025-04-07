<?php

namespace App\Http\Livewire\Log;

use App\Models\HistoryPurchase;
use Livewire\Component;
use Livewire\WithPagination;

class Purchase extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;


    public function UpdatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        if ($this->search) {
            $get = HistoryPurchase::with("user")
                ->where("referensi", "like", "%" . $this->search . "%")
                ->orWhere("action_start", "like", "%" . $this->search . "%")
                ->orWhere("action_end", "like", "%" . $this->search . "%")
                ->orWhere('action_date', "like", "%" . $this->search . "%")
                ->orWhereHas('user', function ($query) {
                    $query->where("name", "like", "%" . $this->search . "%");
                })
                ->orderBy("created_at", "desc");
        } else {
            $get = HistoryPurchase::with("user")->orderBy("created_at", "desc");
        }
        $history = $get->paginate(20);
        return view('livewire.log.purchase', ["history" => $history]);
    }
}
