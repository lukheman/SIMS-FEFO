<?php

namespace App\Livewire\Dashboard;

use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\Transaksi;
use Livewire\Component;

class ResellerDashboard extends Component
{
    public $keranjang;
    public $pesanan;

    public function mount() {
        $keranjang = Keranjang::query()
            ->with('pesanan')
            ->where('id_reseller', getActiveUserId())
            ->first();

        $this->keranjang = Pesanan::query()->where('id_keranjang_belanja', $keranjang)->count();

        $this->pesanan = Transaksi::query()
            ->where('id_reseller', getActiveUserId())
            ->where('status', '!=', 'selesai')
            ->count();

    }

    public function render()
    {
        return view('livewire.dashboard.reseller-dashboard');
    }
}
