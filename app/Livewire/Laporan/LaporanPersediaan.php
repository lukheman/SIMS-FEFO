<?php

namespace App\Livewire\Laporan;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Laporan Persediaan')]
class LaporanPersediaan extends Component
{
    public function render()
    {
        return view('livewire.laporan.laporan-persediaan');
    }
}
