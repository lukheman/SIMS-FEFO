<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Produk;
use App\Models\Transaksi;

class PimpinanController extends Controller
{

    public function laporanPesanan() {
        return view('pimpinan.laporan-pesanan', [
            'page' => 'Laporan Pesanan' ]);
    }
}
