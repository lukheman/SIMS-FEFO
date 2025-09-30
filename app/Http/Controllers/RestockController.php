<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Restock;
use Illuminate\Http\Request;

class RestockController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /* TODO: jangan biarkan user memesan barang yang sama */
        $request->validate([
            'id_produk' => 'required|exists:produk,id',
        ]);

        $produk = Produk::find($request->id_produk);

        Restock::create(['id_produk' => $request->id_produk]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil melakukan pemesanan '.$produk->nama_produk,
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $pesanan = Restock::find($id);
        $pesanan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dihapus',
        ]);

    }

    public function exist($kode_produk)
    {

        $produk = Produk::where('kode_produk', $kode_produk)->first();

        if (! $produk) {
            return response()->json([
                'success' => false,
                'message' => "Produk dengan barcode {$kode_produk} tidak ditemukan",
            ]);
        }

        $exists = Restock::where('id_produk', $produk->id)->exists();

        if ($exists) {
            return response()->json([
                'success' => true,
                'message' => 'Produk telah dipesan sebelumnya',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Produk belum pernah dipesan',
            ]);
        }

    }
}
