<?php

namespace App\Livewire;

use App\Models\Pesanan;
use App\Models\Transaksi;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Traits\WithNotify;
use App\Enums\MetodePembayaran;
use App\Enums\StatusTransaksi;

#[Title('Checkout Pesanan')]
class CheckoutKeranjang extends Component
{
    use WithNotify;

    public $pesanan_ids = [];
    public $pesananList;
    public $pesanUntukPenjual = '';
    public string $metode_pembayaran = 'transfer';
    
    public function mount()
    {
        $ids = request()->query('pesanan_ids', '');
        if (empty($ids)) {
            return redirect()->route('reseller.keranjang');
        }

        $this->pesanan_ids = explode(',', $ids);
        
        $user = getActiveUser();
        $user->load('keranjang');
        
        if (!$user->keranjang) {
            return redirect()->route('reseller.keranjang');
        }

        $this->pesananList = Pesanan::query()
            ->with('produk')
            ->whereIn('id', $this->pesanan_ids)
            ->where('id_keranjang_belanja', $user->keranjang->id)
            ->get();

        if ($this->pesananList->isEmpty()) {
            return redirect()->route('reseller.keranjang');
        }
    }

    public function getSubtotalProperty()
    {
        $total = 0;
        foreach($this->pesananList as $pesanan) {
            $harga = $pesanan->satuan == 1 ? $pesanan->produk->harga_jual_unit_kecil : $pesanan->produk->harga_jual;
            $total += ($harga * $pesanan->jumlah);
        }
        return $total;
    }

    public function getTotalPembayaranProperty()
    {
        return $this->subtotal;
    }

    public function buatPesanan()
    {
        // Pengecekan stok
        foreach($this->pesananList as $pesanan) {
            $multiplier = $pesanan->satuan ? 1 : $pesanan->produk->tingkat_konversi;
            $totalPcsRequested = $pesanan->jumlah * $multiplier;
            if ($pesanan->produk->totalPersediaan() < $totalPcsRequested) {
                $this->notifyError("Stok produk {$pesanan->produk->nama_produk} tidak mencukupi!");
                return;
            }
        }

        $user = getActiveUser();

        $metode = MetodePembayaran::from($this->metode_pembayaran);

        $transaksi = Transaksi::create([
            'id_reseller' => $user->id,
            'metode_pembayaran' => $metode,
            'status' => $metode === MetodePembayaran::COD ? StatusTransaksi::DIPROSES : StatusTransaksi::PENDING,
            // 'catatan' => $this->pesanUntukPenjual // Jika nanti ada
        ]);

        Pesanan::query()->whereIn('id', $this->pesanan_ids)
            ->where('id_keranjang_belanja', $user->keranjang->id)
            ->update([
                'id_transaksi' => $transaksi->id,
                'id_keranjang_belanja' => null,
            ]);

        $this->notifySuccess('Pesanan berhasil dibuat. Silakan cek di menu Transaksi.');
        return redirect()->route('reseller.transaksi');
    }

    public function render()
    {
        $user = getActiveUser();
        return view('livewire.checkout-keranjang', [
            'user' => $user
        ]);
    }
}
