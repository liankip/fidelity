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
        Schema::table('vendor_items', function (Blueprint $table) {
            $table->string('certificate')->nullable()->after('item_name')->comment('Certificate File');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_items', function (Blueprint $table) {
            $table->dropIfExists('product_certificate');
        });
    }
};
