<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Produk;
use App\Models\Restock;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MutasiController extends Controller
{
    public function store(Request $request)
    {

        $validated = $request->validate([
            'id_produk' => 'required|exists:produk,id',
            'jumlah' => 'required',
            'jenis' => 'required',
        ]);

        $produk = Produk::query()->with('persediaan')->find($request->id_produk);

        $mutasi = Mutasi::create($validated);

        if ($request->jenis === 'masuk') {
            $restock = Restock::where('id_produk', $request->id_produk)->first();

            $tanggalPesan = Carbon::parse($restock->created_at);
            $tanggalSelesai = Carbon::now();

            // Hitung selisih hari
            $leadTime = max(1, $tanggalPesan->diffInDays($tanggalSelesai));

            $produk->persediaan->jumlah += $request->jumlah;

            $produk->lead_time = $leadTime;
            $produk->persediaan->save();
            $produk->save();

            $restock->delete();

        } else {
            $produk->persediaan->jumlah -= $request->jumlah;
            $produk->save();
        }

        $message = $request->jenis == 'masuk' ? 'Berhasil menambahkan data barang masuk' : 'Berhasil menambahkan data barang keluar';

        if ($mutasi) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $mutasi,
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menambahkan data mutasi',
        ], 500);

    }

    public function destroy($id)
    {
        $penjualan = Mutasi::find($id)?->delete();

        return response()->json([
            'success' => true,
            'message' => 'Log mutasi berhasil dihapus',
        ], 200);
    }

    public function show($id)
    {
        $mutasi = Mutasi::with('produk')->find($id);

        if ($mutasi) {
            return response()->json([
                'success' => true,
                'message' => 'Data mutasi berhasil didapatkan.',
                'data' => $mutasi,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data mutasi gagal didapatkan.',
        ], 500);
    }

    public function update(Request $request, $id)
    {

        // FIX: jumlah persediaan barang ketika update mutasi barang masuk
        $data = $request->validate([
            'jumlah' => 'required',
        ]);

        $mutasi = Mutasi::find($id);

        $mutasi->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data mutasi berhasil diperbarui',
            'data' => $mutasi,
        ], 200);

    }
}
