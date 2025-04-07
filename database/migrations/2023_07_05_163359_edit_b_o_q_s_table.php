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
        Schema::table('b_o_q_s', function (Blueprint $table) {
            $table->decimal('price_estimation', 25, 2)->default(0)->after('qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('b_o_q_s', function (Blueprint $table) {
            $table->dropColumn('price_estimation');
        });
    }
};
