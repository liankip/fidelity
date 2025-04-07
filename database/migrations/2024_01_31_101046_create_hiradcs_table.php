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
        Schema::create('hiradcs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('document_number');
            $table->date('effective_date')->nullable();
            $table->integer('revision_number')->nullable();
            $table->date('reviewed_date')->nullable();
            $table->string('next_reviewed')->nullable();
            $table->string('dept');
            $table->string('work_unit');
            $table->string('area');
            $table->bigInteger('created_by');
            $table->bigInteger('checked_by')->nullable();
            $table->bigInteger('approved_by')->nullable();
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
        Schema::dropIfExists('hiradcs');
    }
};
