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
        Schema::table('inventory_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('work_order_id')->nullable()->after('podetail_id');

            $table->foreign('work_order_id')->references('id')->on('work_order')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_histories', function (Blueprint $table) {
            $table->dropForeign(['work_order_id']);
            $table->dropColumn('work_order_id');
        });
    }
};
