<?php

namespace Database\Seeders;
use App\Models\PurchaseRequest;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        PurchaseRequest::create([
            'pr_no' => '566/PR/'.env("APP_URL").'/PROJECT1/VIII/2022',
            'pr_type' => 'Barang',
            'project_id' => '1',
            'warehouse_id' => '1',
            'status' => 'New',
            'remark' => 'Urgent',
            'created_by' => '8',
            'created_by' => '8',
        ]);

        PurchaseRequest::create([
            'pr_no' => '908/PR/'.env("APP_URL").'/PROJECT1/IX/2022',
            'pr_type' => 'Barang',
            'project_id' => '1',
            'warehouse_id' => '2',
            'status' => 'New',
            'remark' => '',
            'created_by' => '8',
        ]);

        PurchaseRequest::create([
            'pr_no' => '411/PR/'.env("APP_URL").'/PROJECT2/VIII/2022',
            'pr_type' => 'Barang',
            'project_id' => '2',
            'warehouse_id' => '2',
            'status' => 'Draft',
            'remark' => 'Urgent',
            'created_by' => '8',
        ]);

        PurchaseRequest::create([
            'pr_no' => '410/PR/'.env("APP_URL").'/PROJECT1/VIII/2022',
            'pr_type' => 'Barang',
            'project_id' => '1',
            'warehouse_id' => '1',
            'status' => 'Approved',
            'remark' => 'Urgent',
            'created_by' => '8',
        ]);
    }
}
