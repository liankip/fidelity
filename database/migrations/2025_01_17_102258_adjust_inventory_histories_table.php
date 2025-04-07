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
        Schema::table('inventory_histories', function (Blueprint $table) {
            if (Schema::hasColumn('inventory_histories', 'type')) {
                $table->dropColumn('type');
            }

            if (Schema::hasColumn('inventory_histories', 'pr_no')) {
                $table->renameColumn('pr_no', 'prdetail_id');
            }

            if(Schema::hasColumn('inventory_histories', 'inventory_id')) {
                $table->dropForeign(['inventory_id']);
            }
        });
        
        Schema::table('inventory_histories', function (Blueprint $table) {
            $table->renameColumn('inventory_id', 'inventory_detail_id');
            $table->foreign('inventory_detail_id')->references('id')->on('inventory_details')->onDelete('cascade');
            
            $table->enum('type', ['IN', 'OUT', null])->default(null);

            
            $table->unsignedBigInteger('prdetail_id')->nullable()->change();
            $table->foreign('prdetail_id')->references('id')->on('purchase_request_details')->onDelete('cascade');

            $table->unsignedBigInteger('podetail_id')->nullable()->after('prdetail_id');
            $table->foreign('podetail_id')->references('id')->on('purchase_order_details')->onDelete('cascade');


            $table->dropColumn(['task', 'notes']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('inventory_histories', function (Blueprint $table) {
        $table->dropForeign(['inventory_detail_id']);
        $table->renameColumn('inventory_detail_id', 'inventory_id');
        $table->foreign('inventory_id')->references('id')->on('inventories')->onDelete('cascade');

        $table->renameColumn('prdetail_id', 'pr_no');
        $table->string('pr_no')->nullable()->change();

        $table->renameColumn('podetail_id', 'task');
        $table->string('task')->nullable()->change();
        
        $table->text('notes')->nullable()->change();
        $table->string('type')->nullable()->change();
    });
}

};
