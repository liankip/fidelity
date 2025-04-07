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
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('notes');
            $table->integer('approved_by')->nullable()->after('status');
            $table->date('approved_date')->nullable()->after('approved_by');
            $table->integer('rejected_by')->nullable()->after('approved_date');
            $table->date('rejected_date')->nullable()->after('rejected_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
