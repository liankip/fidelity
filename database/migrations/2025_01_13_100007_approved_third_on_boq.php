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
            $table->string('approved_by_3')->nullable()->after('approved_by_2');
            $table->timestamp('date_approved_3')->nullable()->after('approved_by_3');
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
            $table->dropColumn('approved_by_3');
            $table->dropColumn('date_approve_3');
        });
    }
};
