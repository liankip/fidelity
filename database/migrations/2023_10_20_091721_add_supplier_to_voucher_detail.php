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
        Schema::table('voucher_details', function (Blueprint $table) {
            $table->foreignId('supplier_id')->after('voucher_id');
            $table->foreignId('project_id')->after('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voucher_details', function (Blueprint $table) {
            $table->dropColumn(['supplier_id', 'project_id']);
        });
    }
};
