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
        Schema::create('legal_document_management', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen', 100);
            $table->string('nomor_dokumen', 100);
            $table->string('file_upload', 100);
            $table->string('asal_instansi', 70)->nullable();
            $table->date('expired');
            $table->string('created_by', 10)->nullable();
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
        Schema::dropIfExists('legal_document_management');
    }
};
