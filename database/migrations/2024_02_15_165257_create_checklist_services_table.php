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
        Schema::create('checklist_services', function (Blueprint $table) {
            $table->id();
            $table->integer('service_id');
            $table->string('vehicle_no');
            $table->string('vehicle_name');
            $table->string('service_type');
            $table->json('monthly_service');
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
        Schema::dropIfExists('checklist_services');
    }
};
