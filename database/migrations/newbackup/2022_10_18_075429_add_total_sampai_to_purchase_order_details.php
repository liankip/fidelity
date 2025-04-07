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
        Schema::table('purchase_order_details', function (Blueprint $table) {
            //
            $table->integer('total_sampai')->default(0);
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
        Schema::table('purchase_order_details', function (Blueprint $table) {
            //
            $table->dropColumn('total_sampai');
        });
        Schema::enableForeignKeyConstraints();
    }
};
