<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actual_field_inventory', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pr_id')->unsigned();
            $table->bigInteger('po_detail_id')->unsigned();
            $table->bigInteger('item_id')->unsigned();
            $table->integer('qty_actual');
            $table->foreign('pr_id')->references('id')->on('purchase_requests');
            $table->foreign('po_detail_id')->references('id')->on('purchase_order_details');
            $table->foreign('item_id')->references('id')->on('items');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actual_field_inventory');
    }
};
