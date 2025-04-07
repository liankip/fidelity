<?php

namespace App\Http\Livewire\Order;

use App\Models\BOQ;
use App\Models\Order as ModelsOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Order extends Component
{
    public $search = '';
    public $project_id;

    protected $queryString = ['search'];

    public function mount($id)
    {
        $this->project_id = $id;
    }

    public function delete($id)
    {
        $order = ModelsOrder::find($id);

        if (!$order) {
            return redirect()
                ->route('order.index', ['id' => $this->project_id])
                ->with('error', 'Order not found.');
        }

        BOQ::where('order_id', $id)->update([
            'deleted_by' => Auth::id(),
        ]);

        BOQ::where('order_id', $id)->delete();

        $order->delete();

        return redirect()
            ->route('order.index', ['id' => $this->project_id])
            ->with('success', 'Order deleted successfully.');
    }

    public function render()
    {
        $order = ModelsOrder::where('number_order', 'like', '%' . $this->search . '%')
            ->where('project_id', $this->project_id)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('livewire.order.order', [
            'order' => $order,
        ]);
    }
}
