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
        Schema::table('b_o_q_spreadsheets', function (Blueprint $table) {
            $table->unsignedBigInteger('approved_by')->after('data')->nullable();
            $table->date('date_approved')->after('approved_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->after('date_approved')->nullable();
            $table->date('date_rejected')->after('rejected_by')->nullable();
            $table->unsignedBigInteger('approved_by_2')->after('date_rejected')->nullable();
            $table->date('date_approved_2')->after('approved_by_2')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('b_o_q_spreadsheets', function (Blueprint $table) {
            $table->drop('approved_by');
            $table->drop('date_approved');
            $table->drop('rejected_by');
            $table->drop('date_rejected');
            $table->drop('approved_by_2');
            $table->drop('date_approved_2');
        });
    }
};
