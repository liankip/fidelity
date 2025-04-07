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
        Schema::create('returs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retur_id_detail')->constrained('retur_details');
            $table->foreignId('do_id')->constrained('delivery_orders')->nullable();
            $table->foreignId('po_id')->constrained('purchase_orders');
            $table->string('po_no');
            $table->foreignId('from')->constrained('warehouses');
            $table->string('from_pic');
            $table->foreignId('to')->constrained('vendors');
            $table->string('to_pic');
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
        Schema::dropIfExists('returs');
        Schema::enableForeignKeyConstraints();
    }
};
