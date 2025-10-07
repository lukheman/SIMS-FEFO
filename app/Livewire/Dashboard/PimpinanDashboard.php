<?php

namespace App\Livewire\Dashboard;

use App\Models\Mutasi;
use App\Models\Produk;
use Carbon\Carbon;
use Livewire\Component;

class PimpinanDashboard extends Component
{

    public $totalPenjualan;
    public $jumlahProduk;

    public function mount() {

        $this->jumlahProduk = Produk::query()->count();

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
        return view('livewire.dashboard.pimpinan-dashboard');
    }
}
