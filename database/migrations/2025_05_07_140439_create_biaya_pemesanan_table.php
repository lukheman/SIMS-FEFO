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
        Schema::create('biaya_pemesanan_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produk')->constrained('produk')->cascadeOnDelete();
            $table->decimal('biaya', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya_pemesanan');
    }
};
