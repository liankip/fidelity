<?php

namespace App\Http\Livewire\Order;

use App\Models\BOQ;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;

class EditOrder extends Component
{
    public $project_id;
    public $order_id;
    public $quantity;

    public function mount($id, $order)
    {
        $this->project_id = $id;
        $this->order_id = $order;

        $order = Order::find($this->order_id);

        $this->quantity = $order->quantity;
    }

    public function edit()
    {
        $this->validate(
            [
                'quantity' => 'required|numeric|min:1',
            ],
            [
                'quantity.required' => 'Quantity harus diisi.',
                'quantity.numeric' => 'Quantity harus berupa angka.',
                'quantity.min' => 'Quantity minimal 1',
            ],
        );

        if (!$this->project_id) {
            session()->flash('error', 'Project ID is required.');
            return;
        }

        $order = Order::find($this->order_id);
        $product = Product::find($order->product_id);
        $order->quantity = $this->quantity;
        $order->save();

        if ($product) {
            $data = json_decode($product->data, true);

            foreach ($data as $d) {
                $totalQty = $d[3];

                if (isset($d[3])) {
                    $totalQty *= $this->quantity;
                }

                BOQ::where('order_id', $this->order_id)
                    ->where('item_id', $d[0])
                    ->update([
                        'qty' => $totalQty,
                    ]);
            }
        }

        return redirect()
            ->route('order.index', ['id' => $this->project_id])
            ->with('success', 'Order updated successfully.');
    }

    public function render()
    {
        return view('livewire.order.edit-order', [
            'order' => Order::find($this->order_id),
        ]);
    }
}
