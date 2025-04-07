<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PurchaseOrder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        PurchaseOrder::create([
            'pr_no' => '315/PR/'.env("NO_PREFIX").'/PROJECT2/VIII/2022',
            'po_no' => '991/PO/'.env("NO_PREFIX").'/PROJECT1/VIII/2022',
            'payment_id' => '1',
            'payment_metode_id' => '1',
            'project_id' => '2',
            'warehouse_id' => '2',
            'date_request' => now(),
            'do_id' => '2',
            'price_id' => '1',
            'company_id' => '1',
            'status' => 'Wait For Approval',
            'remark' => '',
            'created_by' => '3',
        ]);
        PurchaseOrder::create([
            'pr_no' => '613/PR/'.env("NO_PREFIX").'/PROJECT2/VIII/2022',
            'po_no' => '1234/PO/'.env("NO_PREFIX").'/PROJECT1/VIII/2022',
            'payment_id' => '2',
            'payment_metode_id' => '3',
            'project_id' => '2',
            'warehouse_id' => '2',
            'date_request' => now(),
            'do_id' => '3',
            'price_id' => '3',
            'company_id' => '1',
            'status' => 'Wait For Approval',
            'remark' => '',
            'created_by' => '3',
        ]);
        PurchaseOrder::create([
            'pr_no' => '121/PR/'.env("NO_PREFIX").'/PROJECT1/VIII/2022',
            'po_no' => '666/PO/'.env("NO_PREFIX").'/PROJECT1/VIII/2022',
            'payment_id' => '3',
            'payment_metode_id' => '2',
            'project_id' => '1',
            'warehouse_id' => '1',
            'date_request' => now(),
            'do_id' => '1',
            'price_id' => '1',
            'company_id' => '1',
            'status' => 'Rejected',
            'remark' => 'test',
            'created_by' => '3',
        ]);
    }
}
