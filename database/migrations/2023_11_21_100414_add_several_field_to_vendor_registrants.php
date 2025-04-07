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
            $table->string('website_link')->nullable();
            $table->string('company_profile')->nullable();
            $table->string('product_catalogue')->nullable();
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
            $table->dropIfExists('website_link');
            $table->dropIfExists('company_profile');
            $table->dropIfExists('product_catalogue');
        });
    }
};
