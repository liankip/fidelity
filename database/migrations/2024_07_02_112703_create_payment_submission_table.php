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
        Schema::create('payment_submission', function (Blueprint $table) {
            $table->id();
            $table->string('no_payment_submission');
            $table->enum('type', ['Project', 'Retail','Termin', 'undefined'])->default('undefined');
            $table->enum('status', ['Draft', 'Waiting for approval', 'Approved'])->default('Waiting for approval');
            $table->string('approved_by')->nullable();
            $table->date('date_approved')->nullable();
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
        Schema::dropIfExists('payment_submission');
    }
};
