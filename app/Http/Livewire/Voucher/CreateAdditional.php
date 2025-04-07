<?php

namespace App\Http\Livewire\Voucher;

use App\Helpers\GenerateVoucherNo;
use App\Models\OfficeExpenseItem;
use App\Models\PaymentSubmissionModel;
use App\Models\Voucher;
use Carbon\Carbon;
use Livewire\Component;

class CreateAdditional extends Component
{
    public int $i = 0;
    public $additionalField = [];
    public $submission;
    public $nomorVoucher;
    public $item;
    public $officeExpenseItem;
    public bool $showSelect = true;

    public function mount(PaymentSubmissionModel $submission): void
    {
        $this->submission = $submission;
        $this->nomorVoucher = GenerateVoucherNo::set();

        $this->officeExpenseItem = OfficeExpenseItem::where('is_paid', false)->whereNotNull('approved_by')->whereNotNull('approved_date')->where('is_approval', false)->get();

        $additionalIds = collect($this->submission->vouchers)
            ->flatMap(function ($d) {
                return collect(json_decode($d->additional_informations, true))->pluck('id');
            })
            ->unique()
            ->toArray();

        $this->officeExpenseItem = $this->officeExpenseItem->reject(function ($item) use ($additionalIds) {
            return in_array($item->id, $additionalIds);
        });
    }

    public function selectOfficeExpenseItem()
    {
        if ($this->item) {
            $officeExpenseItem = OfficeExpenseItem::find(id: $this->item);

            $this->additionalField[$this->i] = [
                'id' => $officeExpenseItem->id,
                'keterangan' => $officeExpenseItem->officeExpensePurchase->officeExpense->office . ' ' . $officeExpenseItem->officeExpensePurchase->purchase_name . ' - ' . $officeExpenseItem->notes . ' (' . rupiah_format($officeExpenseItem->total_expense) . ')',
                'no_rekening' => $officeExpenseItem->vendor . ' ' . $officeExpenseItem->account_number,
                'bank_penerima' => $officeExpenseItem->receiver_name,
                'nama_item' => $officeExpenseItem->notes,
                'total_amount' => $officeExpenseItem->total_expense,
            ];

            $this->showSelect = true;
            $this->item = 'default';
            $this->i++;
        }
    }

    public function addField(): void
    {
        $this->additionalField[] = $this->i;
        $this->i++;
    }

    public function removeField($key): void
    {
        if (isset($this->additionalField[$key])) {
            unset($this->additionalField[$key]);
            $this->additionalField = array_values($this->additionalField);
        }
    }

    public function save()
    {
        $this->validate(
            [
                'additionalField.*.is_confirm' => 'required|accepted',
                'additionalField.*.faktur_pajak' => 'required|string',
                'additionalField.*.keterangan' => 'required|string',
                'additionalField.*.no_rekening' => 'required|string',
                'additionalField.*.bank_penerima' => 'required|string',
                'additionalField.*.project' => 'required|string',
                'additionalField.*.nama_item' => 'required|string',
                'additionalField.*.peminta_penerima' => 'required|string',
                'additionalField.*.total_amount' => 'required|numeric|min:0',
            ],
            [
                'additionalField.*.is_confirm.required' => 'Sudah Diketahui Direksi harus diisi.',
                'additionalField.*.is_confirm.accepted' => 'Sudah Diketahui Direksi tidak boleh kosong sesudah diisi.',
                'additionalField.*.faktur_pajak' => 'Faktur Pajak harus diisi.',
                'additionalField.*.keterangan.required' => 'Keterangan harus diisi.',
                'additionalField.*.no_rekening' => 'No Rekening harus diisi.',
                'additionalField.*.bank_penerima.required' => 'Nama Penerima harus diisi.',
                'additionalField.*.project.required' => 'Project harus diisi.',
                'additionalField.*.nama_item.required' => 'Nama Item harus diisi.',
                'additionalField.*.peminta_penerima.required' => 'Peminta Penerima harus diisi.',
                'additionalField.*.total_amount.required' => 'Total Amount harus diisi.',
                'additionalField.*.total_amount.numeric' => 'Total Amount harus angka.',
                'additionalField.*.total_amount.min' => 'Total Amount tidak boleh lebih kecil 0.',
            ],
        );

        $additionalInformation = [];
        if (count(value: $this->additionalField) > 0) {
            foreach ($this->additionalField as $key => $value) {
                $item = [];
                if (isset($this->additionalField[$key]['id'])) {
                    $item['id'] = $this->additionalField[$key]['id'];
                }
                $item['is_confirm'] = $this->additionalField[$key]['is_confirm'];
                $item['faktur_pajak'] = $this->additionalField[$key]['faktur_pajak'];
                $item['keterangan'] = $this->additionalField[$key]['keterangan'];
                $item['no_rekening'] = $this->additionalField[$key]['no_rekening'];
                $item['bank_penerima'] = $this->additionalField[$key]['bank_penerima'];
                $item['project'] = $this->additionalField[$key]['project'];
                $item['nama_item'] = $this->additionalField[$key]['nama_item'];
                $item['peminta_penerima'] = $this->additionalField[$key]['peminta_penerima'];
                $item['total'] = $this->additionalField[$key]['total_amount'];

                $additionalInformation[] = $item;
            }
        }

        Voucher::create(
            attributes: [
                'voucher_no' => GenerateVoucherNo::get(),
                'payment_submission_id' => $this->submission->id,
                'created_at' => Carbon::now(),
                'additional_informations' => json_encode(value: $additionalInformation),
            ],
        );

        return redirect()->route('payment-submission.voucher.index', $this->submission->id)->with('success', 'Voucher Non PO successfully created');
    }

    public function render()
    {
        return view('livewire.voucher.create-additional');
    }
}
