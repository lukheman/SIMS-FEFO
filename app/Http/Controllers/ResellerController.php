<?php

namespace App\Http\Controllers;

use App\Enums\MetodePembayaran;
use App\Enums\StatusTransaksi;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResellerController extends Controller
{


    public function pengiriman()
    {
        return view('reseller.pengiriman.show', [
            'page' => 'Pengiriman',
        ]);
    }
}
