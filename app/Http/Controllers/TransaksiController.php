<?php

namespace App\Http\Controllers;

use App\Enums\MetodePembayaran;
use App\Enums\Role;
use App\Enums\StatusTransaksi;
use App\Models\Keranjang;
use App\Models\Mutasi;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TransaksiController extends Controller
{
    public function show($id)
    {

        $transaksi = Transaksi::with(['produk', 'user', 'user.reseller_detail'])->findOrFail($id);

        if ($transaksi) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendapatkan data transaksi',
                'data' => $transaksi,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mendapatkan data transaksi',
        ], 500);

    }

}
