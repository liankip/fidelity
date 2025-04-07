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
        Schema::table('delivery_services', function (Blueprint $table) {
            //
            $table->Integer('tarif_per_kg');
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
        Schema::table('delivery_services', function (Blueprint $table) {
            //
            $table->dropColumn('tarif_per_kg');
        });
        Schema::enableForeignKeyConstraints();
    }
};
