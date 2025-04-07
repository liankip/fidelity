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
        Schema::create('idx_receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('idx');
            $table->timestamps();
        });

        \DB::insert('insert into idx_receipts (idx) values (1)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('idx_receipts');
    }
};
