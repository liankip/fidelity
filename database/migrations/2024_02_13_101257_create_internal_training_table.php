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
        Schema::create('internal_training', function (Blueprint $table) {
            $table->id();
            $table->integer('id_no');
            $table->string('no_doc')->nullable();
            $table->string('aspect_name');
            $table->string('risk_effect');
            $table->string('program_plan');
            $table->string('plan')->nullable();
            $table->string('realization')->nullable();
            $table->string('notes')->nullable();
            $table->integer('revision');
            $table->integer('arranged_by')->nullable();
            $table->integer('approved_by')->nullable();
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
        Schema::dropIfExists('internal_trainings');
    }
};
