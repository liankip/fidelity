<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vendor_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->unsignedBigInteger('price');
            $table->string('brand');
            $table->foreignId('unit_id');
            $table->foreignId('category_id');
            $table->string('type');
            $table->foreignId('vendor_id')->constrained('vendor_registrants')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_items');
    }
};
