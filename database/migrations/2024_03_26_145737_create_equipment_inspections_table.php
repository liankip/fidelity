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
        Schema::create('equipment_inspections', function (Blueprint $table) {
            $table->id();
            $table->string('unit');
            $table->string('work');
            $table->foreignId('inspection_officer')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->text('attachment')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('equipment_inspections');
    }
};
