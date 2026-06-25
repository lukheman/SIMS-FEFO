<?php

namespace App\Http\Controllers;

use App\Enums\MetodePembayaran;
use App\Enums\Role;
use App\Enums\StatusTransaksi;
use App\Helpers\QrcodeHelper;
use App\Models\Mutasi;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminTokoController extends Controller
{
    public function transaksi(Request $request)
    {
        $transaksi = Transaksi::create([
            'metode_pembayaran' => MetodePembayaran::TUNAI,
            'status' => StatusTransaksi::SELESAI,
        ]);

        foreach ($request->pesanan as $barcode => $value) {
            $produk = Produk::where('kode_produk', $barcode)->firstOrFail();

            // Cek persediaan produk berdasarkan jumlah dan satuan
            $permintaan = $value['satuan'] === $produk->unit_kecil ? $value['jumlah'] : $value['jumlah'] * $produk->tingkat_konversi;
            if (!$produk->isPersediaanMencukupi($permintaan)) {
                return response()->json([
                    'success' => false,
                    'message' => "Persediaan {$produk->nama_produk} tidak cukup untuk {$value['jumlah']} {$value['satuan']}",
                ], 200);
            }

            // Tentukan harga berdasarkan satuan
            $harga = $value['satuan'] === $produk->unit_kecil ? $produk->harga_jual_unit_kecil : $produk->harga_jual;

            // Kalkulasi total harga
            $total_harga = $harga * $value['jumlah'];

            // Buat pesanan
            Pesanan::create([
                'id_produk' => $produk->id,
                'jumlah' => $value['jumlah'],
                'total_harga' => $total_harga,
                'id_transaksi' => $transaksi->id,
                'satuan' => $value['satuan'] === $produk->unit_kecil ? 1 : 0,
            ]);
        }

        // Kurangi persediaan setelah semua pesanan dibuat
        $this->kurangiPersediaan($transaksi);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dilakukan',
            'transaksi' => $transaksi,
        ], 200);
    }

    private function kurangiPersediaan(Transaksi $transaksi)
    {
        $pesanan = Pesanan::with(['produk', 'produk.persediaan'])->where('id_transaksi', $transaksi->id)->get();

        foreach ($pesanan as $item) {

            // Hitung jumlah unit kecil yang perlu dikurangi
            $jumlahKurangi = $item->satuan ? $item->jumlah : $item->jumlah * $item->produk->tingkat_konversi;

            if (!$item->produk->isPersediaanMencukupi($jumlahKurangi)) {
                return response()->json([
                    'success' => false,
                    'message' => "Persediaan {$item->produk->nama_produk} tidak mencukupi",
                ], 200);
            }

            // Kurangi stok menggunakan metode FEFO
            $hasilFefo = \App\Services\FefoService::kurangiStok($item->produk, $jumlahKurangi);

            // Catat log mutasi per batch
            foreach ($hasilFefo as $hasil) {
                Mutasi::create([
                    'id_produk' => $item->produk->id,
                    'jumlah' => $hasil['jumlah_dikurangi'],
                    'jenis' => 'keluar',
                    'keterangan' => 'Pembelian langsung',
                    'satuan' => $item->satuan,
                    'id_persediaan' => $hasil['persediaan']->id,
                ]);
            }

        }

        return response()->json([
            'success' => true,
            'message' => 'Persediaan pesanan berhasil dikurangi',
        ], 200);
    }
    public function nota(Request $request)
    {

        $request->validate([
            'id_transaksi' => 'required|exists:transaksi,id',
        ]);

        $pesanan = Pesanan::with(['produk'])->where('id_transaksi', $request->id_transaksi)->get();
        $pengirim = Auth::user();
        $penerima = Transaksi::with('user')->find($request->id_transaksi)->user;

        $qrcode = QrcodeHelper::getQrcodeString($request->id_transaksi);

        return view('invoices.nota', [
            'qrcode' => $qrcode,
            'pesanan' => $pesanan,
            'pengirim' => $pengirim,
            'penerima' => $penerima,
        ]);
    }
}
