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
        Schema::create('sales', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_customer')->nullable();
            $table->text('address')->nullable();
            $table->text('product')->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->text('notes')->nullable();
            $table->date('finish_date')->nullable();
            $table->string('sales_person', 100)->nullable();
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
        Schema::table('sales', function (Blueprint $table) {
            //
        });
    }
};
