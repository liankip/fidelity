<?php

namespace App\Http\Livewire\Voucher;

use App\Models\PaymentSubmissionModel;
use App\Models\Voucher;
use Livewire\Component;

class DetailAdditional extends Component
{
    public $voucher;
    public $submission;

    public function mount(PaymentSubmissionModel $submission, Voucher $voucher)
    {
        $this->submission = $submission;
        $this->voucher = $voucher;
    }

    public function render()
    {
        return view('livewire.voucher.detail-additional');
    }
}
