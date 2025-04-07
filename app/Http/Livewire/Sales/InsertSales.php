<?php

namespace App\Http\Livewire\Sales;

use App\Models\Customer;
use App\Models\Sku;
use Livewire\Component;

class InsertSales extends Component
{
    public $id_customer;
    public $addresses = [];
    public $address;
    public $allProducts = [];
    public $product = [];
    public $qty;
    public $payment_method;
    public $notes;
    public $finish_date;
    public $sales_person;

    public function mount()
    {
        $this->allProducts = Sku::all();

        $this->product[] = '';
    }

    public function addNewProduct()
    {
        $this->product[] = null;
        $this->qty[] = null;
    }

    public function updateProduct($index, $value)
    {
        $this->product[$index] = $value;
    }

    public function removeProduct($index)
    {
        unset($this->product[$index]);
        unset($this->qty[$index]);

        $this->product = array_values($this->product);
        $this->qty = array_values($this->qty);
    }

    public function updatedIdCustomer($value)
    {
        $this->addresses = Customer::where('id', $value)->get() ?? [];
    }

    public function insert()
    {
        $validate = $this->validate([
           'id_customer' => 'required|numeric|exists:customer,id',
           'address' => 'required|string',
           'product.*' => 'required',
           'qty.*' => 'required|numeric|min:1',
           'payment_method' => 'required',
           'notes' => 'string|nullable',
           'finish_date' => 'required',
           'sales_person' => 'required',
       ], [
           'id_customer.required' => 'Customer tidak boleh kosong',
           'id_customer.numeric' => 'Customer tidak valid',
           'id_customer.exists' => 'Customer tidak terdaftar',
           'address.required' => 'Alamat tidak boleh kosong',
           'address.string' => 'Alamat tidak valid',
           'product.*.required' => 'Product tidak boleh kosong',
           'qty.*.required' => 'Quantity wajib diisi',
           'qty.*.numeric' => 'Quantity harus berupa angka',
           'qty.*.min' => 'Quantity minimal 1',
           'payment_method.required' => 'Metode Pembayaran tidak boleh kosong',
           'notes.string' => 'Alamat tidak valid',
           'finish_date.required' => 'Tanggal tidak boleh kosong',
           'sales_person.required' => 'Nama Pelanggan tidak boleh kosong',
       ]);

       $productQty = [];
       foreach ($this->product as $index => $productId) {
           if (isset($this->qty[$index])) {
               $productQty[] = [
                   'product' => $productId,
                   'qty' => $this->qty[$index],
               ];
           }
       }

       $validate['product'] = json_encode($productQty);

       \App\Models\Sales::create($validate);

        $this->reset(['product', 'qty']);

        return redirect()->route('sales.index')->with('success', 'Sales successfully added');

    }

    public function render()
    {
        return view('livewire.sales.insert-sales', [
            'customers' => Customer::all(),
        ]);
    }
}
