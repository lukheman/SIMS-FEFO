<?php

namespace App\View\Components\Laporan;

use App\Models\Mutasi;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LaporanBarangMasuk extends Component
{
    public $barang_masuk;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->barang_masuk = Mutasi::with('produk')->where('jenis', 'masuk')->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.laporan.laporan-barang-masuk');
    }
}
