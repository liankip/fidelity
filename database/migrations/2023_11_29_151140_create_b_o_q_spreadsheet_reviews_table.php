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
        Schema::create('b_o_q_spreadsheet_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('b_o_q_spreadsheet_id')->constrained('b_o_q_spreadsheets')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->constrained('users')->cascadeOnDelete();
            $table->json('data');
            $table->string('notes')->nullable();
            $table->integer('revision')->default(0);
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
        Schema::dropIfExists('b_o_q_spreadsheet_reviews');
    }
};
