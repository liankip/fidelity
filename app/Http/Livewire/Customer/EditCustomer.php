<?php

namespace App\Http\Livewire\Customer;

use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditCustomer extends Component
{
    use WithFileUploads;

    public $customer;
    public $name;
    public $npwp;
    public $shipping_address;
    public $ktp;
    public $ktpUrl;
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

    public function mount(\App\Models\Customer $customer)
    {
        $this->customer = $customer;

        $this->name = $customer->name;
        $this->npwp = $customer->npwp;
        $this->shipping_address = $customer->shipping_address;
        $this->ktpUrl = $customer->ktp;
        $this->pic_name = $customer->pic_name;
        $this->pic_phone = $customer->pic_phone;
        $this->pic_email = $customer->pic_email;
        $this->recipient_name = $customer->recipient_name;
        $this->recipient_phone = $customer->recipient_phone;

        $this->billing_address = $customer->billing_address ? json_decode($customer->billing_address, true) : [];

        $this->billing_phone = $customer->billing_phone;
        $this->billing_email = $customer->billing_email;
    }

    public function edit()
    {
        $validate = $this->validate(
            [
                'name' => 'string|max:70',
                'npwp' => 'max:16',
                'shipping_address' => 'nullable|string',
                'ktp' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
                'pic_name' => 'string|max:80',
                'pic_phone' => 'max:18',
                'pic_email' => 'email|max:60',
                'recipient_name' => 'string|max:80',
                'recipient_phone' => 'max:18',
                'billing_address.*' => 'string',
                'billing_phone' => 'max:18',
                'billing_email' => 'string|email',
            ],
            [
                'name.string' => 'Nama harus berupa kata/huruf',
                'name.max' => 'Nama maksimal 70 karakter',
                'npwp.numeric' => 'NPWP harus berupa angka',
                'npwp.max' => 'NPWP maksimal 16 karakter',
                'shipping_address.string' => 'Alamat pengiriman harus berupa kata/huruf',
                'ktp.file' => 'KTP harus berupa file',
                'ktp.mimes' => 'KTP harus berupa file dengan format jpg, png, atau jpeg',
                'pic_name.string' => 'Nama PIC harus berupa kata/huruf',
                'pic_name.max' => 'Nama PIC maksimal 80 karakter',
                'pic_phone.numeric' => 'Nomor telepon PIC harus berupa angka',
                'pic_phone.max' => 'Nomor telepon PIC maksimal 18 karakter',
                'pic_email.email' => 'Email PIC harus berupa email',
                'pic_email.max' => 'Email PIC maksimal 60 karakter',
                'recipient_name.string' => 'Nama penerima harus berupa kata/huruf',
                'recipient_name.max' => 'Nama penerima maksimal 80 karakter',
                'recipient_phone.numeric' => 'Nomor telepon penerima harus berupa angka',
                'recipient_phone.max' => 'Nomor telepon penerima maksimal 18 karakter',
                'billing_address.*.string' => 'Alamat tagihan harus berupa kata/huruf',
                'billing_phone.numeric' => 'Nomor telepon tagihan harus berupa angka',
                'billing_phone.max' => 'Nomor telepon tagihan maksimal 18 karakter',
                'billing_email.email' => 'Email tagihan harus berupa email',
            ],
        );

        $customer = Customer::find($this->customer->id);

        if ($this->ktp) {
            if ($this->ktpUrl) {
                $oldFilePath = parse_url($this->ktpUrl, PHP_URL_PATH);
                Storage::disk('gcs')->delete($oldFilePath);
            }

            $imageName = time() . '.' . $this->ktp->extension();
            $path = $this->ktp->storeAs('customer/ktp', $imageName, 'local');

            $gcsPath = 'customer/ktp/' . $imageName;
            Storage::disk('gcs')->put($gcsPath, fopen($this->ktp->getRealPath(), 'r+'));
            Storage::disk('local')->delete($path);

            $this->ktpUrl = Storage::disk('gcs')->url($gcsPath);
        }

        $validate['ktp'] = $this->ktpUrl;

        $validate['billing_address'] = json_encode($this->billing_address);

        $customer->update($validate);

        return redirect()->route('customer.index')->with('success', 'Customer edit successfully');
    }

    public function render()
    {
        return view('livewire.customer.edit-customer');
    }
}
