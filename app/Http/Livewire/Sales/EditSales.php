<?php

namespace App\Http\Livewire\Sales;

use App\Models\Customer;
use App\Models\Sku;
use Livewire\Component;

class EditSales extends Component
{
    public $id_sales;
    public $id_customer;
    public $addresses = [];
    public $address;
    public $allProducts = [];
    public $product = [];
    public $qty = [];
    public $payment_method;
    public $notes;
    public $finish_date;
    public $sales_person;

    public function mount(\App\Models\Sales $sales)
    {
        $this->id_sales = $sales->id;
        $this->id_customer = $sales->id_customer;
        $this->address = $sales->address;
        $this->payment_method = $sales->payment_method;
        $this->notes = $sales->notes;
        $this->finish_date = $sales->finish_date;
        $this->sales_person = $sales->sales_person;

        $this->allProducts = Sku::all();
        $this->addresses = Customer::where('id', $this->id_customer)->get();

        $productQty = json_decode($sales->product, true);
        foreach ($productQty as $item) {
            $this->product[] = $item['product'];
            $this->qty[] = $item['qty'];
        }
    }

    public function addNewProduct()
    {
        $this->product[] = null;
        $this->qty[] = null;
    }

    public function removeProduct($index)
    {
        unset($this->product[$index]);
        unset($this->qty[$index]);

        $this->product = array_values($this->product);
        $this->qty = array_values($this->qty);
    }

    public function edit()
    {
        $validate = $this->validate([
            'id_customer' => '|numeric|exists:customer,id',
            'address' => 'string',
            'product' => 'nullable',
            'payment_method' => 'string',
            'notes' => 'string|nullable',
            'finish_date' => 'date|nullable',
            'sales_person' => 'string|nullable',
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

        $sales = \App\Models\Sales::findOrFail($this->id_sales);
        $sales->update($validate);

        return redirect()->route('sales.index')->with('success', 'Sales has been updated.');
    }

    public function updatedIdCustomer($value)
    {
        $this->addresses = Customer::where('id', $value)->get() ?? [];
    }

    public function render()
    {
        return view('livewire.sales.edit-sales', [
            'customers' => Customer::all(),
        ]);
    }
}
