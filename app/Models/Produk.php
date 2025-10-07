<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $guarded = [];

    public function persediaan()
    {
        return $this->hasOne(Persediaan::class, 'id_produk');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_produk');
    }

    public function restock()
    {
        return $this->hasMany(Restock::class, 'id_produk');
    }

    public function mutasi()
    {
        return $this->hasMany(Mutasi::class, 'id_produk');
    }

    public function isPersediaanMencukupi(int $permintaan): bool
    {
        return ($this->persediaan?->jumlah ?? 0) >= $permintaan;
    }

    public function getLabelHargaBeliAttribute() {
        $hargaBeli = $this->harga_beli ?? 0;
        $formattedHarga = number_format($hargaBeli, 0, ',', '.');
        $unitBesar = $this->unit_besar ?? 'unit';
        return "Rp. {$formattedHarga}/{$unitBesar}";
    }

    public function getLabelHargaJualAttribute() {
        $hargaJual = $this->harga_jual ?? 0;
        $formattedHarga = number_format($hargaJual, 0, ',', '.');
        $unitBesar = $this->unit_besar ?? 'unit';
        return "Rp. {$formattedHarga}/{$unitBesar}";
    }

    public function getLabelPersediaanAttribute() {
        $persediaanKecil = $this->persediaan?->jumlah ?? 0;

        $tingkatKonversi = $this->tingkat_konversi ?? 1; // Default ke 1 jika null atau 0
        $persediaanBesar = round($persediaanKecil / $tingkatKonversi, 2);

        $unitBesar = $this->unit_besar ?? 'unit';
        $unitKecil= $this->unit_kecil?? 'unit';

        return "{$persediaanKecil} {$unitKecil} ({$persediaanBesar}/{$unitBesar})";
    }

    public function jumlahPersediaanUnitBesar() {
        $persediaanKecil = $this->persediaan?->jumlah ?? 0;

        $tingkatKonversi = $this->tingkat_konversi ?? 1; // Default ke 1 jika null atau 0

        return floor($persediaanKecil / $tingkatKonversi);
    }

    // init akan mengembalikan ukuran fix dalam bal, misalkan 10 pcs (2 bal)
    public function getLabelPersediaanFixUnitAttribute() {
        $persediaanBesar = $this->jumlahPersediaanUnitBesar();

        $tingkatKonversi = $this->tingkat_konversi ?? 1;
        $persediaanKecil = $persediaanBesar * $tingkatKonversi;

        $unitBesar = $this->unit_besar ?? 'unit';
        $unitKecil= $this->unit_kecil?? 'unit';
        return "{$persediaanKecil} {$unitKecil} ({$persediaanBesar}/{$unitBesar})";

    }

    public static function booted() {
        static::created(function($produk) {
            $produk->persediaan()->create();
        });
    }
}
