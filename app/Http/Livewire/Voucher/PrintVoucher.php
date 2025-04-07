<?php

namespace App\Http\Livewire\Voucher;

use App\Models\Voucher;
use Livewire\Component;

class PrintVoucher extends Component
{
    public $showModal = false;
    public $voucherDate;

    public function render()
    {
        return view('livewire.voucher.print-voucher');
    }

    public function toggleModalVoucher()
    {
        $this->showModal = !$this->showModal;
    }

    public function print()
    {
        $this->validate([
            'voucherDate' => 'required',
        ]);

        $voucher = Voucher::whereDate("created_at", $this->voucherDate)->first();

        if (is_null($voucher)) {
            return $this->addError("voucherDate", "Tidak ada voucher pada tanggal tersebut");
        }

        return redirect()->route("print-voucher", ["date" => $this->voucherDate]);
    }
}
