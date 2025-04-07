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
        Schema::create('meeting_form', function (Blueprint $table) {
            $table->id();
            $table->date('meeting_date')->nullable();
            $table->string('meeting_location')->nullable();
            $table->json('meeting_attendant')->nullable();
            $table->json('meeting_notulen')->nullable();
            $table->string('notulensi')->nullable();
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
        Schema::dropIfExists('meeting_form');
    }
};
