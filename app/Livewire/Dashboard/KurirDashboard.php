<?php

namespace App\Livewire\Dashboard;

use App\Models\Transaksi;
use Livewire\Component;

class KurirDashboard extends Component
{
    public $jumlahPesanan;

    public function mount() {
        $this->jumlahPesanan = Transaksi::query()
            ->whereHas('kurir', getActiveUserId())
            ->count();
    }

    public function render()
    {
        return view('livewire.dashboard.kurir-dashboard');
    }
}
