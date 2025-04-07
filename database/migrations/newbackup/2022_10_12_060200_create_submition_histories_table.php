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
        Schema::disableForeignKeyConstraints();
        Schema::create('submition_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('purchase_orders');
            $table->foreignId('item_id')->constrained('items');
            $table->string('item_name');
            $table->decimal('qty')->default(0);
            $table->string('unit');
            $table->integer('do_id');
            $table->string('pic_pengantar');
            $table->string('penerima');
            $table->string('foto_barang');
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
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('submition_histories');
        Schema::enableForeignKeyConstraints();
    }
};
