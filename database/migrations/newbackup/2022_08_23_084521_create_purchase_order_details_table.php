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
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_detail_id')->constrained('purchase_request_details');
            $table->foreignId('purchase_order_id')->constrained('purchase_orders');
            $table->foreignId('item_id')->constrained('items');
            $table->decimal('qty')->default(0);
            $table->decimal('price')->default(0);
            $table->decimal('tax')->default(0);
            $table->decimal('amount')->default(0);
            $table->enum('status', ['new', 'reject', 'cancel', 'approved', 'arrived', 'paid'])->default('new');
            $table->string('notes')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('purchase_order_details');
        Schema::enableForeignKeyConstraints();
    }
};
