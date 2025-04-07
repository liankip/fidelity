<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('b_o_q_spreadsheets', function (Blueprint $table) {
            $table->renameColumn('wbs_id', 'task_id');
            $table->renameColumn('wbs', 'task_number');
            $table->renameColumn('wbs_type', 'is_task');
            $table->dropColumn('job_name');
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
