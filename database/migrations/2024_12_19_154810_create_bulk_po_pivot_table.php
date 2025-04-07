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
        Schema::create('bulk_po_pivot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pr_detail_id');
            $table->unsignedBigInteger('po_detail_id');

            $table->foreign('pr_detail_id')->references('id')->on('purchase_request_details')->onDelete('cascade');
            $table->foreign('po_detail_id')->references('id')->on('purchase_order_details')->onDelete('cascade');
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
        Schema::dropIfExists('bulk_po_pivot');
    }
};
