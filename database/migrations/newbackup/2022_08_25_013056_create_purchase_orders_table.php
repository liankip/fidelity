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
        Schema::disableForeignKeyConstraints();
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('pr_no');
            $table->string('po_no');
            $table->Integer('payment_id');
            $table->string('term_of_payment');
            $table->foreignId('project_id');
            $table->foreignId('warehouse_id');
            $table->string('date_request');
            $table->foreignId('do_id');
            // $table->foreignId('vendor_id')->constrained('vendors');
            $table->foreignId('supplier_id');
            $table->foreignId('company_id');
            $table->foreignId('delivery_service_id');
            $table->dateTime('top_date');
            $table->string('status')->nullable();
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('purchase_orders');
        Schema::enableForeignKeyConstraints();
    }
};
