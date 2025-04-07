<?php

namespace App\Http\Livewire\Voucher;

use App\Models\PaymentSubmissionModel;
use App\Models\Voucher;
use Livewire\Component;

class EditAdditional extends Component
{
    public $i = 1;
    public $voucher;
    public $submission;
    public array $additionalField = [];
    public array $moreAdditionalField = [];

    protected array $rules = [
        'moreAdditionalField.*.is_confirm' => 'required|boolean',
        'moreAdditionalField.*.faktur_pajak' => 'required|string',
        'moreAdditionalField.*.keterangan' => 'required|string',
        'moreAdditionalField.*.no_rekening' => 'required|string',
        'moreAdditionalField.*.bank_penerima' => 'required|string',
        'moreAdditionalField.*.project' => 'required|string',
        'moreAdditionalField.*.nama_item' => 'required|string',
        'moreAdditionalField.*.peminta_penerima' => 'required|string',
        'moreAdditionalField.*.total_amount' => 'required|numeric|min:0',
    ];

    protected array $messages = [
        'moreAdditionalField.*.is_confirm.required' => 'Persetujuan Direksi harus diisi.',
        'moreAdditionalField.*.faktur_pajak' => 'Faktur Pajak harus diisi.',
        'moreAdditionalField.*.keterangan.required' => 'Keterangan harus diisi.',
        'moreAdditionalField.*.bank_penerima.required' => 'Nama Penerima harus diisi.',
        'moreAdditionalField.*.no_rekening' => 'No Rekening harus diisi.',
        'moreAdditionalField.*.project.required' => 'Project harus diisi.',
        'moreAdditionalField.*.nama_item.required' => 'Nama Item harus diisi.',
        'moreAdditionalField.*.peminta_penerima.required' => 'Peminta Penerima harus diisi.',
        'moreAdditionalField.*.total_amount.required' => 'Total Amount harus diisi.',
        'moreAdditionalField.*.total_amount.numeric' => 'Total Amount harus angka.',
        'moreAdditionalField.*.total_amount.min' => 'Total Amount tidak boleh lebih kecil 0.',
    ];

    public function mount(PaymentSubmissionModel $submission, Voucher $voucher)
    {
        $this->submission = $submission;
        $this->voucher = $voucher;
        $this->additionalField = json_decode($voucher->additional_informations, true);
    }

    public function removeField($index): void
    {
        unset($this->additionalField[$index]);
    }

    public function moreAddField($i)
    {
        $i = $i + 1;
        $this->i = $i;

        $this->moreAdditionalField[] = $i;
    }


    public function moreRemoveField($index)
    {
        unset($this->moreAdditionalField[$index]);
    }

    public function update($id)
    {
        $this->validate();

        $moreAdditionalInformation = [];

        if (!empty($this->moreAdditionalField) && count($this->moreAdditionalField) > 0) {
            foreach ($this->moreAdditionalField as $key => $value) {
                $additionalInformation[] = [
                    'is_confirm' => $this->moreAdditionalField[$key]['is_confirm'],
                    'faktur_pajak' => (int)$this->moreAdditionalField[$key]['faktur_pajak'],
                    'keterangan' => $this->moreAdditionalField[$key]['keterangan'],
                    'no_rekening' => $this->moreAdditionalField[$key]['no_rekening'],
                    'bank_penerima' => $this->moreAdditionalField[$key]['bank_penerima'],
                    'project' => $this->moreAdditionalField[$key]['project'],
                    'nama_item' => $this->moreAdditionalField[$key]['nama_item'],
                    'peminta_penerima' => $this->moreAdditionalField[$key]['peminta_penerima'],
                    'total' => (int)$this->moreAdditionalField[$key]['total_amount'],
                ];
            }

            $moreAdditionalInformation = $additionalInformation;
        }

        $additionalField = json_encode($this->additionalField);

        $mergedAdditionalInformations = !empty($moreAdditionalInformation) ? array_merge(json_decode($additionalField, true), $moreAdditionalInformation) : json_decode($additionalField, true);

        Voucher::where('id', $id)->update([
            'additional_informations' => json_encode($mergedAdditionalInformations),
        ]);

        return redirect()->route('payment-submission.voucher.index', $this->submission->id)->with('success', 'Voucher Non PO updated successfully!');
    }

    public function render()
    {
        return view('livewire.voucher.edit-additional');
    }
}
