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
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('is_termin');
            $table->enum('type', ['Project', 'Retail', 'Termin', 'undefined'])->default('undefined')->after('additional_informations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->boolean('is_termin')->default(0)->after('additional_informations');
        });
    }
};
