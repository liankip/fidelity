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
        Schema::table('office_expense_items', function (Blueprint $table) {
            $table->string('receiver_name', 100)->nullable()->after('total_expense');
            $table->string('vendor', 50)->nullable()->after('receiver_name');
            $table->string('account_number', 30)->nullable()->after('vendor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('office_expense_items', function (Blueprint $table) {
            //
        });
    }
};
