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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('project_id')->constrained();
            $table->enum('reason', ['Sakit', 'Cuti', 'Darurat Keluarga', 'Lainnya']);
            $table->text('notes')->nullable;
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('days_count');
            $table->string('attachment_file')->nullable();
            $table->enum('status',['Approved', 'Rejected', 'New']);
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
        Schema::dropIfExists('leaves');
    }
};
