<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Produk;
use App\Models\Restock;
use DateTime;

class AdminGudangController extends Controller
{
    protected $role = 'admin_gudang';

    public function biayaPenyimpanan()
    {
        $produk = Produk::query()->with('biayaPenyimpanan')->get();

        return view("{$this->role}.produk.biaya-penyimpanan", [
            'page' => 'Biaya Penyimpanan Produk',
            'produk' => $produk,
        ]);
    }

    public function biayaPemesanan()
    {
        $produk = Produk::query()->with('biayaPemesanan')->get();

        return view("{$this->role}.produk.biaya-pemesanan", [
            'page' => 'Biaya Pemesanan Produk',
            'produk' => $produk,
        ]);
    }

    public function persediaan()
    {
        $produk = Produk::with('persediaan')->get();

        return view("{$this->role}.produk.persediaan", [
            'page' => 'Persediaan Produk',
            'produk' => $produk,
        ]);
    }

    public function index()
    {
        $total_produk = Produk::count();
        $total_persediaan = Produk::with('persediaan')->get()->sum('persediaan.jumlah');

        return view("{$this->role}.index", [
            'page' => 'Dashboard',
            'total_produk' => $total_produk,
            'total_persediaan' => $total_persediaan,
        ]);
    }

    public function barangMasuk()
    {
        $barang_masuk = Mutasi::with('produk')->where('jenis', 'masuk')->get();

        return view("{$this->role}.barang-masuk", [
            'page' => 'Barang Masuk',
            'barang_masuk' => $barang_masuk,
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

    public function produk()
    {
        $produk = Produk::query()->with(['biayaPenyimpanan', 'biayaPemesanan'])->get();

        return view("{$this->role}.produk.produk", [
            'page' => 'Produk',
            'produk' => $produk,
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

    public function eoq()
    {

        $produk = Produk::with('persediaan')->get();

        return view("{$this->role}.eoq", [
            'page' => 'EOQ',
            'produk' => $produk,
        ]);
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
