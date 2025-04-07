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
        Schema::table('task_engineer_drawing', function (Blueprint $table) {
            $table->text('description')->nullable()->after('status_uploaded');
            $table->integer('section')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_engineer_drawing', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('section');
        });
    }
};
