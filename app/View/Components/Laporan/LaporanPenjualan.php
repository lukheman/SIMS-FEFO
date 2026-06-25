<?php

namespace App\View\Components\Laporan;

use App\Models\Mutasi;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LaporanPenjualan extends Component
{
    public $penjualan;

    public $ttd;

    /**
     * Create a new component instance.
     */
    public function __construct($ttd)
    {
        $this->penjualan = \App\Models\Pesanan::with(['produk', 'transaksi'])
            ->whereHas('transaksi', function($q) {
                $q->whereNotIn('status', ['pending', 'dibatalkan']);
            })->get()->sortBy(function($pesanan) {
                return $pesanan->transaksi->tanggal;
            });
        $this->ttd = $ttd;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.laporan.laporan-penjualan');
    }
}
