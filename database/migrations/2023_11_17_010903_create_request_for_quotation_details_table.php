<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('request_for_quotation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('request_for_quotation_id')->references('id')->on('request_for_quotations')->onDelete('cascade');
            $table->foreignId('item_id');
            $table->unsignedBigInteger('price')->nullable();
            $table->unsignedBigInteger('qty')->nullable();
            $table->string('unit');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_for_quotation_details');
    }
};
