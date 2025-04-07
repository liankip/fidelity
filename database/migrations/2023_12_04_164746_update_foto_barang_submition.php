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
        Schema::table('submition_histories', function (Blueprint $table) {
            $table->string('foto_left')->nullable()->change();
            $table->string('foto_right')->nullable()->change();
            $table->string('foto_back')->nullable()->change();
            $table->string('foto_barang')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submition_histories', function (Blueprint $table) {
            //
        });
    }
};
