<?php

namespace App\Livewire\Dashboard;

use App\Models\Transaksi;
use Livewire\Component;

class KurirDashboard extends Component
{
    public $jumlahPesanan;

    public function mount() {
        $this->jumlahPesanan = Transaksi::query()
            ->whereHas('kurir', function($q) {
                $q->where('id', getActiveUserId());
            })
            ->whereDate('created_at', \Carbon\Carbon::today())
            ->count();
    }

    public function render()
    {
        return view('livewire.dashboard.kurir-dashboard');
    }
}
