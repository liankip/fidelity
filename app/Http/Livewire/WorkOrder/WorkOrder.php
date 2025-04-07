<?php

namespace App\Http\Livewire\WorkOrder;

use Livewire\Component;

class WorkOrder extends Component
{
    public $search = '';

    protected $queryString = ['search'];

    public function render()
    {
        $workOrder = \App\Models\WorkOrder::where('number', 'LIKE', '%' . $this->search . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('livewire.work-order.work-order', [
            'workOrder' => $workOrder
        ]);
    }
}
