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
        Schema::table('site_check_upload', function (Blueprint $table) {
            $table->dropForeign(['pr_id']);

            $table->foreign('pr_id')->references('id')->on('purchase_request_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_check_upload', function (Blueprint $table) {
            $table->dropForeign(['pr_id']);

            $table->foreign('pr_id')->references('id')->on('purchase_requests')->onDelete('cascade');
        });
    }
};
