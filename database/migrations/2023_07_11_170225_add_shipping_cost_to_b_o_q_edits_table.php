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
        Schema::table('b_o_q_edits', function (Blueprint $table) {
            $table->decimal('shipping_cost', 25, 2)->default(0)->after('price_estimation');
            $table->text('origin')->nullable()->after('shipping_cost');
            $table->text('destination')->nullable()->after('origin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('b_o_q_edits', function (Blueprint $table) {
            $table->dropColumn('shipping_cost');
            $table->dropColumn('origin');
            $table->dropColumn('destination');
        });
    }
};
