<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Produk;
use App\Models\TransaksiMode;

class PimpinanController extends Controller
{
    public function laporanEOQ()
    {

        $data_eoq = Produk::with('persediaan')->get();

        return view('pimpinan.laporan-eoq', [
            'page' => 'Laporan EOQ',
            'produk' => $data_eoq,
        ]);

    }

    public function index()
    {
        $transaksi = TransaksiMode::where('status', 'selesai')->count();
        $persediaan_barang = Produk::with('persediaan')->get()->sum('persediaan.jumlah');

        return view('pimpinan.index', [
            'page' => 'Dashboard',
            'transaksi' => $transaksi,
            'persediaan_barang' => $persediaan_barang,
        ]);
    }

    public function laporanPenjualan()
    {
        $penjualan = Mutasi::where('jenis', 'keluar')->get();

        return view('pimpinan.laporan-penjualan', [
            'page' => 'Laporan Penjualan',
            'penjualan' => $penjualan,
        ]);
    }

    public function laporanPersediaanProduk()
    {
        $produk = Produk::with('persediaan')->get();

        return view('pimpinan.laporan-persediaan-produk', [
            'page' => 'Laporan Persediaan Produk',
            'produk' => $produk,
        ]);
    }

    public function persediaan()
    {
        return view('pimpinan.persediaan', [
            'page' => 'Persediaan Produk',
        ]);
    }

    public function laporanBarangMasuk()
    {
        return view('pimpinan.laporan-barang-masuk', [
            'page' => 'Laporan Barang Masuk',
        ]);
    }

    public function laporanPesanan() {
        return view('pimpinan.laporan-pesanan', [
            'page' => 'Laporan Pesanan'
        ]);
    }
}
