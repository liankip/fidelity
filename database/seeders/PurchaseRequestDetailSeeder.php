<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PurchaseRequestDetail;

class PurchaseRequestDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        PurchaseRequestDetail::create([
            'pr_id' => '3',
            'item_id' => '1',
            'item_name' => 'Item 1',
            'type' => '',
            'unit' => '',
            'qty' => '11',
            'status' => 'Draft',
            'notes' => '',
            'created_by' => '8',
        ]);
        PurchaseRequestDetail::create([
            'pr_id' => '3',
            'item_id' => '2',
            'item_name' => 'Item 2',
            'type' => '',
            'unit' => '',
            'qty' => '15',
            'status' => 'Draft',
            'notes' => '',
            'created_by' => '8',
        ]);
        PurchaseRequestDetail::create([
            'pr_id' => '3',
            'item_id' => '5',
            'item_name' => 'Item 5',
            'type' => '',
            'unit' => '',
            'qty' => '15',
            'status' => 'Draft',
            'notes' => '',
            'created_by' => '8',
        ]);
        PurchaseRequestDetail::create([
            'pr_id' => '3',
            'item_id' => '6',
            'item_name' => 'Item 6',
            'type' => '',
            'unit' => '',
            'qty' => '22',
            'status' => 'Draft',
            'notes' => '',
            'created_by' => '8',
        ]);
        PurchaseRequestDetail::create([
            'pr_id' => '3',
            'item_id' => '8',
            'item_name' => 'Item 8',
            'type' => '',
            'unit' => '',
            'qty' => '5',
            'status' => 'Draft',
            'notes' => '',
            'created_by' => '8',
        ]);
        PurchaseRequestDetail::create([
            'pr_id' => '3',
            'item_id' => '9',
            'item_name' => 'Item 9',
            'type' => '',
            'unit' => '',
            'qty' => '7',
            'status' => 'Draft',
            'notes' => '',
            'created_by' => '8',
        ]);
        PurchaseRequestDetail::create([
            'pr_id' => '3',
            'item_id' => '10',
            'item_name' => 'Item 10',
            'type' => '',
            'unit' => '',
            'qty' => '15',
            'status' => 'Draft',
            'notes' => '',
            'created_by' => '8',
        ]);
        PurchaseRequestDetail::create([
            'pr_id' => '3',
            'item_id' => '11',
            'item_name' => 'Item 11',
            'type' => '',
            'unit' => '',
            'qty' => '2',
            'status' => 'Draft',
            'notes' => '',
            'created_by' => '8',
        ]);
        PurchaseRequestDetail::create([
            'pr_id' => '3',
            'item_id' => '12',
            'item_name' => 'Item 12',
            'type' => '',
            'unit' => '',
            'qty' => '18',
            'status' => 'Draft',
            'notes' => '',
            'created_by' => '8',
        ]);
        PurchaseRequestDetail::create([
            'pr_id' => '4',
            'item_id' => '2',
            'item_name' => 'Item 2',
            'type' => '',
            'unit' => '',
            'qty' => '21',
            'status' => 'Approved',
            'notes' => '',
            'created_by' => '8',
        ]);
        PurchaseRequestDetail::create([
            'pr_id' => '4',
            'item_id' => '3',
            'item_name' => 'Item 3',
            'type' => '',
            'unit' => '',
            'qty' => '10',
            'status' => 'Approved',
            'notes' => '',
            'created_by' => '8',
        ]);
        PurchaseRequestDetail::create([
            'pr_id' => '4',
            'item_id' => '5',
            'item_name' => 'Item 5',
            'type' => '',
            'unit' => '',
            'qty' => '7',
            'status' => 'Approved',
            'notes' => '',
            'created_by' => '8',
        ]);
        PurchaseRequestDetail::create([
            'pr_id' => '4',
            'item_id' => '7',
            'item_name' => 'Item 7',
            'type' => '',
            'unit' => '',
            'qty' => '1',
            'status' => 'Approved',
            'notes' => '',
            'created_by' => '8',
        ]);
        PurchaseRequestDetail::create([
            'pr_id' => '4',
            'item_id' => '12',
            'item_name' => 'Item 12',
            'type' => '',
            'unit' => '',
            'qty' => '15',
            'status' => 'Approved',
            'notes' => '',
            'created_by' => '8',
        ]);
    }
}
