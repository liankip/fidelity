<?php

namespace App\Http\Livewire\Voucher;

use App\Models\Payment;
use App\Models\PaymentSubmissionModel;
use App\Models\PurchaseOrder;
use App\Models\Voucher;
use App\Models\VoucherDetail;
use App\Traits\VoucherTotalPaidHelper;
use Livewire\Component;

class EditVoucher extends Component
{
    use VoucherTotalPaidHelper;

    public $keyword;
    public $selectedItem;
    public $checked = [];

    public $keterangan;
    public $bank_penerima;
    public $project;
    public $nama_item;
    public $peminta_penerima;
    public $total_amount = 0;
    public $voucherDetails = [];
    public $voucher;
    public $newVouchers = [];
    public $submission;
    public $isLunas = [];

    public array $amount_to_pay = [];
    public array $amount = [];
    public $faktur_pajak = [];
    public $faktur_pajak_new = [];

    protected array $messages = [
        'faktur_pajak_new.*.required' => 'Faktur pajak harus di isi',
        'faktur_pajak_new.*.numeric' => 'Format harus angka',

        'amount_to_pay.*.required' => 'Total Pembayaran harus di isi',
        'amount_to_pay.*.numeric' => 'Format penulisan harus angka',
        'amount_to_pay.*.lt' => 'Jumlah pembayaran yang akan dibayarkan harus lebih kecil daripada total harga PO',

        'amount.*.required' => 'Total Pembayaran harus di isi',
        'amount.*.numeric' => 'Format penulisan harus angka',
        'amount.*.lt' => 'Jumlah pembayaran yang akan dibayarkan harus lebih kecil daripada total harga PO',
    ];

    protected $listeners = [
        'removeVoucher' => '$refresh',
    ];

    public function editFakturPajak($id)
    {
        $this->tampil_faktur_pajak = $id;
    }

    public function mount(PaymentSubmissionModel $submission, Voucher $voucher)
    {
        $this->submission = $submission;
        $this->voucher = $voucher;
        $this->voucherDetails = $this->voucher->voucher_details;
        foreach ($this->voucherDetails as $vd) {
            $this->amount_to_pay[$vd->id] = $vd->amount_to_pay;
            $this->faktur_pajak[$vd->id] = $vd->faktur_pajak;
        }
        $this->rules = $this->amount_to_pay;
    }

    public function render()
    {
        $query = PurchaseOrder::whereIn('status', ['approved', 'need to pay'])
            ->with(['supplier', 'project', 'do', 'invoices'])
            ->whereHas('supplier', function ($q) {
                $q->where('id', '=', $this->voucherDetails[0]->supplier_id);
            })
            ->where(function ($q) {
                $q->where('po_no', 'like', '%' . $this->keyword . '%')
                    ->orWhereHas('project', function ($q) {
                        $q->where('name', 'like', '%' . $this->keyword . '%');
                    });
            });

        $purchaseOrder = $query->orderBy('po_no')
            ->orderByDesc('created_at')->paginate(10);

        return view('livewire.voucher.edit-voucher', [
            'purchaseOrder' => $this->addTotalPaidAttribute($purchaseOrder),
        ]);
    }

    public function addVoucher()
    {
        $checked = collect($this->checked)->filter(function ($value, $key) {
            return $value;
        })->keys();
        $uniqId = uniqid();
        foreach ($checked as $key => $item) {
            $poData = PurchaseOrder::with('project', 'supplier', 'podetail', 'submition', 'pr',)->where('po_no', $item)->get();
            $modifyAttr = $this->addTotalPaidAttribute($poData);
            $this->newVouchers[] = [
                'id' => $uniqId . $key,
                'purchase_orders' => $modifyAttr
            ];
        }

        $this->checked = [];
    }

    public function removeVoucher($id)
    {
        $this->newVouchers = array_filter($this->newVouchers, function ($voucher) use ($id) {
            return $voucher['id'] !== $id;
        });

        $this->emit('removeVoucher');
    }

    public function deleteVoucher($id)
    {
        $voucherDetail = VoucherDetail::find($id);
        $voucherDetail->delete();

        $this->emitSelf('removeVoucher');
    }

    public function update($id)
    {
        $rules = [];
        $message = [];
        $voucherId = 0;

        foreach ($this->voucherDetails as $voucher) {
            $voucherId = $voucher['id'];

            $rules[("amount_to_pay.$voucherId")] = [
                'nullable',
                'numeric',
            ];

            $rules["faktur_pajak.$voucherId"] = 'required';
            $messages["faktur_pajak.$voucherId.required"] = "Faktur Pajak harus di isi";

            if ((int)$this->amount_to_pay[$voucher['id']] == 0) {
                $rules["amount_to_pay.$voucherId"][] = 'required';
                $message["amount_to_pay.$voucherId.required"] = "Total Pembayaran harus di isi";
            } else if (intval($this->amount_to_pay[$voucher['id']]) > $voucher['total']) {
                $rules["amount_to_pay.$voucherId"][] = 'lt:' . number_format($voucher['total']);
                $message["amount_to_pay.$voucherId.lt"] = "Total yang harus dibayarkan tidak boleh melebihi Rp. " . number_format($voucher['total']);
            }

            if (isset($this->amount_to_pay[$voucher['id']])) {
                $voucher->update([
                    'faktur_pajak' => $this->faktur_pajak[$voucher['id']],
                    'amount_to_pay' => $this->amount_to_pay[$voucher['id']]
                ]);
            }
        }

        foreach ($this->newVouchers as $voucher) {
            $voucherId = $voucher['id'];
            $amount = $this->amount[$voucherId] ?? null;

            foreach ($voucher['purchase_orders'] as $po) {
                $exist = VoucherDetail::where('purchase_order_id', $po['id'])->where('voucher_id', $id)->first();

                $paymentData = Payment::where('po_id', $po['id'])->get();
                $paid = 0;
                foreach ($paymentData as $payment) {
                    $sumAmount = VoucherDetail::where('purchase_order_id', $payment->po_id)->where('voucher_id', $payment->voucher_id)->sum('amount_to_pay');
                    $paid += $sumAmount;
                }

                $paidAmount = $paid;

                $remainingTotal = intval($po['total_amount'] - $paidAmount);

                $rules["amount.$voucherId"] = [
                    'nullable',
                    'numeric',
                ];

                $rules[("faktur_pajak_new.$voucherId")] = [
                    'nullable',
                    'numeric',
                ];

                $rules["faktur_pajak_new.$voucherId"] = 'required';
                $message["faktur_pajak_new.$voucherId.required"] = "Faktur Pajak harus di isi";

                if (is_null($exist)) {
                    $voucherId = $voucher['id'];

                    if (is_null($amount) || $amount === '') {
                        $rules["amount.$voucherId"] = 'required';
                        $message["amount.$voucherId.required"] = "Total Pembayaran harus di isi";
                    } elseif ($amount > $remainingTotal) {
                        $rules["amount.$voucherId"] = "lte:$remainingTotal";
                        $message["amount.$voucherId.lte"] = "Total yang harus dibayarkan tidak boleh melebihi Rp. " . number_format($remainingTotal);
                    }

                    if (isset($this->amount[$voucherId]) && $amount <= $remainingTotal) {
                        $amountToPay = isset($this->isLunas[$voucherId]) && $this->isLunas[$voucherId] === true
                            ? $po['total_amount']
                            : $this->amount[$voucherId];

                        VoucherDetail::create([
                            'voucher_id' => $this->voucher->id,
                            'purchase_order_id' => $po['id'],
                            'supplier_id' => $po['supplier_id'],
                            'project_id' => $po['project_id'],
                            'total' => $po['total_amount'],
                            'amount_to_pay' => $amountToPay,
                            'faktur_pajak' => (int)$this->faktur_pajak_new[$voucherId],
                        ]);
                    }
                }
            }
        }

        $this->validate($rules, $message);

        return redirect()->route('payment-submission.voucher.index', $this->submission->id)->with('success', 'Voucher updated successfully!');
    }
}
