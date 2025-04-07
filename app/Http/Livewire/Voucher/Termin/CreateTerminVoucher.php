<?php

namespace App\Http\Livewire\Voucher\Termin;

use Carbon\Carbon;
use App\Roles\Role;
use App\Models\User;
use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PurchaseOrder;
use App\Models\VoucherDetail;
use App\Helpers\GenerateVoucherNo;
use App\Models\Payment;
use App\Traits\NotificationManager;
use App\Notifications\VoucherCreated;

class CreateTerminVoucher extends Component
{
    use WithPagination, NotificationManager;

    public $keyword;
    public $voucherPeriod;
    public $amount_to_pay = [];
    public $po_total_amount = [];
    public $total = [];
    public $vouchers = [];
    public $checked = [];
    public $additionalField = [];
    protected $paginationTheme = 'bootstrap';

    protected $messages = [
        'amount_to_pay.*.numeric' => 'Format penulisan harus angka',
        'amount_to_pay.*.lt' => 'Jumlah pembayaran yang akan dibayarkan harus leibh kecil daripada total harga PO',
    ];

    public function render()
    {
        $purchaseOrders = PurchaseOrder::with('supplier', 'project')->where('status_barang', 'Arrived');

        $purchaseOrders->when($this->keyword, function ($q) {
            return $q->where('po_no', 'like', '%' . $this->keyword . '%');
        });

        $purchaseOrders->orderByDesc('date_request');
        $purchaseOrders = $purchaseOrders->paginate(10);

        $term_of_payment = [
            'Termin 2' => 1,
            'Termin 3' => 2,
        ];

        $query = PurchaseOrder::whereIn('status', ['approved', 'need to pay'])->whereIn('term_of_payment', ['Termin 2', 'Termin 3'])
            ->with(['supplier', 'project', 'do', 'invoices']);

        $query->where(function ($q) {
            $q->where('po_no', 'like', '%' . $this->keyword . '%')
                ->orWhereHas('supplier', function ($q) {
                    $q->where('name', 'like', '%' . $this->keyword . '%');
                })
                ->orWhereHas('project', function ($q) {
                    $q->where('name', 'like', '%' . $this->keyword . '%');
                });
        });

        // Add additional condition for term_of_payment
        // $query->where(function ($q) {
        //     $q->whereIn('term_of_payment', ['Cash', 'cash'])
        //         ->orWhere('status_barang', 'Arrived');
        // });

        $data_vouchers = $query->orderByDesc('created_at')->get();

        foreach ($data_vouchers as $voucher) {
            
            if (!array_key_exists($voucher->term_of_payment, $term_of_payment)) {
                $voucher->data_term_of_payment = null;
            } else {
                $voucher->data_term_of_payment = $term_of_payment[$voucher->term_of_payment];
            }

            // Check voucher approval and payment
            
            if(count($voucher->voucherDetail) > 0){
                // Approval
                foreach ($voucher->voucherDetail as $detail) {
                    if($detail->voucher->approved_by !== null){
                        $voucher->approvalStatus = true;
                    } else {
                        $voucher->approvalStatus = false;
                    }

                }

                // Payment
                if(count($voucher->payments) > 0){
                    $paid = 0;
                    foreach($voucher->payments as $payment){
                        $sumAmount = VoucherDetail::where('purchase_order_id', $payment->po_id)->where('voucher_id', $payment->voucher_id)->sum('amount_to_pay');
                        $paid += $sumAmount;
                    }

                    $voucher->paidAmount = $paid;

                    if ($voucher->paidAmount >= $detail->total) {
                        $voucher->terminStatus = 'Lunas';
                    } 
                } else {
                    $voucher->terminStatus = 'Belum lunas';
                }
            }

            // Check Invoice
            if(count($voucher->invoices) === 0) {
                $voucher->invoice_status = null;
            } elseif (count($voucher->invoices) > 0) {

                if ($voucher->term_of_payment === 'Termin 2') {
                    $paymentExist = count($voucher->payments);
                    if($paymentExist === 0){
                        $voucher->invoice_status = true;
                    } else {
                        // Check Barang Sampai
                        $percentComplete = $voucher->percent_complete;
                        $voucher->invoice_status = $percentComplete >= 100 ? true : 'Termin 2 Incomplete';
                    }
                }

                if ($voucher->term_of_payment === 'Termin 3') {
                    // Check Total Invoice
                    $totalInvoice = count($voucher->invoices);
                    $percentComplete = $voucher->percent_complete;
                    $paymentExist = count($voucher->payments);

                    if ($paymentExist === 0) {
                        $voucher->invoice_status = true;
                    } elseif ($paymentExist === 1 && $totalInvoice < 2) {
                        $voucher->invoice_status = 'Termin 3 Incomplete Invoice';
                    } elseif ($paymentExist > 1 && $totalInvoice >=2 && $percentComplete < 100) {
                        $voucher->invoice_status = 'Termin 3 Incomplete';
                    } else {
                        $voucher->invoice_status = true;
                    }

                }
            } 
            else {
                $voucher->invoice_status = true;
            }

            // if (in_array($voucher->data_term_of_payment, [3, 4])) {
            //     // For CoD terms, ensure there's either a DeliveryOrder or Invoice
            //     if ($voucher->do->count() == 0 && $voucher->invoices->count() == 0) {
            //         $voucher->data_term_of_payment = null;
            //     }
            // }
        }
        return view('livewire.voucher.termin.create-termin-voucher', [
            'purchaseOrders' => $purchaseOrders,
            'data_vouchers' => $data_vouchers,
        ]);
    }

    public function addVoucher()
    {
        $checked = collect($this->checked)->filter(function ($value, $key) {
            return $value;
        })->keys();
        $uniqId = uniqid();
        foreach ($checked as $key => $item) {
            $this->vouchers[] = [
                'id' => $uniqId . $key,
                'purchase_orders' => PurchaseOrder::with('project', 'supplier', 'podetail', 'submition', 'pr',)->where('po_no', $item)->get()
            ];
        }

        $this->checked = [];
    }

    public function removeVoucher($id)
    {
        $this->vouchers = collect($this->vouchers)->filter(function ($value, $key) use ($id) {
            return $value['id'] != $id;
        })->toArray();
    }

    public function save()
    {
        $rules = [];
        $message = [];
        foreach ($this->vouchers as $voucher) {
            $voucherId = $voucher['id'];
            foreach ($voucher['purchase_orders'] as $po) {
                $paymentData = Payment::where('po_id', $po['id'])->get();
                $totalPayment = count($paymentData);
                $paid = 0;
                foreach($paymentData as $payment){
                    $sumAmount = VoucherDetail::where('purchase_order_id', $payment->po_id)->where('voucher_id', $payment->voucher_id)->sum('amount_to_pay');
                    $paid += $sumAmount;
                }

                $paidAmount = $paid;
                
                $remainingTotal = intval($po['total_amount'] - $paidAmount);

                $rules[("amount_to_pay.$voucherId")] = [
                    'nullable',
                    'numeric',
                    // 'lt:po_total_amount.' . $voucherId,
                ];

                $enteredAmount = intval($this->amount_to_pay[$voucher['id']]);
                if($totalPayment === 0 && $enteredAmount > $remainingTotal){
                    $rules["amount_to_pay.$voucherId"][] = 'lt:' . $remainingTotal;
                    $message["amount_to_pay.$voucherId.lt"] = "Total yang harus dibayarkan tidak boleh melebihi Rp. " .number_format($remainingTotal);
                }
                
                
                if (($po['term_of_payment'] === 'Termin 2' && $totalPayment === 1) || ($po['term_of_payment'] === 'Termin 3' && $totalPayment >=2)) {
                    $voucherAmountToPay = intval($this->amount_to_pay[$voucher['id']]);
                    
                    if($voucherAmountToPay !== $remainingTotal){
                        $rules["amount_to_pay.$voucherId"][] = 'same:' . $remainingTotal;
                        $message["amount_to_pay.$voucherId.same"] = "Total yang harus dibayarkan sebesar Rp. " .number_format($remainingTotal);
                    }

                }

                $this->po_total_amount[$voucherId] = $po['total_amount'];
            }
        }

        $this->validate($rules, $message);

        $existingWaitingApproval = Voucher::where('approved_by', null)->where('type', 'Termin')->get();
        if (count($existingWaitingApproval) >= 1) {
            return redirect()->route('vouchers.create')->with('danger', 'Terdapat Voucher Yang Masih Menunggu Approval');
        } else {
            $voucherNo = GenerateVoucherNo::get();

            $newVoucher = Voucher::create([
                'voucher_no' => $voucherNo,
                'created_at' => Carbon::now(),
                'approved_by' => null,
                'date_approved' => null,
                'additional_informations' => json_encode($this->additionalField),
                'type' => 'Termin'
            ]);

            $recerver = User::withoutRole(Role::MANAGER);

            foreach ($this->vouchers as $voucher) {
            //    dd($this->amount_to_pay[$voucher['id']]);
                foreach ($voucher['purchase_orders'] as $po) {
                    $exist = VoucherDetail::where('purchase_order_id', $po['id'])
                        ->where('voucher_id', $newVoucher->id)
                        ->first();
                    if (is_null($exist)) {
                        VoucherDetail::create([
                            'voucher_id' => $newVoucher->id,
                            'purchase_order_id' => $po['id'],
                            'supplier_id' => $po['supplier_id'],
                            'project_id' => $po['project_id'],
                            'total' => $po['total_amount'],
                            'amount_to_pay' => $this->amount_to_pay[$voucher['id']] // Store amount_to_pay
                        ]);
                    }
                }
            }
            $this->sendNotification($newVoucher, $recerver, VoucherCreated::class);
            return redirect()->route('vouchers.termin.index')->with('success', 'Voucher successfully created');
        }
    }

    public function addField()
    {
        for ($i = 0; $i < 6; $i++) {
            $this->additionalField[] = '';
        }
    }

    public function removeFields()
    {
        $this->additionalField = array_slice($this->additionalField, 0, -6);
    }
}
