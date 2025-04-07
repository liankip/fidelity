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
            $table->dropColumn('grosir_price');
            $table->dropColumn('distributor_price');
            $table->dropColumn('msrp_price');

            $table->bigInteger('total_modal_price')->default(0);
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
            $table->bigInteger('grosir_price')->default(0);
            $table->bigInteger('distributor_price')->default(0);
            $table->bigInteger('msrp_price')->default(0);

            $table->dropColumn('total_modal_price');
        });
    }
};
