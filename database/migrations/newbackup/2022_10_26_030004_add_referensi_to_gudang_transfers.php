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
        Schema::table('gudang_transfers', function (Blueprint $table) {
            //
            $table->string('referensi')->default(0);
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
        Schema::table('gudang_transfers', function (Blueprint $table) {
            //
            $table->dropColumn('referensi');
        });
        Schema::enableForeignKeyConstraints();
    }
};
