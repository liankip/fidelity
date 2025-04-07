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
        Schema::create('capex_expense_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('capex_expense_id')->constrained();
            $table->date('purchase_date');
            $table->integer('total_expense');
            $table->text('notes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('capex_expense_items');
    }
};
