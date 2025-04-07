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
        Schema::table('minutes_of_meeting', function (Blueprint $table) {
            $table->integer('comment_by')->after('comment')->nullable();
            $table->integer('approved_by')->after('comment_by')->nullable();
            $table->timestamp('approved_at')->after('approved_by')->nullable();
            $table->integer('approved_by_2')->after('approved_at')->nullable();
            $table->timestamp('approved_at_2')->after('approved_by_2')->nullable();
            $table->integer('rejected_by')->after('approved_at_2')->nullable();
            $table->timestamp('rejected_at')->after('rejected_by')->nullable();
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
