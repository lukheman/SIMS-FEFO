<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Produk;
use App\Models\Restock;
use DateTime;

class AdminGudangController extends Controller
{
    protected $role = 'admin_gudang';


    public function persediaan()
    {
        $produk = Produk::with('persediaan')->get();

        return view("{$this->role}.produk.persediaan", [
            'page' => 'Persediaan Produk',
            'produk' => $produk,
        ]);
    }



    public function pesanan()
    {
        $pesanan = Restock::with('produk')->get();

        return view("{$this->role}.pesanan", [
            'page' => 'Pesanan',
            'pesanan' => $pesanan,
        ]);
    }



    private function getPM($periode1, $periode2, $id_produk)
    {
        $periode1 = new DateTime($periode1);
        $periode2 = new DateTime($periode2);

        $penjualanPeriode1 = Mutasi::where('id_produk', $id_produk)
            ->whereYear('tanggal', $periode1->format('Y'))
            ->whereMonth('tanggal', $periode1->format('m'))
            ->where('jenis', 'keluar')
            ->sum('jumlah');

        $penjualanPeriode2 = Mutasi::where('id_produk', $id_produk)
            ->whereYear('tanggal', $periode2->format('Y'))
            ->whereMonth('tanggal', $periode2->format('m'))
            ->where('jenis', 'keluar')
            ->sum('jumlah');

        return max($penjualanPeriode1, $penjualanPeriode2);

    }

    private function cekLogPenjualan($periode, $id_produk)
    {
        [$tahun, $bulan] = explode('-', $periode);

        $penjualan = Mutasi::where('jenis', 'keluar')
            ->whereYear($tahun)
            ->whereMonth($bulan)
            ->count();

        return $penjualan > 0;

    }

    public function laporanPenjualan()
    {

        return view("{$this->role}.laporan-penjualan", ['page' => 'Laporan Penjualan']);
    }

    public function laporanBarangMasuk()
    {
        return view("{$this->role}.laporan-barang-masuk", [
            'page' => 'Laporan Barang Masuk',
        ]);
    }
}
