<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PurchaseOrderDetail;

class PurchaseOrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        PurchaseOrderDetail::create([
            'pr_id_detail' => '1',
            'po_id' => '3',
            'item_id' => '1',
            'item_name' => 'Item 1',
            'type' => '',
            'unit' => '',
            'qty' => '11',
            'price' => '1000',
            'tax' => '0',
            'amount' => '11000',
            'item_pict' => '',
            'status' => 'New',
            'notes' => '',
            'created_by' => '4',


        ]);
        PurchaseOrderDetail::create([
            'pr_id_detail' => '2',
            'po_id' => '3',
            'item_id' => '2',
            'item_name' => 'Item 2',
            'type' => '',
            'unit' => '',
            'qty' => '15',
            'price' => '1000',
            'tax' => '0',
            'amount' => '15000',
            'item_pict' => '',
            'status' => 'New',
            'notes' => '',
            'created_by' => '4',


        ]);
        PurchaseOrderDetail::create([
            'pr_id_detail' => '3',
            'po_id' => '1',
            'item_id' => '5',
            'item_name' => 'Item 5',
            'type' => '',
            'unit' => '',
            'qty' => '15',
            'price' => '1000',
            'tax' => '0',
            'amount' => '15000',
            'item_pict' => '',
            'status' => 'New',
            'notes' => '',
            'created_by' => '4',


        ]);
        PurchaseOrderDetail::create([
            'pr_id_detail' => '4',
            'po_id' => '3',
            'item_id' => '6',
            'item_name' => 'Item 6',
            'type' => '',
            'unit' => '',
            'qty' => '22',
            'price' => '1000',
            'tax' => '0',
            'amount' => '22000',
            'item_pict' => '',
            'status' => 'New',
            'notes' => '',
            'created_by' => '4',


        ]);
        PurchaseOrderDetail::create([
            'pr_id_detail' => '5',
            'po_id' => '3',
            'item_id' => '8',
            'item_name' => 'Item 8',
            'type' => '',
            'unit' => '',
            'qty' => '5',
            'price' => '1000',
            'tax' => '0',
            'amount' => '5000',
            'item_pict' => '',
            'status' => 'New',
            'notes' => '',
            'created_by' => '4',


        ]);
        PurchaseOrderDetail::create([
            'pr_id_detail' => '6',
            'po_id' => '4',
            'item_id' => '9',
            'item_name' => 'Item 9',
            'type' => '',
            'unit' => '',
            'qty' => '7',
            'price' => '1000',
            'tax' => '0',
            'amount' => '7000',
            'item_pict' => '',
            'status' => 'New',
            'notes' => '',
            'created_by' => '4',


        ]);
        PurchaseOrderDetail::create([
            'pr_id_detail' => '7',
            'po_id' => '4',
            'item_id' => '10',
            'item_name' => 'Item 10',
            'type' => '',
            'unit' => '',
            'qty' => '15',
            'price' => '1000',
            'tax' => '0',
            'amount' => '15000',
            'item_pict' => '',
            'status' => 'New',
            'notes' => '',
            'created_by' => '4',


        ]);
        PurchaseOrderDetail::create([
            'pr_id_detail' => '8',
            'po_id' => '4',
            'item_id' => '11',
            'item_name' => 'Item 11',
            'type' => '',
            'unit' => '',
            'qty' => '2',
            'price' => '1000',
            'tax' => '0',
            'amount' => '2000',
            'item_pict' => '',
            'status' => 'New',
            'notes' => '',
            'created_by' => '4',


        ]);
        PurchaseOrderDetail::create([
            'pr_id_detail' => '9',
            'po_id' => '4',
            'item_id' => '12',
            'item_name' => 'Item 12',
            'type' => '',
            'unit' => '',
            'qty' => '18',
            'price' => '1000',
            'tax' => '0',
            'amount' => '18000',
            'item_pict' => '',
            'status' => 'New',
            'notes' => '',
            'created_by' => '4',


        ]);
        PurchaseOrderDetail::create([
            'pr_id_detail' => '10',
            'po_id' => '5',
            'item_id' => '2',
            'item_name' => 'Item 2',
            'type' => '',
            'unit' => '',
            'qty' => '21',
            'price' => '1000',
            'tax' => '0',
            'amount' => '21000',
            'item_pict' => '',
            'status' => 'Approved',
            'notes' => '',
            'created_by' => '4',
            'approved_by' => '2',

        ]);
        PurchaseOrderDetail::create([
            'pr_id_detail' => '11',
            'po_id' => '5',
            'item_id' => '3',
            'item_name' => 'Item 3',
            'type' => '',
            'unit' => '',
            'qty' => '10',
            'price' => '1000',
            'tax' => '0',
            'amount' => '10000',
            'item_pict' => '',
            'status' => 'Approved',
            'notes' => '',
            'created_by' => '4',
            'approved_by' => '2',

        ]);
        PurchaseOrderDetail::create([
            'pr_id_detail' => '12',
            'po_id' => '5',
            'item_id' => '5',
            'item_name' => 'Item 5',
            'type' => '',
            'unit' => '',
            'qty' => '7',
            'price' => '1000',
            'tax' => '0',
            'amount' => '7000',
            'item_pict' => '',
            'status' => 'Approved',
            'notes' => '',
            'created_by' => '4',
            'approved_by' => '2',

        ]);
        PurchaseOrderDetail::create([
            'pr_id_detail' => '13',
            'po_id' => '5',
            'item_id' => '7',
            'item_name' => 'Item 7',
            'type' => '',
            'unit' => '',
            'qty' => '1',
            'price' => '1000',
            'tax' => '0',
            'amount' => '1000',
            'item_pict' => '',
            'status' => 'Approved',
            'notes' => '',
            'created_by' => '4',
            'approved_by' => '2',

        ]);
        PurchaseOrderDetail::create([
            'pr_id_detail' => '14',
            'po_id' => '5',
            'item_id' => '12',
            'item_name' => 'Item 12',
            'type' => '',
            'unit' => '',
            'qty' => '15',
            'price' => '1000',
            'tax' => '0',
            'amount' => '15000',
            'item_pict' => '',
            'status' => 'Approved',
            'notes' => '',
            'created_by' => '4',
            'approved_by' => '2',

        ]);
    }
}
