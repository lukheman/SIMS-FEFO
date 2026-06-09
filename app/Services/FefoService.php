<?php

namespace App\Services;

use App\Models\Persediaan;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class FefoService
{
    /**
     * Kurangi stok menggunakan metode FEFO (First Expired First Out).
     * Mengambil dari batch dengan tanggal_exp paling dekat terlebih dahulu.
     *
     * @param Produk $produk
     * @param int $jumlah — jumlah yang akan dikurangi (dalam unit kecil)
     * @return array — daftar batch yang dikurangi [{persediaan, jumlah_dikurangi}]
     * @throws \Exception jika stok tidak mencukupi
     */
    public static function kurangiStok(Produk $produk, int $jumlah): array
    {
        $totalStok = $produk->persediaan()->sum('jumlah');

        if ($totalStok < $jumlah) {
            throw new \Exception("Stok produk {$produk->nama_produk} tidak mencukupi. Tersedia: {$totalStok}, diminta: {$jumlah}");
        }

        $batchList = $produk->persediaan()
            ->where('jumlah', '>', 0)
            ->orderBy('created_at', 'ASC')
            ->get();

        $sisaPermintaan = $jumlah;
        $hasilPengurangan = [];

        return DB::transaction(function () use ($batchList, $sisaPermintaan, &$hasilPengurangan) {
            foreach ($batchList as $batch) {
                if ($sisaPermintaan <= 0)
                    break;

                $dikurangi = min($batch->jumlah, $sisaPermintaan);
                $batch->jumlah -= $dikurangi;
                $batch->save();

                $hasilPengurangan[] = [
                    'persediaan' => $batch,
                    'jumlah_dikurangi' => $dikurangi,
                ];

                $sisaPermintaan -= $dikurangi;
            }

            return $hasilPengurangan;
        });
    }

    /**
     * Tambah stok baru ke batch.
     * Jika sudah ada batch dengan tanggal_exp yang sama untuk produk ini, tambahkan ke batch tersebut.
     * Jika belum ada, buat batch baru.
     *
     * @param Produk $produk
     * @param int $jumlah — jumlah yang ditambahkan (dalam unit kecil)
     * @param string|null $tanggalExp — tanggal expired batch
     * @return Persediaan
     */
    public static function tambahStok(Produk $produk, int $jumlah, ?string $tanggalExp = null): Persediaan
    {
        return DB::transaction(function () use ($produk, $jumlah) {
            // Cari batch dengan tanggal_masuk yang sama (hari ini)
            $batch = $produk->persediaan()
                ->where('tanggal_masuk', now()->toDateString())
                ->first();

            if ($batch) {
                $batch->jumlah += $jumlah;
                $batch->save();
            } else {
                $batch = $produk->persediaan()->create([
                    'jumlah' => $jumlah,
                    'tanggal_masuk' => now()->toDateString(),
                ]);
            }

            return $batch;
        });
    }
}
