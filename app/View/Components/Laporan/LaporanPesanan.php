<?php

namespace App\View\Components\Laporan;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Mutasi;
use App\Models\User;
use App\Models\Transaksi;

use App\Constants\Role;

class LaporanPesanan extends Component
{

    public $pesanan;

    public $ttd;

    public function __construct()
    {
        //
        $this->pesanan = Transaksi::with('user')->whereHas('user')->get();

        $this->ttd = 'jadmin';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.laporan.laporan-pesanan');
    }
}
