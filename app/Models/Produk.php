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
        return $this->hasMany(Persediaan::class, 'id_produk');
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

    /**
     * Total stok dari semua batch persediaan.
     */
    public function totalPersediaan(): int
    {
        return (int) $this->persediaan()->sum('jumlah');
    }

    /**
     * Persediaan aktif (stok > 0) diurutkan FEFO.
     */
    public function persediaanFefo()
    {
        return $this->persediaan()->aktif()->fefoOrder();
    }

    /**
     * Tanggal expired terdekat dari batch aktif.
     */
    public function getTanggalExpTerdekatAttribute()
    {
        return $this->persediaan()
            ->aktif()
            ->whereNotNull('tanggal_exp')
            ->orderBy('tanggal_exp', 'ASC')
            ->value('tanggal_exp');
    }

    public function isPersediaanMencukupi(int $permintaan): bool
    {
        return $this->totalPersediaan() >= $permintaan;
    }

    public function getLabelHargaBeliAttribute()
    {
        $hargaBeli = $this->harga_beli ?? 0;
        $formattedHarga = number_format($hargaBeli, 0, ',', '.');
        $unitBesar = $this->unit_besar ?? 'unit';
        return "Rp. {$formattedHarga}/{$unitBesar}";
    }

    public function getLabelHargaJualAttribute()
    {
        $hargaJual = $this->harga_jual ?? 0;
        $formattedHarga = number_format($hargaJual, 0, ',', '.');
        $unitBesar = $this->unit_besar ?? 'unit';
        return "Rp. {$formattedHarga}/{$unitBesar}";
    }

    public function getLabelPersediaanAttribute()
    {
        $persediaanKecil = $this->totalPersediaan();

        $tingkatKonversi = $this->tingkat_konversi ?? 1;
        $persediaanBesar = round($persediaanKecil / $tingkatKonversi, 2);

        $unitBesar = $this->unit_besar ?? 'unit';
        $unitKecil = $this->unit_kecil ?? 'unit';

        return "{$persediaanKecil} {$unitKecil} ({$persediaanBesar}/{$unitBesar})";
    }

    public function jumlahPersediaanUnitBesar()
    {
        $persediaanKecil = $this->totalPersediaan();

        $tingkatKonversi = $this->tingkat_konversi ?? 1;

        return floor($persediaanKecil / $tingkatKonversi);
    }

    // init akan mengembalikan ukuran fix dalam bal, misalkan 10 pcs (2 bal)
    public function getLabelPersediaanFixUnitAttribute()
    {
        $persediaanBesar = $this->jumlahPersediaanUnitBesar();

        $tingkatKonversi = $this->tingkat_konversi ?? 1;
        $persediaanKecil = $persediaanBesar * $tingkatKonversi;

        $unitBesar = $this->unit_besar ?? 'unit';
        $unitKecil = $this->unit_kecil ?? 'unit';
        return "{$persediaanKecil} {$unitKecil} ({$persediaanBesar}/{$unitBesar})";

    }
}
