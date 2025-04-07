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
        Schema::table('inventories', function (Blueprint $table) {
            $table->string('project_id')->nullable()->after('id');
            $table->string('task_id')->nullable()->after('project_id');
            $table->string('new_task_id')->nullable()->after('stock');
            $table->integer('actual_qty')->nullable()->after('new_task_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn(['project_id', 'task_id', 'new_task_id', 'actual_qty']);
        });
    }
};
