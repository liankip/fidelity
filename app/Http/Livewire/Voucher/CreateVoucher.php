<?php

namespace App\Http\Livewire\Voucher;

use App\Helpers\GenerateVoucherNo;
use App\Models\PaymentSubmissionModel;
use App\Models\PurchaseOrder;
use App\Models\Voucher;
use App\Models\VoucherDetail;
use App\Rules\Voucher\ValidAmount;
use App\Traits\NotificationManager;
use App\Traits\VoucherTotalPaidHelper;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class CreateVoucher extends Component
{
    use WithPagination, NotificationManager, VoucherTotalPaidHelper;

    public $keyword;
    public $voucherID;
    public $remainingTotal;
    public $total = [];
    public $vouchers = [];
    public $checked = [];
    public $additionalField = [];
    protected $paginationTheme = 'bootstrap';
    public $i = 1;
    public $keterangan;
    public $bank_penerima;
    public $project;
    public $nama_item;
    public $peminta_penerima;
    public $total_amount = 0;
    public $submission;
    public array $isLunas = [];
    public array $amount = [];
    public $nomorVoucher;
    public $faktur_pajak;

    protected function rules()
    {
        $rules = [];
        foreach ($this->vouchers as $voucher) {
            $voucherId = $voucher['id'];
            $rules["faktur_pajak.{$voucherId}"] = 'required';

            foreach ($voucher['purchase_orders'] as $po) {
                $paid = $po['total_paid_amount'] ?? 0;
                $remainingTotal = intval($po['total_amount'] - $paid);

                if (!isset($this->isLunas[$voucherId]) || $this->isLunas[$voucherId] !== true) {
                    $rules["amount.{$voucherId}"] = ['required', 'lte:' . $remainingTotal, new ValidAmount];
                }
            }
        }

        return $rules;
    }

    protected function messages()
    {
        $messages = [];
        foreach ($this->vouchers as $voucher) {
            $voucherId = $voucher['id'];
            $messages["faktur_pajak.{$voucherId}.required"] = "Faktur Pajak harus di isi";

            foreach ($voucher['purchase_orders'] as $po) {
                $remainingTotal = intval($po['total_amount'] - ($po['total_paid_amount'] ?? 0));

                if (!isset($this->isLunas[$voucherId]) || $this->isLunas[$voucherId] !== true) {
                    $messages["amount.{$voucherId}.required"] = "Total Pembayaran harus di isi";
                    $messages["amount.{$voucherId}.lte"] = "Total yang harus dibayarkan tidak boleh melebihi Rp. " . number_format($remainingTotal);
                }
            }
        }

        return $messages;
    }

    protected $listeners = [
        'removeVoucher' => '$refresh',
        'save' => '$refresh'
    ];

    public function mount(PaymentSubmissionModel $submission)
    {
        $this->nomorVoucher = GenerateVoucherNo::set();
        $this->submission = $submission;
        foreach ($this->vouchers as $voucher) {
            $this->voucherID = $voucher['id'];
            foreach ($voucher['purchase_orders'] as $po) {

                $paid = 0;
                if (isset($po['total_paid_amount'])) {
                    $paid = $po['total_paid_amount'];
                }

                $this->remainingTotal = intval($po['total_amount'] - $paid);
            }
        }
    }

    public function render()
    {
        $purchaseOrders = PurchaseOrder::with('supplier', 'project')->where('status_barang', 'Arrived');

        $purchaseOrders->when($this->keyword, function ($q) {
            return $q->where('po_no', 'like', '%' . $this->keyword . '%');
        });

        $purchaseOrders->orderByDesc('date_request');
        $purchaseOrders = $purchaseOrders->paginate(10);

        $query = PurchaseOrder::whereIn('status', ['approved', 'need to pay'])
            ->with(['supplier', 'project', 'do', 'invoices'])
            ->where(function ($q) {
                $q->where('po_no', 'like', '%' . $this->keyword . '%')
                    ->orWhereHas('supplier', function ($q) {
                        $q->where('name', 'like', '%' . $this->keyword . '%');
                    })
                    ->orWhereHas('project', function ($q) {
                        $q->where('name', 'like', '%' . $this->keyword . '%');
                    });
            });

        $data_vouchers = $query->orderByDesc('created_at')->paginate(10);

        $data_vouchers = $this->addTotalPaidAttribute($data_vouchers);

        return view('livewire.voucher.create-voucher', [
            'purchaseOrders' => $purchaseOrders,
            'data_vouchers' => $data_vouchers,
            'submission' => $this->submission->id,
            'vouchers' => $this->vouchers
        ]);
    }

    public function addVoucher()
    {
        $checked = collect($this->checked)->filter(function ($value, $key) {
            return $value;
        })->keys();

        foreach ($checked as $key => $item) {
            $poData = PurchaseOrder::with('project', 'supplier', 'podetail', 'submition', 'pr',)->where('po_no', $item)->get();
            $modifyAttr = $this->addTotalPaidAttribute($poData);
            $this->vouchers[] = [
                'id' => uniqid() . $key,
                'purchase_orders' => $modifyAttr
            ];
        }

        $this->checked = [];
    }

    public function removeVoucher($id)
    {
        $this->vouchers = collect($this->vouchers)->filter(function ($value, $key) use ($id) {
            return $value['id'] != $id;
        })->toArray();

        $this->emit('removeVoucher');
    }

    public function save()
    {
        $this->validate();
        $vouchersToProcess = [];
        $supplierID = null;

        try {
            foreach ($this->vouchers as $voucher) {
                $voucherId = $voucher['id'];
                foreach ($voucher['purchase_orders'] as $po) {

                    $paid = 0;
                    if (isset($po['total_paid_amount'])) {
                        $paid = $po['total_paid_amount'];
                    }

                    $remainingTotal = intval($po['total_amount'] - $paid);

                    $amount = $this->amount[$voucherId] ?? null;
                    $amountToPay = isset($this->isLunas[$voucher['id']]) && $this->isLunas[$voucher['id']] === true
                        ? $remainingTotal
                        : $amount;

                    if ($supplierID === null) {
                        $supplierID = $po['supplier_id'];
                    } elseif ($po['supplier_id'] !== $supplierID) {
                        return redirect()->back()->with('danger', '1 Voucher hanya memiliki 1 supplier yang sama');
                    }


                    if (!is_null($amountToPay) && $amountToPay !== '' && $amountToPay <= $remainingTotal) {
                        $vouchersToProcess[] = $voucher;
                    }
                }
            }

            if (!empty($vouchersToProcess)) {
                $newVoucher = Voucher::create([
                    'voucher_no' => GenerateVoucherNo::get(),
                    'payment_submission_id' => $this->submission->id,
                    'created_at' => Carbon::now(),
                ]);

                foreach ($vouchersToProcess as $voucher) {
                    $voucherId = $voucher['id'];
                    $amount = $this->amount[$voucherId] ?? null;

                    foreach ($voucher['purchase_orders'] as $po) {
                        $paid = 0;
                        if (isset($po['total_paid_amount'])) {
                            $paid = $po['total_paid_amount'];
                        }

                        $remainingTotal = intval($po['total_amount'] - $paid);

                        $amountToPay = isset($this->isLunas[$voucher['id']]) && $this->isLunas[$voucher['id']] === true
                            ? $remainingTotal
                            : $amount;

                        VoucherDetail::create([
                            'voucher_id' => $newVoucher->id,
                            'purchase_order_id' => $po['id'],
                            'supplier_id' => $po['supplier_id'],
                            'project_id' => $po['project_id'] ?? null,
                            'total' => $po['total_amount'],
                            'amount_to_pay' => $amountToPay,
                            'faktur_pajak' => (int)$this->faktur_pajak[$voucher['id']],
                        ]);
                    }
                }
            }

            $this->emit('save');

            return redirect()->route('payment-submission.voucher.index', $this->submission->id)->with('success', 'Voucher successfully created');
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
}
