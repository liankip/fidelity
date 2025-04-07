<?php

namespace App\Http\Livewire\WorkOrder;

use App\Models\Customer;
use App\Models\Sales;
use App\Models\Sku;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class InsertWorkOrder extends Component
{
    public $number;
    public $id_customer;
    public $allProducts = [];
    public $product = [];
    public $qty;
    public $salesId;
    public $deadline_date;

    public function mount($sales = null)
    {
        $this->salesId = $sales;
        $this->allProducts = Sku::all();

        if($sales !== null) {
            $salesData = Sales::find($sales);
            $this->id_customer = $salesData->id_customer;
            $productData = json_decode($salesData->product, true);

            foreach($productData as $product) {
                $this->product[] = $product['product'];
                $this->qty[] = $product['qty'];
            }
        } else {
            $this->product[] = '';
            $this->qty[] = '';
        }

        $this->generateWorkOrderNumber();
    }

    public function generateWorkOrderNumber()
    {
        $currentYear = date('y');
        $randomNumber = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        $this->number = "WO/{$randomNumber}/{$currentYear}";
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

    public function insert()
    {
        $validate = $this->validate([
            // 'id_customer' => 'required|exists:customer,id',
            'number' => 'nullable',
            'product.*' => 'required',
            'qty.*' => 'required|numeric|min:1',
            'deadline_date' => 'required',
        ], [
            // 'id_customer.required' => 'Nama Customer wajib diisi',
            // 'id_customer.exists' => 'Nama Customer tidak ada',
            'product.*.required' => 'Product wajib diisi',
            'qty.*.required' => 'Quantity wajib diisi',
            'qty.*.numeric' => 'Quantity harus berupa angka',
            'qty.*.min' => 'Quantity minimal 1',
            'deadline_date.required' => 'Deadline Date wajib diisi',
        ]);

        if (count($this->product) !== count(array_unique($this->product))) {
            Throw ValidationException::withMessages([
                'product' => 'Produk tidak boleh duplikat.',
            ]);
        }

        DB::beginTransaction();

        try {
        
        $productQty = [];
        foreach ($this->product as $index => $productId) {
            if (isset($this->qty[$index])) {
                $productQty[] = [
                    'product' => $productId,
                    'qty' => $this->qty[$index],
                ];
            }
        }

        if (!empty($errors)) {
            return back()->withErrors(['product' => implode(', ', $errors)]);
        }

        $validate['product'] = json_encode($productQty);

        \App\Models\WorkOrder::create($validate);

        if($this->salesId !== null) { 
            $sales = Sales::find($this->salesId);
            $sales->product = json_encode($productQty);
            $sales->save();
        }

        DB::commit();
        return redirect(route('work-order.index'));

        } catch (\Exception $e) {
           dd($e);
        }
    }

    public function render()
    {
        return view('livewire.work-order.insert-work-order', [
            'customers' => Customer::all()
        ]);
    }
}
