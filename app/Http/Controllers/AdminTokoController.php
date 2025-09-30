<?php

namespace App\Http\Controllers;

use App\Constants\MetodePembayaran;
use App\Constants\Role;
use App\Constants\StatusTransaksi;
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

            if (! $item->produk->isPersediaanMencukupi($item->jumlah)) {
                return response()->json([
                    'success' => false,
                    'message' => "Persediaan {$item->produk->nama_produk} tidak mencukupi",
                ], 200);
            }

            // kurangi persediaan produk
            if($item->satuan) {
                $item->produk->persediaan->jumlah -= $item->jumlah;
            } else {
                $item->produk->persediaan->jumlah -= $item->jumlah * $item->produk->tingkat_konversi;
            }
            $item->produk->persediaan->save();


            // catat log mutasi
            Mutasi::create([
                'id_produk' => $item->produk->id,
                'jumlah' => $item->jumlah,
                'jenis' => 'keluar',
                'keterangan' => 'Pembelian langsung',
                'satuan' => $item->satuan
            ]);

        }

        return response()->json([
            'success' => true,
            'message' => 'Persediaan pesanan berhasil dikurangi',
        ], 200);
    }

    public function kasir()
    {

        return view('admin_toko.kasir', [
            'page' => 'Kasir',
        ]);

    }

    public function index()
    {
        $pesanan = Transaksi::where('status', StatusTransaksi::PENDING)->count();
        $total_penjualan = Transaksi::where('status', StatusTransaksi::SELESAI)->count();
        $persediaan_barang = Produk::with('persediaan')->get()->sum('persediaan.jumlah');

        return view('admin_toko.index', [
            'page' => 'Dashboard',
            'pesanan' => $pesanan,
            'total_penjualan' => $total_penjualan,
            'persediaan_barang' => $persediaan_barang,
        ]);
    }

    public function pesanan()
    {
        $pesanan = Transaksi::with('user')->whereHas('user')->get();
        $kurir = User::query()->where('role', Role::KURIR)->get();

        return view('admin_toko.pesanan', [
            'page' => 'Pesanan',
            'pesanan' => $pesanan,
            'kurir' => $kurir,
        ]);
    }

    public function persediaan()
    {
        $produk = Produk::all();

        return view('admin_toko.persediaan', [
            'page' => 'Persediaan',
            'produk' => $produk,
        ]);
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

    public function laporanPenjualan()
    {
        return view('admin_toko.laporan-penjualan', [
            'page' => 'Laporan Penjualan',
        ]);
    }

    public function laporanPesanan() {
        return view('admin_toko.laporan-pesanan', [
            'page' => 'Laporan Pesanan'
        ]);
    }
}
