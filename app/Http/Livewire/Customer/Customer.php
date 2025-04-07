<?php

namespace App\Http\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer as ModelCustomer;

class Customer extends Component
{
    public $search = '';

    protected $queryString = ['search'];

    public function delete($id)
    {
        ModelCustomer::findOrFail($id)->delete();

        return redirect()->route('customer.index')->with('success', 'Customer deleted successfully');
    }

    public function render()
    {
        $customer = ModelCustomer::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('livewire.customer.customer', [
            'customer' => $customer,
        ]);
    }
}
