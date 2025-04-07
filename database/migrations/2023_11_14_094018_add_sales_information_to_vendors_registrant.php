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
        Schema::table('vendor_registrants', function (Blueprint $table) {
            $table->string('sales_email')->nullable();
            $table->string('sales_phone')->nullable();
            $table->string('npwp_image')->nullable();
            $table->string('npwp')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_registrants', function (Blueprint $table) {
            //
        });
    }
};
