<?php

namespace App\Livewire\Dashboard;

use App\Models\Mutasi;
use App\Models\Pesanan;
use App\Models\Transaksi;
use Carbon\Carbon;
use Livewire\Component;

class KasirDashboard extends Component
{
    public $jumlahPesanan;
    public $totalPenjualan;

    public function mount() {


        $this->jumlahPesanan = Pesanan::query()
            ->whereDate('created_at', Carbon::today())
            ->count();

        $mutasiHariIni = Mutasi::with('produk')
            ->where('jenis', 'keluar')
            ->whereDate('created_at', Carbon::today())
            ->get();

        $this->totalPenjualan = $mutasiHariIni->sum(function ($mutasi) {
            // Jika satuan = true, gunakan harga_jual unit besar, jika false gunakan harga_jual_unit_kecil
            $harga = $mutasi->satuan
                ? $mutasi->produk->harga_jual
                : $mutasi->produk->harga_jual_unit_kecil;

            return $mutasi->jumlah * $harga;
        });
    }

    public function render()
    {
        return view('livewire.dashboard.kasir-dashboard');
    }
}
