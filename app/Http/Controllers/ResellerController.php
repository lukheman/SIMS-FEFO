<?php

namespace App\Http\Controllers;

use App\Constants\MetodePembayaran;
use App\Constants\StatusTransaksi;
use App\Models\Keranjang;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResellerController extends Controller
{
    public function index()
    {
        $reseller = Auth::guard('reseller')->user();

        $keranjang = Keranjang::where('id_reseller', Auth::guard('reseller')->id())->first() ??
            Keranjang::create([
                'id_reseller' => $reseller->id,
            ]);

        $keranjang = Pesanan::where('id_keranjang_belanja', $keranjang->id)->count();

        $pesanan = Transaksi::where('id_reseller', Auth::guard('reseller')->id())->where('status', '!=', 'selesai')->count();

        return view('reseller.index', [
            'page' => 'Dashboard',
            'keranjang' => $keranjang,
            'pesanan' => $pesanan,
        ]);
    }

    public function katalog(Request $request)
    {
        $query = $request->input('q');

        $produk = Produk::query()
            ->when($query, function ($q) use ($query) {
                $q->where('nama_produk', 'like', "%{$query}%");
            })
            ->get(); // atau ->get() jika tidak ingin pagination

        return view('reseller.katalog', [
            'page' => 'Katalog',
            'produk' => $produk,
            'query' => $query,
        ]);
    }

    public function keranjang()
    {
        $keranjang = Keranjang::where('id_reseller', Auth::guard('reseller')->id())->first();

        if ($keranjang) {
            $pesanan = Pesanan::with(['produk'])->where('id_keranjang_belanja', $keranjang->id)->get();

            return view('reseller.keranjang', [
                'page' => 'Keranjang',
                'pesanan' => $pesanan,
            ]);
        }

        return view('reseller.keranjang', [
            'page' => 'Keranjang',
            'pesanan' => [],
        ]);

    }

    public function transaksi(Request $request)
    {

        $belumbayar = $request->query('belumbayar');
        $pending = $request->query('pending');
        $diproses = $request->query('diproses');
        $dikirim = $request->query('dikirim');
        $selesai = $request->query('selesai');

        $transaksi = Transaksi::where('id_reseller', Auth::guard('reseller')->id());

        if ($belumbayar === '0') {

            $transaksi = $transaksi
                ->where('metode_pembayaran', MetodePembayaran::TRANSFER)
                ->where('status', StatusTransaksi::PENDING);

        } elseif ($pending === '1') {
            $transaksi = $transaksi->where('status', StatusTransaksi::PENDING);
        } elseif ($diproses === '1') {
            $transaksi = $transaksi->where('status', StatusTransaksi::DIPROSES);
        } elseif ($dikirim === '1') {
            $transaksi = $transaksi->where('status', StatusTransaksi::DIKIRIM);
        } elseif ($selesai === '1') {
            $transaksi = $transaksi->whereIn('status', [
                StatusTransaksi::DITERIMA,
                StatusTransaksi::SELESAI,
            ]);
        } else {
            $transaksi = $transaksi
                ->where('metode_pembayaran', MetodePembayaran::TRANSFER)
                ->where('status', StatusTransaksi::PENDING);
        }

        $transaksi = $transaksi->get();

        return view('reseller.transaksi', [
            'page' => 'Transaksi',
            'transaksi' => $transaksi,
        ]);

    }

    public function pengiriman()
    {
        return view('reseller.pengiriman.show', [
            'page' => 'Pengiriman',
        ]);
    }
}
