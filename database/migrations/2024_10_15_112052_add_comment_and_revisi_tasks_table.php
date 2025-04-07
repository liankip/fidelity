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
        Schema::table('tasks', function (Blueprint $table) {
            $table->text('comment')->nullable();
            $table->boolean('revision')->nullable();
            $table->unsignedBigInteger('revision_by_user_1')->nullable();
            $table->date('revision_date_user_1')->nullable();
            $table->unsignedBigInteger('revision_by_user_2')->nullable();
            $table->date('revision_date_user_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task', function (Blueprint $table) {
            //
        });
    }
};
