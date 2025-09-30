<?php

namespace App\Http\Controllers;

use App\Constants\StatusPembayaran;
use App\Constants\StatusTransaksi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KurirController extends Controller
{
    public function index()
    {
        $dikirim = Transaksi::where('status', 'dikirim')->count();
        $diproses = Transaksi::where('status', 'diproses')->count();

        return view('kurir.index', [
            'page' => 'Dashboard',
            'dikirim' => $dikirim,
            'diproses' => $diproses,
        ]);
    }

    public function pesanan()
    {
        $pesanan = Transaksi::with(['user'])->where('status', 'dikirim')->where('id_kurir', auth()->user()->id)->get();

        return view('kurir.pesanan', [
            'page' => 'Pesanan',
            'pesanan' => $pesanan,
        ]);
    }

    public function konfirmasiPembayaranPage()
    {
        return view('kurir.konfirmasi-pembayaran', [
            'page' => 'Konfirmasi Pembayaran',
        ]);
    }

    public function konfirmasiPembayaran(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => ['required', Rule::enum(StatusPembayaran::class)],
        ]);

        $transaksi = Transaksi::with('user')->findOrFail($id);

        if ($transaksi->id_kurir !== auth()->user()->id) {

            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak valid, mohon periksa apakah pesanan telah diberikan kepada Anda',
            ], 200);

        }

        if ($transaksi->status === StatusTransaksi::DIKIRIM) {
            $transaksi->status = StatusTransaksi::DITERIMA;
            $transaksi->status_pembayaran = StatusPembayaran::LUNAS;
            $transaksi->save();
        }

        $transaksi['total_harga'] = $transaksi->total_harga;

        return response()->json([
            'success' => true,
            'message' => 'Pesanan telah diserahkan ke pembeli',
            'data' => $transaksi,
        ], 200);

    }
}
