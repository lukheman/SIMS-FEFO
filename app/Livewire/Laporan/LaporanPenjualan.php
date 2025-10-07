<?php

namespace App\Livewire\Laporan;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Laporan Penjualan')]
class LaporanPenjualan extends Component
{
    public function render()
    {
        return view('livewire.laporan.laporan-penjualan');
    }
}
