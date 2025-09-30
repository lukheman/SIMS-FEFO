<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_produk' => 'required|exists:produk,id',
            'jumlah' => 'required|numeric|min:1',
        ]);

        $id_reseller = Auth::guard('reseller')->id();

        // Cek apakah reseller punya keranjang, kalau belum bikin baru
        if (!Keranjang::where('id_reseller', $id_reseller)->first()) {
            Keranjang::create([
                'id_reseller' => $id_reseller,
            ]);
        }

        $keranjang = Keranjang::where('id_reseller', $id_reseller)->first();
        $produk = Produk::find($request->id_produk);

        // Konversi dari bal ke pcs
        $jumlah_pcs = $request->jumlah * $produk->tingkat_konversi;

        // Cek stok produk
        if (!$produk->isPersediaanMencukupi($jumlah_pcs)) {
            return response()->json([
                'success' => false,
                'message' => "Maaf, stok {$produk->nama_produk} tidak cukup saat ini.",
            ], 200);
        }

        $unit = $request->satuan === $produk->unit_kecil;

        // Buat pesanan
        $pesanan = Pesanan::create([
            'id_reseller' => $id_reseller,
            'id_produk' => $request->id_produk,
            'id_keranjang_belanja' => $keranjang->id,
            'jumlah' => $request->jumlah,
            'satuan' => $unit
        ]);

        if ($pesanan) {
            return response()->json([
                'success' => true,
                'message' => "{$produk->nama_produk} berhasil ditambahkan ke keranjang!",
                'data' => $produk,
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => "Gagal menambahkan {$produk->nama_produk} ke keranjang. Coba lagi ya!",
        ], 500);
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $pesanan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dihapus dari keranjang',
        ]);
    }

    public function destroyMany(Request $request)
    {

        $ids = $request->ids;

        Pesanan::whereIn('id', $ids)->delete();

        $lenIds = count($ids);

        return response()->json([
            'success' => true,
            'message' => "Berhasil menghapus $lenIds pesanan dari keranjang",
        ]);

    }

    public function show($id)
    {

        $pesanan = Pesanan::query()->with('produk')->find($id);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapakan pesanan',
            'data' => $pesanan,
        ]);

    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'jumlah' => 'required|numeric',
        ]);

        $pesanan = Pesanan::with('produk')->find($id);
        $pesanan->jumlah = $request->jumlah;
        $pesanan->save();

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil diperbarui',
            'data' => $pesanan,
        ], 200);

    }
}
