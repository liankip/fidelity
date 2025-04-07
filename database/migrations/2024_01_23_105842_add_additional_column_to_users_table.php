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
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('nik')->after('id')->nullable();
            $table->string('position')->after('email')->nullable();
            $table->string('education')->after('position')->nullable();
            $table->string('status')->after('education')->nullable();
            $table->string('gender')->after('status')->nullable();
            $table->string('dob')->after('gender')->nullable();
            $table->string('accepted_date')->after('dob')->nullable();
            $table->string('address')->after('accepted_date')->nullable();
            $table->string('disability')->after('address')->nullable();
            $table->boolean('active')->after('disability')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nik');
            $table->dropColumn('position');
            $table->dropColumn('education');
            $table->dropColumn('status');
            $table->dropColumn('gender');
            $table->dropColumn('dob');
            $table->dropColumn('accepted_date');
            $table->dropColumn('address');
            $table->dropColumn('disability');
            $table->dropColumn('active');
        });
    }
};
