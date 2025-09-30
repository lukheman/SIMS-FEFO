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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produk')->constrained('produk')->onDelete('cascade');
            $table->foreignId('id_reseller')->nullable()->constrained('reseller')->onDelete('cascade');
            $table->foreignId('id_transaksi')->nullable()->constrained('transaksi')->onDelete('cascade');
            $table->foreignId('id_keranjang_belanja')->nullable()->constrained('keranjang_belanja')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->boolean('satuan')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
