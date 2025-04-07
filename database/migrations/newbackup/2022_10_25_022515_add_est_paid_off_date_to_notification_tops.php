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
        Schema::table('notification_tops', function (Blueprint $table) {
            //
            $table->dateTime('est_paid_off_date')->nullable();
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
        Schema::table('notification_tops', function (Blueprint $table) {
            //
            $table->dropColumn('est_paid_off_date');
        });
        Schema::enableForeignKeyConstraints();
    }
};
