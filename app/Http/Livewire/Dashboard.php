<?php

namespace App\Http\Livewire;

use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{

    public $total_pr, $total_pr_new, $total_pr_process, $total_pr_cancel;
    public $total_po_new, $total_po_approved, $total_po_arrived, $total_po_w_approved;
    public $total_po_w_payment, $total_po_cancelled, $total_po_rejected, $total_po_thisyear, $total_pr_thisyear;
    public $poPerMonth;

    public function mount()
    {
        $year = date('Y');
        $connectionName = DB::connection()->getName();
        $rawQuery = "";

        if ($connectionName == 'mysql') {
            $rawQuery = 'DATE_FORMAT(purchase_order_details.created_at, "%m") as month_number, DATE_FORMAT(purchase_order_details.created_at, "%M") as month, SUM(purchase_order_details.price) as total_price';
        } else if ($connectionName == 'pgsql') {
            $rawQuery = "EXTRACT(MONTH FROM purchase_order_details.created_at) as month_number, TO_CHAR(purchase_order_details.created_at, 'Month') as month, SUM(purchase_order_details.price) as total_price";
        }

        $year = Carbon::now()->year;

        // Generate a sequence of months from January to December
        $months = collect(range(1, 12))->map(function ($month) use ($year) {
            return Carbon::create($year, $month, 1)->format('F');
        });

        // Your original query
        $totalPricesPerMonth = DB::table('purchase_order_details')
            ->join('purchase_orders', 'purchase_order_details.purchase_order_id', '=', 'purchase_orders.id')
            ->selectRaw($rawQuery)
            ->whereYear('purchase_order_details.created_at', $year)
            ->whereNotIn('purchase_orders.status', ['Cancel', 'Draft', 'Rejected'])
            ->groupBy('month_number', 'month')
            ->orderBy('month_number')
            ->get();

        // Merge the original results with zero values for missing months
        $mergedResults = $months->map(function ($monthName, $monthNumber) use ($totalPricesPerMonth) {
            $found = $totalPricesPerMonth->first(function ($item) use ($monthName) {
                return $item->month == $monthName;
            });

            return $found ?: (object) ['month' => $monthName, 'month_number' => $monthNumber + 1, 'total' => 0];
        });

        $this->poPerMonth = $mergedResults;
    }
    public function render()
    {
        // purchase request
        $this->total_pr = PurchaseRequest::count();

        $this->total_pr_new = PurchaseRequest::where('status', 'New')->count();
        $this->total_pr_process = PurchaseRequest::where('status', 'Draft')->count();
        $this->total_pr_cancel = PurchaseRequest::where('status', 'Cancel')->count();

        // purchase order
        $this->total_po_new = PurchaseOrder::where('status', 'New')->count();
        $this->total_po_approved = PurchaseOrder::where('status', 'Approved')->count();
        $this->total_po_arrived = PurchaseOrder::where('status', 'Arrived')->count();
        $this->total_po_w_approved = PurchaseOrder::where('status', 'Wait For Approval')->count();
        $this->total_po_w_payment = PurchaseOrder::where('status', 'Waiting For Payment')->count();
        $this->total_po_cancelled = PurchaseOrder::where('status', 'Cancel')->count();
        $this->total_po_rejected = PurchaseOrder::where('status', 'Rejected')->count();

        $this->total_po_thisyear = PurchaseOrder::count();

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://telegram-bot.satrianusa.group/api/telegram/update-attendance',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Accept: 7qcX3AuK5mm23KW5TiKCGkDMKkj1354027i4y38HTxUCyCqpBz4c27kAhjBS74837O7QesJEW41uVBFqL00s4O66z1F9KzCL7M7kDduayQ7q83OSZm758EKE'
                ),
            )
        );

        $curl_attendance = json_decode(curl_exec($curl), true);
        curl_close($curl);

        $ontime = 0;
        $late = 0;
        $permission = 0;
        $not_fill = 0;

        if (isset($curl_attendance['data'])) {
            foreach ($curl_attendance['data'] as $entry) {
                if ($entry['status'] === 'ontime') {
                    $ontime++;
                }
                if ($entry['status'] === 'late') {
                    $late++;
                }
                if ($entry['status'] === 'permission') {
                    $permission++;
                }
                if ($entry['status'] === 'not_fill') {
                    $not_fill++;
                }
            }
        }

        $projects = Project::where('status', 'On going')->get();

        return view('livewire.dashboard', [
            'ontime' => $ontime,
            'late' => $late,
            'permission' => $permission,
            'not_fill' => $not_fill,
            'projects' => $projects
        ]);
    }
}
