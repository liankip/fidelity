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
        Schema::table('work_order', function (Blueprint $table) {
            $table->dropColumn('id_customer');

            $table->date('deadline_date')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_order', function (Blueprint $table) {
            $table->unsignedBigInteger('id_customer')->after('number');

            $table->dropColumn('deadline_date');
        });
    }
};
