<?php

namespace App\Http\Livewire\Voucher\Termin;

use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class AllTermin extends Component
{
    use WithPagination, WithFileUploads;

    public $keyword;

    public function render()
    {
        $query = Voucher::query();

        $query->where(function ($q) {
            $q->where('voucher_no', 'like', '%' . $this->keyword . '%');
        });

        $vouchers = $query->where('type', 'Termin')->orderBy('created_at', 'desc')->get();
        return view('livewire.voucher.termin.all-termin', compact('vouchers'));
    }
}
