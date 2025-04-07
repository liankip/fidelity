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
        Schema::create('po_pr_pivot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('po_id');
            $table->unsignedBigInteger('pr_id');

            $table->foreign('po_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('pr_id')->references('id')->on('purchase_requests')->onDelete('cascade');
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
        Schema::dropIfExists('po_pr_pivot');
    }
};
