<?php

namespace App\Models;

use App\Providers\PerhitunganEOQServices;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
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

    public function biayaPemesanan()
    {
        return $this->hasOne(BiayaPemesanan::class, 'id_produk');
    }

    public function biayaPenyimpanan()
    {
        return $this->hasOne(BiayaPenyimpanan::class, 'id_produk');
    }

    public function isPersediaanMencukupi(int $permintaan): bool
    {
        return ($this->persediaan?->jumlah ?? 0) >= $permintaan;
    }

    public static function EOQSemuaProdukAllTime()
    {
        $result = [];

        // Ambil tanggal keluar pertama dari semua produk
        $firstDate = Mutasi::where('jenis', 'keluar')
            ->orderBy('tanggal', 'asc')
            ->value('tanggal');

        if (! $firstDate) {
            return []; // Tidak ada data keluar
        }

        $start = Carbon::parse($firstDate)->startOfMonth();
        $end = Carbon::now()->startOfMonth();
        $current = $start->copy();

        // Ambil semua produk (gunakan model Produk)
        $produkList = Produk::with(['biayaPemesanan', 'biayaPenyimpanan'])->get();

        // Loop semua bulan dari awal sampai sekarang
        while ($current <= $end) {
            foreach ($produkList as $produk) {

                // cek apakah data mencukupi
                if (! PerhitunganEOQServices::hasSufficientSalesData($produk->id, $current)) {
                    // $result[] = [
                    //      'nama_produk' => $produk->nama_produk,
                    //      'periode' => $current->format('Y-m'),
                    // ];

                    continue;
                }

                $EOQ = PerhitunganEOQServices::economicOrderQuantity($produk->id, $current);
                $SS = PerhitunganEOQServices::safetyStock($produk->id, $current);
                $ROP = PerhitunganEOQServices::reorderPoint($produk->id, $current);

                $result[] = [
                    'nama_produk' => $produk->nama_produk,
                    'eoq' => $EOQ,
                    'safety_stock' => $SS,
                    'reorder_point' => $ROP,
                    'periode' => $current->format('Y-m'),
                ];
            }

            $current->addMonth();
        }

        return $result;
    }

    public static function EOQPerBulan($periode)
    {
        $result = [];

        try {
            $periode = Carbon::createFromFormat('Y-m', $periode);
        } catch (\Exception $e) {
            return ['error' => 'Format periode tidak valid. Gunakan format Y-m (contoh: 2024-05).'];
        }

        $produkList = self::with(['biayaPemesanan', 'biayaPenyimpanan'])->get();

        foreach ($produkList as $produk) {

            // cek apakah data mencukupi
            if (! PerhitunganEOQServices::hasSufficientSalesData($produk->id, $periode)) {
                // $result[] = [
                //      'produk' => $produk,
                // ];

                continue;
            }

            $EOQ = PerhitunganEOQServices::economicOrderQuantity($produk->id, $periode);
            $SS = PerhitunganEOQServices::safetyStock($produk->id, $periode);
            $ROP = PerhitunganEOQServices::reorderPoint($produk->id, $periode);

            $result[] = [
                'produk' => $produk,
                'eoq' => $EOQ,
                'safety_stock' => $SS,
                'reorder_point' => $ROP,
            ];
        }

        return $result;
    }

    public function getEconomicOrderQuantityAttribute(): string
    {
        $unitBesar = $this->unit_besar ?? 'unit';
        $unitKecil= $this->unit_kecil?? 'unit';
        $eoq = PerhitunganEOQServices::economicOrderQuantity($this->id);
        $eoqBesar = $eoq / ($this->tingkat_konversi ?? 1);
        return "{$eoq} {$unitKecil} ({$eoqBesar} {$unitBesar})";
    }

    public function getSafetyStockAttribute(): string
    {

        $unitBesar = $this->unit_besar ?? 'unit';
        $unitKecil= $this->unit_kecil?? 'unit';
        $ss = PerhitunganEOQServices::safetyStock($this->id);
        $ssBesar = $ss / ($this->tingkat_konversi ?? 1);
        return "{$ss} {$unitKecil} ({$ssBesar} {$unitBesar})";
    }

    public function getReorderPointAttribute(): string
    {
        $unitBesar = $this->unit_besar ?? 'unit';
        $unitKecil= $this->unit_kecil?? 'unit';
        $rop = PerhitunganEOQServices::reorderPoint($this->id);;
        $ropBesar = $rop / ($this->tingkat_konversi ?? 1);

        return "{$rop} {$unitKecil} ({$ropBesar} {$unitBesar})";

    }

    public function getFrekuensiPemesananAttribute(): int
    {
        return PerhitunganEOQServices::frekuensiPemesanan($this->id);
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
}
