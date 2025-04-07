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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('recommended_by')->nullable()->after('pic');
            $table->string('surveyor_name')->nullable()->after('recommended_by');
            $table->boolean('is_approved')->default(true)->after('surveyor_name');
            $table->foreignId('approved_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropIfExists('recommendation_name');
            $table->dropIfExists('is_approved');
            $table->dropIfExists('surveyor_name');
            $table->dropIfExists('approved_by');
        });
    }
};
