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
        Schema::table('vouchers', function (Blueprint $table) {
            $table->integer('approved_by')->after('voucher_no')->nullable();
            $table->date('date_approved')->after('approved_by')->nullable();
            $table->integer('rejected_by')->after('date_approved')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->drop('approved_by');
            $table->drop('date_approved');
            $table->drop('rejected_by');
        });
    }
};
