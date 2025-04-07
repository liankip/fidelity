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
        Schema::table('b_o_q_edits', function (Blueprint $table) {
            $table->enum('category', ['Edit', 'Adendum'])->after('revision')->default('Edit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('b_o_q_edits', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
