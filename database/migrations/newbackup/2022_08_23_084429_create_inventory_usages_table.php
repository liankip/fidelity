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
        Schema::create('inventory_usages', function (Blueprint $table) {
            $table->id();
            $table->string('iu_no');
            $table->foreignId('iu_id_detail')->constrained('inventory_usage_details');
            $table->foreignId('from')->constrained('warehouses');
            $table->string('from_pic');
            $table->foreignId('to')->constrained('projects');
            $table->string('to_pic');
            $table->foreignId('do_id')->constrained('delivery_orders')->nullable();
            $table->string('status')->nullable();
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('inventory_usages');
        Schema::enableForeignKeyConstraints();
    }
};
