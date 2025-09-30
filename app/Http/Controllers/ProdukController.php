<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::all();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data' => $produk,
        ], 200);

    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'nama_produk' => 'required',
            'kode_produk' => 'required|unique:produk,kode_produk',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'biaya_penyimpanan' => 'required|numeric|min:0',
            'biaya_pemesanan' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'exp' => 'required',
            'harga_jual_unit_kecil' => 'required|numeric|min:0',
            'tingkat_konversi' => 'required|numeric|min:0',
            'unit_kecil' =>  'required',
            'unit_besar' =>  'required',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('images', 'public');
        }

        $produk = Produk::create(collect($data)->except(['biaya_penyimpanan', 'biaya_pemesanan'])->all());

        $produk->biayaPenyimpanan()->create([
            'biaya' => $validated['biaya_penyimpanan'],
        ]);

        $produk->biayaPemesanan()->create([
            'biaya' => $validated['biaya_pemesanan'],
        ]);

        $produk->persediaan()->create();

        if ($produk) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'data' => $produk,
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menambahkan produk',
        ], 500);

    }

    public function update(Request $request, $id)
    {

        // validasi data
        $validated = $request->validate([
            'kode_produk' => [
                'required',
                Rule::unique('produk', 'kode_produk')->ignore($id),
            ],
            'nama_produk' => 'required',
            'harga_beli' => 'required|numeric:min:0',
            'harga_jual' => 'required|numeric:min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'biaya_penyimpanan' => 'required|numeric:min:0',
            'biaya_pemesanan' => 'required|numeric:min:0',
            'deskripsi' => 'nullable|string',
            'exp' => 'required',
        ]);

        $data = $request->all();

        $produk = Produk::findOrFail($id);

        if ($request->hasFile('gambar')) {

            // hapus file lama
            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }

            // simpan file baru
            $data['gambar'] = $request->file('gambar')->store('images', 'public');
        }

        $produk->update(collect($data)->except(['biaya_penyimpanan', 'biaya_pemesanan'])->all());

        $produk->biayaPenyimpanan()->update([
            'biaya' => $validated['biaya_penyimpanan'],
        ]);

        $produk->biayaPemesanan()->update([
            'biaya' => $validated['biaya_pemesanan'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diperbarui',
            'data' => $produk,
        ], 200);

    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        $produk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus',
        ]);
    }

    public function show($id)
    {
        $produk = Produk::query()->with(['biayaPenyimpanan', 'biayaPemesanan'])->find($id);

        if ($produk) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil didapatkan',
                'data' => $produk,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mendapatkan produk',
        ], 500);
    }

    public function kodeProduk($barcode)
    {
        $produk = Produk::where('kode_produk', $barcode)->first();

        if ($produk) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil didapatkan',
                'data' => $produk,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak dapat mendapatkan data produk',
        ], 200);

    }
}
