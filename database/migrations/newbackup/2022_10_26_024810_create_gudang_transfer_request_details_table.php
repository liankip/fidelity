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
        Schema::create('gudang_transfer_request_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gtr_id')->constrained('gudang_transfer_requests');
            $table->foreignId('item_id')->constrained('items');
            $table->string('item_name');
            $table->string('type');
            $table->string('unit');
            $table->integer('qty');
            $table->string('status')->nullable();
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
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
        Schema::dropIfExists('gudang_transfer_request_details');
        Schema::enableForeignKeyConstraints();
    }
};
