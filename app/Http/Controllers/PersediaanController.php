<?php

namespace App\Http\Controllers;

use App\Models\Persediaan;
use App\Models\Produk;
use Illuminate\Http\Request;

class PersediaanController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_produk' => 'required|exists:produk,id',
            'periode' => 'required',
            'lead_time' => 'required|numeric|min:0',
            'rata_rata_penggunaan' => 'required|numeric|min:0',
            'biaya_penyimpanan' => 'required|numeric|min:0',
            'biaya_pemesanan' => 'required|numeric|min:0',
            'pembelian' => 'required|numeric|min:0',
        ]);

        $persediaan = Persediaan::create($data);
        if ($persediaan) {

            // setiap pembelian produk maka persediaan produk bertambah
            $produk = Produk::find($request->id_produk);
            $produk->persediaan += $request->pembelian;
            $produk->save();

            return response()->json([
                'success' => true,
                'message' => 'Persediaan berhasil ditambahkan',
                'data' => $persediaan,
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Persediaan gagal ditambahkan',
        ], 500);

    }

    public function show($id)
    {
        $persediaan = Persediaan::with('produk')->findOrFail($id);

        if ($persediaan) {
            return response()->json([
                'success' => true,
                'message' => 'Persediaan berhasil didapatkan',
                'data' => $persediaan,
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mendapatkan persediaan',
        ], 500);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            // 'id_produk' => 'required|exists:produk,id',
            // 'stock' => 'required|numeric|min:0',
            // 'stock_min' => 'required|numeric|min:0',
            // 'stock_max' => 'required|numeric|min:0',
            'lead_time' => 'required|numeric|min:0',
            'rata_rata_penggunaan' => 'required|numeric|min:0',
            'biaya_penyimpanan' => 'required|numeric|min:0',
            'biaya_pemesanan' => 'required|numeric|min:0',
        ]);

        $persediaan = Persediaan::findOrFail($id);
        $persediaan->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Persediaan berhasil diperbarui',
            'data' => $persediaan,
        ], 200);

    }

    public function destroy($id)
    {
        $persediaan = Persediaan::findOrFail($id);

        $persediaan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Persediaan berhasil dihapus',
        ]);
    }
}
