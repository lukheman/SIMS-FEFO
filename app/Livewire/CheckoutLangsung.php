<?php

namespace App\Livewire;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\Pesanan;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Traits\WithNotify;

#[Title('Checkout')]
class CheckoutLangsung extends Component
{
    use WithNotify;

    public $produk_id;
    public $jumlah;
    public $satuan;
    public $produk;
    public $pesanUntukPenjual = '';
    
    public function mount()
    {
        $this->produk_id = request()->query('produk_id');
        $this->jumlah = request()->query('jumlah', 1);
        $this->satuan = request()->query('satuan', 0);
        
        $this->produk = Produk::findOrFail($this->produk_id);
    }

    public function getHargaSatuanProperty()
    {
        return $this->satuan == 1 ? $this->produk->harga_jual_unit_kecil : $this->produk->harga_jual;
    }

    public function getNamaSatuanProperty()
    {
        return $this->satuan == 1 ? $this->produk->unit_kecil : $this->produk->unit_besar;
    }

    public function getSubtotalProperty()
    {
        return $this->hargaSatuan * $this->jumlah;
    }

    public function getTotalPembayaranProperty()
    {
        return $this->subtotal;
    }

    public function buatPesanan()
    {
        // Pengecekan stok (seperti di Katalog)
        $multiplier = $this->satuan ? 1 : $this->produk->tingkat_konversi;
        $totalPcsRequested = $this->jumlah * $multiplier;
        
        if ($this->produk->totalPersediaan() < $totalPcsRequested) {
            $this->notifyError('Stok tidak mencukupi!');
            return;
        }

        $user = getActiveUser();

        $transaksi = Transaksi::create([
            'id_reseller' => $user->id,
            'metode_pembayaran' => \App\Enums\MetodePembayaran::TRANSFER,
            'status' => \App\Enums\StatusTransaksi::PENDING,
            // 'catatan' => $this->pesanUntukPenjual // Jika di masa depan ada kolom catatan
        ]);

        Pesanan::create([
            'id_produk' => $this->produk->id,
            'id_reseller' => $user->id,
            'id_transaksi' => $transaksi->id,
            'jumlah' => $this->jumlah,
            'satuan' => $this->satuan
        ]);

        $this->notifySuccess('Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
        return redirect()->route('reseller.transaksi');
    }

    public function render()
    {
        $user = getActiveUser();
        return view('livewire.checkout-langsung', [
            'user' => $user
        ]);
    }
}
