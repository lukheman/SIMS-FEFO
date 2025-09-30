<?php

namespace App\Providers;

use App\Models\Mutasi;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class PerhitunganEOQServices extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public static function hitungEOQ(float $D, float $S, float $H): float
    {
        return $D > 0 ? round(sqrt((2 * $D * $S) / $H), 2) : 0;
    }

    public static function hitungSafetyStock(float $PM, float $PRR, int $LT): float
    {
        return ($PM - $PRR) * $LT;
    }

    public static function hitungROP(float $SS, float $Q, int $LT): float
    {
        return round($SS + ($LT * $Q), 2);
    }

    public static function penjualanBulanan(int $produkId, Carbon $periode): int
    {
        return Mutasi::where('id_produk', $produkId)
            ->whereYear('tanggal', $periode->year)
            ->whereMonth('tanggal', $periode->month)
            ->where('jenis', 'keluar')
            ->sum('jumlah');
    }

    public static function penjualanRataRataHarian(int $produkId, Carbon $periode1, Carbon $periode2): float
    {
        $jumlah = self::penjualanBulanan($produkId, $periode1) + self::penjualanBulanan($produkId, $periode2);
        $hari = $periode1->daysInMonth + $periode2->daysInMonth;

        return $hari > 0 ? round($jumlah / $hari, 2) : 0;
    }

    public static function penjualanMaksimum($produk_id, Carbon $periode1, Carbon $periode2)
    {
        $jumlah1 = self::penjualanBulanan($produk_id, $periode1);
        $jumlah2 = self::penjualanBulanan($produk_id, $periode2);

        return max($jumlah1, $jumlah2);
    }

    public static function getDemand($produk_id, Carbon $periode1, Carbon $periode2)
    {
        return self::penjualanBulanan($produk_id, $periode1) +
           self::penjualanBulanan($produk_id, $periode2);
    }

    public static function economicOrderQuantity($produk_id, ?Carbon $periode = null): float
    {
        $periode = $periode ?? Carbon::now();

        $periode1 = (clone $periode)->subMonth(2);
        $periode2 = (clone $periode)->subMonth(1);

        $produk = Produk::find($produk_id);

        $D = self::getDemand($produk_id, $periode1, $periode2);

        $S = optional($produk->biayaPemesanan)->biaya ?? 0;
        $H = optional($produk->biayaPenyimpanan)->biaya ?? 1;

        return self::hitungEOQ($D, $S, $H);

    }

    public static function safetyStock($produk_id, ?Carbon $periode = null): float
    {

        $periode = $periode ?? Carbon::now();

        $periode1 = (clone $periode)->subMonth(2);
        $periode2 = (clone $periode)->subMonth(1);

        $produk = Produk::find($produk_id);

        $D = self::getDemand($produk->id, $periode1, $periode2);
        $PM = self::penjualanMaksimum($produk->id, $periode1, $periode2);
        $PRR = $D / 2;
        $LT = $produk->lead_time;

        return self::hitungSafetyStock($PM, $PRR, $LT);
    }

    public static function reorderPoint($produk_id, ?Carbon $periode = null): float
    {
        $periode = $periode ?? Carbon::now();

        $periode1 = (clone $periode)->subMonth(2);
        $periode2 = (clone $periode)->subMonth(1);

        $produk = Produk::find($produk_id);

        $LT = $produk->lead_time;
        $SS = self::safetyStock($produk_id, $periode);

        $Q = self::penjualanRataRataHarian($produk_id, $periode1, $periode2);

        return self::hitungROP($SS, $Q, $LT);
    }

    public static function frekuensiPemesanan($produk_id, ?Carbon $periode = null): int
    {
        $periode = $periode ?? Carbon::now();

        $periode1 = (clone $periode)->subMonth(2);
        $periode2 = (clone $periode)->subMonth(1);

        $D = self::getDemand($produk_id, $periode1, $periode2);

        $EOQ = self::economicOrderQuantity($produk_id, $periode) ?: 1;

        return round($D / $EOQ);

    }

    public static function hasSufficientSalesData($produk_id, ?Carbon $periode = null): bool
    {
        $periode = $periode ?? Carbon::now();

        $periode1 = (clone $periode)->subMonth(2);
        $periode2 = (clone $periode)->subMonth(1);

        $jumlah1 = self::penjualanBulanan($produk_id, $periode1);
        $jumlah2 = self::penjualanBulanan($produk_id, $periode2);

        return $jumlah1 !== 0 && $jumlah2 !== 0;
    }
}
