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
        Schema::rename('task_approval', 'task_file_path');
        Schema::table('task_file_path', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('approved_by_user_1');
            $table->dropColumn('approved_date_user_1');
            $table->dropColumn('approved_by_user_2');
            $table->dropColumn('approved_date_user_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_approval', function (Blueprint $table) {
            //
        });
    }
};
