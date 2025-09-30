<?php

namespace App\View\Components\Produk;

use App\Models\Produk;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PersediaanProduk extends Component
{
    /**
     * Create a new component instance.
     */
    public $produk;

    public function __construct()
    {
        $this->produk = Produk::all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.produk.persediaan-produk');
    }
}
