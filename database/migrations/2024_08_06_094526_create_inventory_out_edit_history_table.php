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
        Schema::create('inventory_out_edit_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('inventory_out_id')->unsigned();
            $table->bigInteger('prev_out_qty');
            $table->bigInteger('new_out_qty');
            $table->integer('prev_user_id');
            $table->integer('new_user_id');
            $table->text('prev_desc')->nullable();
            $table->text('new_desc')->nullable();
            $table->string('edited_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_out_edit_history');
    }
};
