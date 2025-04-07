<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('voucher_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id');
            $table->foreignId('voucher_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_details');
    }
};
