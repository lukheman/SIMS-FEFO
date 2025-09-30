<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Produk;
use App\Models\Transaksi;

class PemilikTokoController extends Controller
{
    public function laporanEOQ()
    {

        $data_eoq = Produk::with('persediaan')->get();

        return view('pemilik_toko.laporan-eoq', [
            'page' => 'Laporan EOQ',
            'produk' => $data_eoq,
        ]);

    }

    public function index()
    {
        $transaksi = Transaksi::where('status', 'selesai')->count();
        $persediaan_barang = Produk::with('persediaan')->get()->sum('persediaan.jumlah');

        return view('pemilik_toko.index', [
            'page' => 'Dashboard',
            'transaksi' => $transaksi,
            'persediaan_barang' => $persediaan_barang,
        ]);
    }

    public function laporanPenjualan()
    {
        $penjualan = Mutasi::where('jenis', 'keluar')->get();

        return view('pemilik_toko.laporan-penjualan', [
            'page' => 'Laporan Penjualan',
            'penjualan' => $penjualan,
        ]);
    }

    public function laporanPersediaanProduk()
    {
        $produk = Produk::with('persediaan')->get();

        return view('pemilik_toko.laporan-persediaan-produk', [
            'page' => 'Laporan Persediaan Produk',
            'produk' => $produk,
        ]);
    }

    public function persediaan()
    {
        return view('pemilik_toko.persediaan', [
            'page' => 'Persediaan Produk',
        ]);
    }

    public function laporanBarangMasuk()
    {
        return view('pemilik_toko.laporan-barang-masuk', [
            'page' => 'Laporan Barang Masuk',
        ]);
    }

    public function laporanPesanan() {
        return view('pemilik_toko.laporan-pesanan', [
            'page' => 'Laporan Pesanan'
        ]);
    }
}
