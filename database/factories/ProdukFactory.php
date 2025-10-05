<?php

namespace Database\Factories;

use App\Models\Persediaan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Produk;
use App\Constants\UnitKecilProduk;
use App\Constants\UnitBesarProduk;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    protected $model = Produk::class;

    public function definition(): array
    {
        return [
            'nama_produk' => $this->faker->words(2, true), // contoh: "Minyak Goreng"
            'kode_produk' => strtoupper(Str::random(6)),  // contoh: "AB12CD"
            'harga_beli' => $this->faker->randomFloat(2, 1000, 100000), // harga beli
            'harga_jual' => $this->faker->randomFloat(2, 2000, 150000), // harga jual
            'lead_time' => $this->faker->numberBetween(0, 30), // hari
            'deskripsi' => $this->faker->sentence(),
            'gambar' => $this->faker->imageUrl(400, 400, 'product', true, 'Produk'),
            'exp' => $this->faker->optional()->dateTimeBetween('now', '+2 years'),
            'harga_jual_unit_kecil' => $this->faker->randomFloat(2, 500, 50000),
            'tingkat_konversi' => $this->faker->numberBetween(1, 24), // misal 1 dus = 24 pcs
            'unit_kecil' => $this->faker->randomElement(UnitKecilProduk::values()),
            'unit_besar' => $this->faker->randomElement(UnitBesarProduk::values()),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Produk $produk) {
            Persediaan::factory()->create([
                'id_produk' => $produk->id, // relasi ke produk
            ]);
        });
    }
}
