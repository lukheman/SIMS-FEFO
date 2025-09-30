<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Constants\UnitKecilProduk;
use App\Constants\UnitBesarProduk;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk', 100);
            $table->string('kode_produk', 20)->unique();
            $table->decimal('harga_beli', 10, 2); // perbal
            $table->decimal('harga_jual', 10, 2); // perbal
            $table->integer('lead_time')->default(0); // waktu tunggu dalam satuan hari
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->date('exp')->nullable();
            $table->decimal('harga_jual_unit_kecil', 10, 2);
            $table->integer('tingkat_konversi')->default(0); // jumlah unit kecil dalam satu unit besar
            $table->enum('unit_kecil', UnitKecilProduk::values());
            $table->enum('unit_besar', UnitBesarProduk::values());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
