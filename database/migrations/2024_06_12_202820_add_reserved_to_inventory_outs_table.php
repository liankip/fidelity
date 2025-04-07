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
        Schema::table('inventory_outs', function (Blueprint $table) {
            $table->string('owner_id')->nullable()->after('user_id');
            $table->enum('reserved', ['false','true'])->default('false')->after('desc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_outs', function (Blueprint $table) {
            $table->dropColumn('owner_id');
            $table->dropColumn('reserved');
        });
    }
};
