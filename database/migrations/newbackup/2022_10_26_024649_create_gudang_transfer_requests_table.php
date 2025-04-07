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
        Schema::create('gudang_transfer_requests', function (Blueprint $table) {
            $table->id();
            $table->string('gt_no');
            $table->foreignId('from')->constrained('warehouses');
            $table->string('from_pic');
            $table->foreignId('to')->constrained('warehouses');
            $table->string('to_pic');
            $table->string('status')->nullable();
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('gudang_transfer_requests');
        Schema::enableForeignKeyConstraints();
    }
};
