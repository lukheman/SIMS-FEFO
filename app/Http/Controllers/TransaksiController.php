<?php

namespace App\Http\Controllers;

use App\Constants\MetodePembayaran;
use App\Constants\Role;
use App\Constants\StatusPembayaran;
use App\Constants\StatusTransaksi;
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'metode_pembayaran' => ['required', Rule::enum(MetodePembayaran::class)],
            'pesanan_dipilih' => 'required|string',  // Menambahkan validasi untuk memastikan ini string
        ]);

        $pesanan_dipilih = $request->input('pesanan_dipilih');
        $pesanan_dipilih = explode(',', $pesanan_dipilih);

        $keranjang = Keranjang::where('id_reseller', Auth::guard('reseller')->id())->first();

        $pesanan = Pesanan::whereIn('id', $pesanan_dipilih)->with('produk')->get();

        $totalHarga = 0;

        foreach ($pesanan as $item) {
            $totalHarga += $item->total_harga;
        }

        // cek apakah harga >= 200.000
        if($totalHarga < 500000) {
            return response()->json([
                'success' => false,
                'message' => 'Total harga pesanan Anda belum mencapai batas minimum sebesar Rp. 500.000.',
            ], 200);
        }


        $transaksi = Transaksi::create([
            'id_reseller' => Auth::guard('reseller')->id(),
            'metode_pembayaran' => $request->metode_pembayaran,
            'status' => MetodePembayaran::from($validated['metode_pembayaran']) === MetodePembayaran::COD ? StatusTransaksi::DIPROSES : StatusTransaksi::PENDING,
        ]);

        // Update pesanan terkait
        Pesanan::whereIn('id', $pesanan_dipilih)
            ->where('id_keranjang_belanja', $keranjang->id)
            ->update([
                'id_transaksi' => $transaksi->id,
                'id_keranjang_belanja' => null,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil melakukan pemesanan barang',
            'transaksi' => $transaksi,
        ], 200);

    }

    public function buktiPembayaran(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        $transaksi = Transaksi::findOrFail($id);

        if ($request->hasFile('bukti_pembayaran')) {
            // hapus file lama
            if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                Storage::disk('public')->delete($transaksi->bukti_pembayaran);
            }

            // simpan file baru
            $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        }

        $transaksi->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengirim bukti pembayaran',
            'data' => $transaksi,
        ], 200);

    }

    public function updateKurir(Request $request, $id)
    {

        $request->validate([
            'id_kurir' => ['required', 'exists:users,id'],
        ]);

        if (Role::from(Auth::user()->role) === Role::ADMINTOKO) {

            $transaksi = Transaksi::findOrFail($id);
            $kurir = User::find($request->id_kurir);

            if ($transaksi->status === StatusTransaksi::DIPROSES) {
                $transaksi->status = StatusTransaksi::DIKIRIM;

                $this->kurangiPersediaan($transaksi);
            }
            // jika lunas maka status pengiriman otomatis jadi diproses
            $transaksi->update(['id_kurir' => $request->id_kurir]);
            $transaksi->save();

            return response()->json([
                'success' => true,
                'message' => "Pesanan diserahkan kepada {$kurir->name}",
            ], 200);

        }

        return response()->json([
            'success' => false,
            'message' => 'Aksi tidak valid untuk peran Anda.',
        ], 403);

    }

    public function updateStatusPembayaran(Request $request, $id)
    {

        $request->validate([
            'status_pembayaran' => ['required', Rule::enum(StatusPembayaran::class)],
        ]);

        $transaksi = Transaksi::findOrFail($id);

        if (Role::from(Auth::user()->role) === Role::ADMINTOKO) {
            if (StatusPembayaran::from($request->status_pembayaran) === StatusPembayaran::LUNAS) {

                // jika lunas maka status pengiriman otomatis jadi diproses
                $transaksi->status_pembayaran = StatusPembayaran::LUNAS;
                $transaksi->status = StatusTransaksi::DIPROSES;
                $transaksi->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Status pembayaran berhasil diubah menjadi lunas',
                ], 200);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Aksi tidak valid untuk peran Anda.',
        ], 403);

    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(StatusTransaksi::class)],
        ]);

        $transaksi = Transaksi::findOrFail($id);

        $user_role = Auth::guard('reseller')->user()->role ?? Auth::user()->role;

        if (Role::from($user_role) === Role::ADMINTOKO) {
            return $this->handleAdminTokoActions($request, $transaksi);
        } elseif (Role::from($user_role) === Role::KURIR) {
            return $this->handleKurirActions($request, $transaksi);
        } elseif (Role::from($user_role) === Role::RESELLER) {
            return $this->handleResellerActions($request, $transaksi);
        }

        return response()->json([
            'success' => false,
            'message' => 'Aksi tidak valid untuk peran Anda.',
        ], 403);
    }

    private function handleAdminTokoActions(Request $request, Transaksi $transaksi)
    {

        $status = StatusTransaksi::from($request->status);

        if ($status === StatusTransaksi::DIPROSES) {
            return $this->kurangiPersediaan($transaksi);
        } elseif ($status === StatusTransaksi::DIKIRIM) {
            return $this->kirimPesanan($transaksi);
        }

        return response()->json([
            'success' => false,
            'message' => 'Status tidak valid untuk admin toko.',
        ], 400);

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
                'keterangan' => 'Pengiriman pesanan',
                'satuan' => false
            ]);

        }

        return response()->json([
            'success' => true,
            'message' => 'Persediaan pesanan berhasil dikurangi',
        ], 200);
    }

    private function kirimPesanan(Transaksi $transaksi)
    {
        if ($transaksi->status === StatusTransaksi::DIKIRIM) {

            return response()->json([
                'success' => true,
                'message' => 'Pesanan telah diserahkan ke kurir',
            ], 200);

        }

        if ($transaksi->status !== StatusTransaksi::DIPROSES) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan belum diproses',
            ], 200);
        }

        $transaksi->status = StatusTransaksi::DIKIRIM;
        $transaksi->save();

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dikirim',
        ], 200);
    }

    private function handleKurirActions(Request $request, Transaksi $transaksi)
    {
        $transaksi = Transaksi::with(['user', 'user.reseller_detail'])->findOrFail($transaksi->id);
        /* FIX: this */

        if ($transaksi->status === StatusTransaksi::DIKIRIM && $request->status === StatusTransaksi::STATUS['dibayar']) {

            $transaksi->status = StatusTransaksi::STATUS['dibayar'];
            $transaksi->save();

        }

        $transaksi['total_harga'] = $transaksi->totalHarga();

        $message = $transaksi->status === StatusTransaksi::STATUS['dibayar'] ?
            'Pembayaran telah dibayar pada '.$transaksi->updated_at :
            'Transaksi selesai dibayar';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $transaksi,
        ], 200);

    }

    private function handleResellerActions(Request $request, Transaksi $transaksi)
    {

        if (StatusTransaksi::from($request->status) === StatusTransaksi::DITERIMA) {
            $transaksi->status = StatusTransaksi::SELESAI;
            $transaksi->save();

        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil diterima',
            'data' => $transaksi,
        ], 200);

    }

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

    public function detail(Request $request)
    {

        $request->validate([
            'id_transaksi' => 'required|exists:transaksi,id',
        ]);

        $pesanan = Pesanan::with(['produk'])->where('id_transaksi', $request->id_transaksi)->get();

        if ($pesanan->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mendapatkan detail transaksi',
                'data' => $pesanan,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mendapatkan detail transaksi',
        ], 500);

    }
}
