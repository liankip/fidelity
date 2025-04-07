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
        Schema::table('sku', function (Blueprint $table) {
            $table->renameColumn('nama', 'name');
            $table->renameColumn('data', 'boq');
            $table->integer('grosir_price')->nullable()->after('data');
            $table->integer('distributor_price')->nullable()->after('grosir_price');
            $table->integer('msrp_price')->nullable()->after('distributor_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sku', function (Blueprint $table) {
            //
        });
    }
};
