<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('tanggal_pengiriman')->nullable();
            $table->timestamp('tanggal_diterima')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tanggal_pengiriman');
            $table->dropColumn('tanggal_diterima');
        });
    }

};
