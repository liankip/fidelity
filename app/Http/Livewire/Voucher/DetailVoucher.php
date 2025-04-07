<?php

namespace App\Http\Livewire\Voucher;

use App\Models\PaymentSubmissionModel;
use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithFileUploads;

class DetailVoucher extends Component
{
    use WithFileUploads;

    public $voucher;
    public $po;
    public $tax_status;
    public $notes;
    public $payment_pict;

    public $submission;

    public function mount(PaymentSubmissionModel $submission, Voucher $voucher)
    {
        $this->submission = $submission;
        $this->voucher = $voucher;
    }

    public function render()
    {
        return view('livewire.voucher.detail-voucher');
    }
}
