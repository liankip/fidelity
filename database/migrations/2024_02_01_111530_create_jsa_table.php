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
        Schema::create('jsa', function (Blueprint $table) {
            $table->id();
            $table->string('no_jsa')->unique();
            $table->integer('job_no');
            $table->string('job_name');
            $table->string('position_no')->nullable();
            $table->string('position_name')->nullable();
            $table->string('section_department')->nullable();
            $table->string('superior_position')->nullable();
            $table->date('jsa_date');
            $table->integer('arranged_by')->nullable();
            $table->integer('checked_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('revision_num')->nullable();
            $table->string('reviewed')->nullable();
            $table->string('suggestion_notes')->length(500)->nullable();
            $table->string('job_location')->nullable();
            $table->json('details_data')->nullable();
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jsa');
    }
};
