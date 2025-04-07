<?php

namespace App\Http\Livewire\Order;

use App\Models\BOQ;
use App\Models\Order;
use App\Models\Product;
use App\Models\Unit;
use Livewire\Component;

class InsertOrder extends Component
{
    public $project_id;
    public $product;
    public $quantity;

    public function mount($id)
    {
        $this->project_id = $id;
    }

    public function insert()
    {
        $this->validate(
            [
                'product' => 'required',
                'quantity' => 'required|numeric|min:1',
            ],
            [
                'product.required' => 'Produk harus dipilih.',
                'quantity.required' => 'Quantity harus diisi.',
                'quantity.numeric' => 'Quantity harus berupa angka.',
                'quantity.min' => 'Quantity minimal 1',
            ],
        );

        if (!$this->project_id) {
            session()->flash('error', 'Project ID is required.');
            return;
        }

        $product = Product::find($this->product);

        $order = new Order();
        $order->number_order = 'ORD-' . date('YmdHis');
        $order->project_id = $this->project_id;
        $order->product_id = $this->product;
        $order->quantity = $this->quantity;
        $order->save();

        if ($product) {
            $data = json_decode($product->data, true);

            foreach ($data as $d) {
                $totalQty = $d[3];

                if (isset($d[3])) {
                    $totalQty *= $this->quantity;
                }

                $unit = Unit::where('name', $d[1])->first();

                $boq = new BOQ();
                $boq->no_boq = 3;
                $boq->project_id = $this->project_id;
                $boq->item_id = $d[0];
                $boq->unit_id = $unit->id;
                $boq->qty = $totalQty;
                $boq->price_estimation = $d[2];
                $boq->shipping_cost = 0.0;
                $boq->origin = null;
                $boq->destination = null;
                $boq->note = "";
                $boq->revision = 0.0;
                $boq->order_id = $order->id;

                $boq->save();
            }

            return redirect()->route('order.index', ['id' => $this->project_id]);
        } else {
            session()->flash('error', 'Product not found.');
        }
    }

    public function render()
    {
        return view('livewire.order.insert-order', [
            'products' => Product::all(),
        ]);
    }
}
