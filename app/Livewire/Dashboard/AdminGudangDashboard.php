<?php

namespace App\Livewire\Dashboard;

use App\Models\Mutasi;
use App\Models\Produk;
use Livewire\Component;

class AdminGudangDashboard extends Component
{
    public $jumlahProduk;
    public $jumlahPenjualan;

    public function mount() {
        $this->jumlahProduk = Produk::query()->count();
        $this->jumlahPenjualan = Mutasi::query()->where('jenis', 'keluar')->count();
    }

    public function render()
    {
        return view('livewire.dashboard.admin-gudang-dashboard');
    }
}
