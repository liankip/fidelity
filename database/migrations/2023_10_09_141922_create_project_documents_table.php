<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_documents', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('file_name');
            $table->foreignId('uploaded_by');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_documents');
    }
};
