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
        Schema::table('customer', function (Blueprint $table) {
            $table->renameColumn('nama', 'name');
            $table->string('npwp', 16)->after('nama')->nullable();
            $table->text('shipping_address')->after('npwp')->nullable();
            $table->string('ktp', 200)->after('shipping_address')->nullable();
            $table->string('pic_name', 80)->after('ktp')->nullable();
            $table->renameColumn('nomor_handphone', 'pic_phone');
            $table->renameColumn('email', 'pic_email');
            $table->string('recipient_name', 80)->after('email')->nullable();
            $table->string('recipient_phone', 16)->after('recipient_name')->nullable();
            $table->renameColumn('alamat', 'billing_address')->after('recipient_phone')->nullable();
            $table->renameColumn('provinsi', 'billing_phone')->after('billing_address')->nullable();
            $table->renameColumn('kota', 'billing_email')->after('billing_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer', function (Blueprint $table) {
            //
        });
    }
};
