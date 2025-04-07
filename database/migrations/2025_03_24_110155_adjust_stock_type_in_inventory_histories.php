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
            $table->decimal('stock_before', 10, 1)->change();
            $table->decimal('stock_after', 10, 1)->change();
            $table->decimal('stock_change', 10, 1)->change();
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
            $table->integer('stock_before')->change();
            $table->integer('stock_after')->change();
            $table->integer('stock_change')->change();
        });
    }
};
