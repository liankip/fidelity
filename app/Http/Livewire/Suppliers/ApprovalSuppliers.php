<?php

namespace App\Http\Livewire\Suppliers;

use App\Models\Supplier;
use Livewire\Component;

class ApprovalSuppliers extends Component
{
    public $suppliers;

    public function render()
    {
        return view('livewire.suppliers.approval-suppliers');
    }

    public function approve($id)
    {
        $supplier = Supplier::find($id);
        $supplier->is_approved = true;
        $supplier->approved_by = auth()->user()->id;
        $supplier->save();

        return redirect()->route('suppliers.index', ['tab' => 'need-approval'])->with('success', 'Supplier approved successfully');
    }

    public function reject($id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();

        return redirect()->route('suppliers.index', ['tab' => 'need-approval'])->with('success', 'Supplier rejected successfully');
    }
}
