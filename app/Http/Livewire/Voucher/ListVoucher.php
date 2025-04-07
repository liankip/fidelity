<?php

namespace App\Http\Livewire\Voucher;

use App\Models\PaymentSubmissionModel;
use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ListVoucher extends Component
{
    use WithPagination, WithFileUploads;

    public $fromDate, $toDate;
    public $keyword;
    protected $paginationTheme = 'bootstrap';
    public $selectedItem;
    public $selectedHistoryItem;
    public $voucher;
    public $po;
    public $paymentHistory;

    // payment
    public $tax_note;
    public $tax_status;
    public $notes;
    public $payment_pict;
    public $submission;

    protected $rules = [
        'payment_pict' => 'required',
        'tax_status' => 'required',
    ];

    public function mount(PaymentSubmissionModel $submission)
    {
        $this->submission = $submission;
    }

    public function closeModal()
    {
        $this->reset('po');
    }

    public function render()
    {
        $query = Voucher::query()
            ->where('payment_submission_id', $this->submission->id);

        if (!empty($this->keyword)) {
            $query->where(function ($q) {
                $q->where('voucher_no', 'like', '%' . $this->keyword . '%')
                    ->orWhereHas('voucher_details.purchase_order.podetail.item', function ($q) {
                        $q->where('name', 'like', '%' . $this->keyword . '%');
                    });
            });
        }

        $vouchers = $query->orderBy('created_at', 'desc')->get();
        return view('livewire.voucher.list-voucher', compact('vouchers'));
    }

    public function printVouchers($paramId)
    {
        $voucher = Voucher::where('id', $paramId)->first();
        return view('livewire.voucher.print-vouchers-new', compact('voucher'));
    }
}
