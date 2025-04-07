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
        Schema::table('suppliers', function (Blueprint $table) {
            //
            $table->integer("blacklist");
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
        Schema::table('suppliers', function (Blueprint $table) {
            //Schema::dropIfExists('prices');
            $table->dropColumn('blacklist');
        });
        Schema::enableForeignKeyConstraints();
    }
};
