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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pr_no');
            $table->string('pr_type');
            $table->foreignId('project_id')->constrained('projects');
            $table->foreignId('warehouse_id')->constrained('warehouses');
            $table->string('status')->nullable();
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('purchase_requests');
        Schema::enableForeignKeyConstraints();
    }
};
