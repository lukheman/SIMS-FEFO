<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use App\Models\Reseller;
use App\Models\Transaksi;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

#[Title('Laporan Penjualan Reseller')]
class LaporanPenjualanReseller extends Component
{
    use WithPagination;

    public $selectedReseller = '';

    public function updatingSelectedReseller()
    {
        $this->resetPage();
    }

    #[Computed]
    public function resellerList()
    {
        return Reseller::orderBy('name')->get();
    }

    #[Computed]
    public function transaksiList()
    {
        return Transaksi::query()
            ->with(['user', 'pesanan'])
            ->whereHas('user')
            ->when($this->selectedReseller, function ($query) {
                $query->where('id_reseller', $this->selectedReseller);
            })
            ->latest('tanggal')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.laporan.laporan-penjualan-reseller');
    }
}
