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
        Schema::table('inventory_outs', function (Blueprint $table) {
            $table->unsignedBigInteger('inventory_detail_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_outs', function (Blueprint $table) {
            $table->unsignedBigInteger('inventory_detail_id')->change();
        });
    }
};
