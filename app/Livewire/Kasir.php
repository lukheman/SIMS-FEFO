<?php

namespace App\Livewire;

use App\Constants\MetodePembayaran;
use App\Enums\StatusTransaksi;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Traits\WithConfirmation;
use App\Traits\WithNotify;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Kasir extends Component
{
    use WithConfirmation;
    use WithNotify;

    public string $search = '';
    public array $daftarBelanja = [];
    public $bayar = 0;
    public $showNextTransactionButton = false;

    #[Computed]
    public function produkList() {
        return Produk::query()
            ->with('persediaan')
            ->when($this->search, function($query){

                $query->where('nama_produk', 'like', '%'.$this->search.'%')
                ->orWhere('kode_produk', 'like', '%'.$this->search.'%');

            })
            ->latest()
            ->get()
            ->take(3);
    }

    public function addProduk($id)
    {
        $produk = Produk::query()->with('persediaan')->find($id);
        if (!$produk) return;

        // Jika produk sudah ada di daftar, tambah qty
        foreach ($this->daftarBelanja as &$item) {
            if ($item['id'] === $produk->id) {

                if($item['qty'] < $produk->persediaan->jumlah) {

                    $item['qty'] += 1;
                    $item['total'] = $item['qty'] * $item['harga'];
                    return;
                } else {
                    $this->notifyError('Persediaan tidak mencukupi');
                    return;
                }
            }
        }

        // Jika belum ada, tambahkan baru
        $this->daftarBelanja[] = [
            'id'    => $produk->id,
            'kode'  => $produk->kode_produk,
            'nama'  => $produk->nama_produk,
            'harga' => $produk->harga_jual_unit_kecil,
            'qty'   => 1,
            'total' => $produk->harga_jual_unit_kecil,
        ];
    }

    public function deleteProduk($id)
    {
        $this->daftarBelanja = array_values(array_filter($this->daftarBelanja, fn($item) => $item['id'] !== $id));
    }

    #[Computed]
    public function totalItem()
    {
        return collect($this->daftarBelanja)->sum('qty');
    }

    #[Computed]
    public function totalHarga()
    {
        return collect($this->daftarBelanja)->sum('total');
    }

    public function selesaikanTransaksi()
    {
        if (empty($this->daftarBelanja)) {
            session()->flash('error', 'Daftar belanja kosong!');
            return;
        }

        if ($this->bayar < $this->totalHarga) {
            session()->flash('error', 'Uang bayar kurang!');
            return;
        }

        // buat transaksi
        $transaksi = Transaksi::query()->create([
            'metode_pembayaran' => MetodePembayaran::TUNAI,
            'status' => StatusTransaksi::SELESAI,
        ]);

        foreach($this->daftarBelanja as $item) {
            $pesanan = Pesanan::query()->create([
                'id_produk' => $item['id'],
                'jumlah' => $item['qty'],
                'id_transaksi' => $transaksi->id,
                'satuan' => 1
            ])->load('produk', 'produk.persediaan');

            // kurangi persediaan barang setelah transaksi dibuat
            $pesanan->produk->persediaan->jumlah -= $item['qty'];
            $pesanan->produk->persediaan->save();

        }

        $this->showNextTransactionButton = true;
        $this->notifySuccess('Transaksi berhasil disimpan!');
    }

    public function clearTransaction() {
        // Reset keranjang
        $this->daftarBelanja = [];
        $this->bayar = 0;
    }

    public function render()
    {
        return view('livewire.kasir');
    }
}
