<?php

namespace App\Http\Livewire\Order;

use App\Models\BOQ;
use App\Models\HistoryPurchase;
use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\User;
use App\Roles\Role;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PurchaseRequestCreated;
use Exception;

class MonitoringOrder extends Component
{
    use WithPagination;

    public $project_id;
    public $order_id;
    public $project;
    public $boqs;
    public $boqsArray;
    public $sortBy, $search;
    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';

    public function mount($id, $order)
    {
        $this->project_id = $id;
        $this->order_id = $order;
    }

    public function createPR()
    {
        $arrtodb = [];

        try {
            foreach ($this->userarray as $value) {
                array_push($arrtodb, $value['id']);
            }

            $currentuser = Auth::user();

            $purchaserequest = PurchaseRequest::create([
                'pr_no' => null,
                'pr_type' => $this->type,
                'project_id' => $this->project->id,
                'partof' => 'retail',
                'is_task' => 0,
                'warehouse_id' => 0,
                'requester' => $this->requester,
                'status' => 'Draft',
                'remark' => $this->remark,
                'city' => 'Jakarta',
                'created_by' => $currentuser->id,
            ]);

            HistoryPurchase::create([
                'action_start' => 'New Draft PR',
                'action_end' => 'New Draft PR',
                'referensi' => null,
                'action_by' => $currentuser->id,
                'created_by' => $currentuser->id,
                'action_date' => Carbon::now(),
                'created_at' => Carbon::now(),
            ]);

            $purches = User::role([Role::PURCHASING, Role::IT])->get();

            foreach ($purches as $pur) {
                Notification::send(
                    $pur,
                    new PurchaseRequestCreated([
                        'pr_no' => $purchaserequest->pr_no,
                        'pr_detail' => $purchaserequest->id,
                        'created_by' => $currentuser->name,
                    ]),
                );
            }

            return redirect()
                ->to('/itemprindex/' . $purchaserequest->id . '?firstcreate=yes')
                ->with('success', 'Purchase Request Destination has been created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function render()
    {
        $boqItems = BOQ::where('order_id', $this->order_id)
            ->where('project_id', $this->project_id)
            ->where(function ($query) {
                $query->orWhereHas('item', function ($itemQuery) {
                    $itemQuery->where('name', 'like', '%' . $this->search . '%');
                });
                $query->orWhereHas('unit', function ($unitQuery) {
                    $unitQuery->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(10);

        $totalPriceEstimation = $boqItems->sum(function ($b) {
            return $b->price_estimation * $b->qty + $b->shipping_cost;
        });

        return view('livewire.order.monitoring-order', [
            'boqList' => $boqItems,
            'totalPriceEstimation' => $totalPriceEstimation,
        ]);
    }
}
