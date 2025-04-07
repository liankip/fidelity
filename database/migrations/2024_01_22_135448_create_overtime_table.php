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
        Schema::create('overtime', function (Blueprint $table) {
            $table->id();
            $table->integer('overtime_id');
            $table->integer('user_id');
            $table->integer('project_id');
            $table->date('overtime_date');
            $table->time('start_time');
            $table->time('finish_time');
            $table->string('overtime_report');
            $table->integer('est_cost');
            $table->string('assigned_by');
            $table->string('status')->nullable();
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
        Schema::dropIfExists('overtime');
    }
};
