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
        Schema::table('inventory_usage_details', function (Blueprint $table) {
            //
            $table->integer('asset_status')->default(0);

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
        Schema::table('inventory_usage_details', function (Blueprint $table) {
            //
            $table->dropColumn('asset_status');
        });
        Schema::enableForeignKeyConstraints();
    }
};
