<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Mutasi extends Model
{
    protected $table = 'mutasi';

    protected $guarded = [];

    protected $appends = ['total_harga_jual', 'total_harga_beli'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function getLabelTotalHargaJualAttribute()
    {

        $totalHarga = $this->satuan ?
            $this->produk->harga_jual_unit_kecil * $this->jumlah :

            $this->produk->harga_jual * $this->jumlah;

        $formattedHarga = number_format($totalHarga, 0, ',', '.');
        return "Rp. {$formattedHarga}";

    }

    public function getTotalHargaBeliAttribute() {
        return $this->produk->harga_beli * $this->jumlah;
    }

    public function getLabelTotalHargaBeliAttribute() {
        $formattedHarga = number_format($this->getTotalHargaBeliAttribute(), 0, ',', '.');
        return "Rp. {$formattedHarga}";
    }


    public function getLabelHargaJualAttribute()
    {
        $hargaJual = $this->satuan ? ($this->produk->harga_jual_unit_kecil ?? 0) : ($this->produk->harga_jual ?? 0);
        $unit = $this->satuan ? ($this->produk->unit_kecil ?? 'unit') : ($this->produk->unit_besar ?? 'unit');

        $formattedHarga = number_format($hargaJual, 0, ',', '.');

        return "Rp. {$formattedHarga}/{$unit}";
    }

    public function getTotalHargaJualAttribute()
    {
        return $this->satuan ? ($this->produk->harga_jual_unit_kecil ?? 0) * $this->jumlah : ($this->produk->harga_jual ?? 0) * $this->jumlah;
    }


    public static function penjualanMaksimum($id_produk, $periode)
    {

        $maxMingguan = self::where('id_produk', $id_produk)
            ->where('jenis', 'keluar')
            ->whereYear('tanggal', $periode->year)
            ->whereMonth('tanggal', $periode->month)
            ->selectRaw('YEAR(tanggal) AS tahun, WEEK(tanggal, 1) AS minggu, SUM(jumlah) AS total')
            ->groupBy('tahun', 'minggu')
            ->orderByDesc('total')
            ->first();

        return $maxMingguan->total ?? 0;

    }

    // rata-rata penjualan harian
    public static function rataRataPenjualan($id_produk, Carbon $periode)
    {

        $penjualan = self::where('id_produk', $id_produk)
            ->whereYear('tanggal', $periode->year)
            ->whereMonth('tanggal', $periode->month)
            ->where('jenis', 'keluar')
            ->sum('jumlah');

        $rata_rata = $penjualan / $periode->daysInMonth;

        return $rata_rata;

    }

    public static function rataRataPenjualanSemua($periode)
    {

        $jumlahHari = $periode->daysInMonth;

        $rataRataPerProduk = self::select('id_produk', DB::raw("SUM(jumlah) / {$jumlahHari} AS rata_rata_harian"))
            ->where('jenis', 'keluar')
            ->whereYear('tanggal', $periode->year)
            ->whereMonth('tanggal', $periode->month)
            ->groupBy('id_produk')
            ->get();

        return $rataRataPerProduk;

    }

    public function getLabelJumlahUnitDipesanAttribute() {
        $unitKecil = $this->produk->unit_kecil;
        $unitBesar = $this->produk->unit_besar;

        $jumlahUnitKecil = $this->jumlah;
        $jumlahUnitBesar = $this->jumlah / $this->produk->tingkat_konversi;

        return "{$jumlahUnitKecil} {$unitKecil} ({$jumlahUnitBesar} {$unitBesar})";
    }

    public function getLabelJumlahUnitTerjualAttribute() {
        $unit = $this->satuan ? $this->produk->unit_kecil : $this->produk->unit_besar;

        return "{$this->jumlah} {$unit}";
    }
}
