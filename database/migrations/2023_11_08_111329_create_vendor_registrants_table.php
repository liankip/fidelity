<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vendor_registrants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('name');
            $table->string('npwp');
            $table->string('address');
            $table->string('ktp_image');
            $table->string('email');
            $table->string('telp');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('bank_owner_name');
            $table->string('bank_branch');
            $table->json('top');

            $table->boolean('is_approved')->default(false);
            $table->foreignId('aproved_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_registrants');
    }
};
