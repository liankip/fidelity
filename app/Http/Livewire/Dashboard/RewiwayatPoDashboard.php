<?php

namespace App\Http\Livewire\Dashboard;

use App\Constants\PurchaseOrderStatus;
use App\Models\PurchaseOrder;
use Livewire\Component;

class RewiwayatPoDashboard extends Component
{
    public $po;
    public function render()
    {

        $this->po = PurchaseOrder::where(function ($query) {
            $query->where("status", PurchaseOrderStatus::APPROVED)
                ->orWhere("status", PurchaseOrderStatus::PAID)
                ->orWhere("status", PurchaseOrderStatus::NEED_TO_PAY)
                ->orWhere("status", PurchaseOrderStatus::PARTIALLY_PAID);
        })->orderBy("approved_at","DESC")->take(10)->get();

        // $startDate = Carbon::now()->subWeek()->startOfWeek();
        // $endDate = Carbon::now()->subWeek()->endOfWeek();
        // $this->po = PurchaseOrder::whereBetween('created_at', [$startDate, $endDate])->where(function ($query) {
        //     $query->where("status", "Approved")
        //         ->orWhere("status", "wait for Approval")
        //         ->orWhere("status", "Paid")
        //         ->orWhere("status", "Need to Pay")
        //         ->orWhere("status", "Partially Paid");
        // })->orderBy("approved_at","DESC")->take(10)->get();
        return view('livewire.dashboard.rewiwayat-po-dashboard');
    }
    public function refresh()
    {
        return;
    }
}
