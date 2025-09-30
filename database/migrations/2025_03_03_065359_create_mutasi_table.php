<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// mutasi berfungsi untuk mencatat log pemasukan dan pengeluaran barang
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mutasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produk')->constrained('produk')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->date('tanggal')->default(now()->toDateString());
            $table->enum('jenis', ['masuk', 'keluar']);
            $table->string('keterangan')->nullable();
            $table->boolean('satuan')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi');
    }
};
