<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kategori_w_artikels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_artikel_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('artikel_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_w_artikels');
    }
};
