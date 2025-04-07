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
        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('pic')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('province')->nullable()->change();
            $table->string('post_code')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('pic')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('province')->nullable(false)->change();
            $table->string('post_code')->nullable(false)->change();
        });
    }
};
