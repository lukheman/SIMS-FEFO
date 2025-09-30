<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class InputDataMutasiController extends Controller
{

    public function index() {
        $produk = Produk::all();
        return view('input-data-mutasi', [
            'produk' => $produk
        ]);
    }

}
