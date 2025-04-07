<?php

namespace App\Http\Livewire\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class InsertCustomer extends Component
{
    use WithFileUploads;

    public $name;
    public $npwp;
    public $shipping_address;
    public $ktp;
    public $pic_name;
    public $pic_phone;
    public $pic_email;
    public $recipient_name;
    public $recipient_phone;
    public $billing_address = [''];
    public $billing_phone;
    public $billing_email;

    public function addNewBillingAddress()
    {
        $this->billing_address[] = '';
    }

    public function updateBillingAddress($index, $value)
    {
        $this->billing_address[$index] = $value;
    }

    public function removeBillingAddress($index)
    {
        if (count($this->billing_address) > 1) {
            unset($this->billing_address[$index]);
            $this->billing_address = array_values($this->billing_address);
        }
    }


    public function insert()
    {
        $validate = $this->validate(
            [
                'name' => 'required|string|max:70',
                'npwp' => 'required|max:16',
                'shipping_address' => 'required|string',
                'ktp' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
                'pic_name' => 'required|string|max:80',
                'pic_phone' => 'required|max:18',
                // 'pic_email' => 'required|email|max:60',
                'recipient_name' => 'required|string|max:80',
                'recipient_phone' => 'required|max:18',
                'billing_address.*' => 'required|string',
                'billing_phone' => 'required|max:18',
                // 'billing_email' => 'required|string|email',
            ],
            [
                'nama.required' => 'Nama harus diisi',
                'nama.string' => 'Nama harus berupa huruf/kata',
                'nama.max' => 'Nama maksimal 70 karakter',
                'npwp.required' => 'NPWP harus diisi',
                'npwp.max' => 'NPWP maksimal 16 karakter',
                'shipping_address.required' => 'Alamat pengiriman harus diisi',
                'shipping_address.string' => 'Alamat pengiriman harus berupa huruf/kata',
                'ktp.file' => 'KTP harus berupa file',
                'ktp.mimes' => 'KTP harus berupa file dengan format jpg, png atau jpeg',
                'ktp.max' => 'KTP maksimal 2048 KB',
                'pic_name.string' => 'Nama PIC harus berupa huruf/kata',
                'pic_name.max' => 'Nama PIC maksimal 80 karakter',
                'pic_phone.required' => 'Nomor handphone PIC harus diisi',
                'pic_phone.max' => 'Nomor handphone PIC maksimal 18 karakter',
                // 'pic_email.required' => 'Email PIC harus diisi',
                'pic_email.email' => 'Email PIC harus berupa email',
                'pic_email.max' => 'Email PIC maksimal 60 karakter',
                'recipient_name.required' => 'Nama penerima harus diisi',
                'recipient_name.string' => 'Nama penerima harus berupa huruf/kata',
                'recipient_name.max' => 'Nama penerima maksimal 80 karakter',
                'recipient_phone.required' => 'Nomor handphone penerima harus diisi',
                'recipient_phone.max' => 'Nomor handphone penerima maksimal 18 karakter',
                'billing_address.*.required' => 'Alamat tagihan harus diisi',
                'billing_address.*.string' => 'Alamat tagihan harus berupa huruf/kata',
                'billing_phone.required' => 'Nomor handphone tagihan harus diisi',
                'billing_phone.max' => 'Nomor handphone tagihan maksimal 18 karakter',
                // 'billing_email.required' => 'Email tagihan harus diisi',
                'billing_email.string' => 'Email tagihan harus berupa huruf/kata',
                'billing_email.email' => 'Email tagihan harus berupa email',
            ],
        );

        if ($this->ktp) {
            $imageName = time() . '.' . $this->ktp->extension();

            $tempFotoKtp = $this->ktp->storeAs('customer/ktp/', $imageName, 'local');

            $pathFotoKtp = 'customer/ktp/' . $imageName;

            Storage::disk('gcs')->put($pathFotoKtp, fopen($this->ktp->getRealPath(), 'r+'));
            Storage::disk('local')->delete($tempFotoKtp);

            $imagePath = Storage::disk('gcs')->url($pathFotoKtp);

            $validate['ktp'] = $imagePath;
        }

        $validate['billing_address'] = json_encode($this->billing_address);

        Customer::create($validate);

        $this->reset();

        return redirect()->route('customer.index')->with('success', 'Customer saved successfully');
    }


    public function render()
    {
        return view('livewire.customer.insert-customer');
    }
}
