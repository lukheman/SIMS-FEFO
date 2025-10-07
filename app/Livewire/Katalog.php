<?php

namespace App\Livewire;

use App\Models\Produk;
use App\Traits\WithModal;
use App\Traits\WithNotify;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Katalog Produk')]
class Katalog extends Component
{
    use WithModal;
    use WithNotify;


    public string $search = '';

    public ?Produk $produk;

    public string $modalId = 'modal-pesan-produk';

    public int $satuan = 0;

    public int $jumlahPesanan = 1;

    public function addToCart() {

        // TODO:  cek ketersediaan produk sebelum menambahkan ke keranjang
        $user = getActiveUser();
        $user->loadMissing('keranjang');

        $user->keranjang->pesanan()->create([
            'id_produk' => $this->produk->id,
            'id_reseller' => $user->id,
            'jumlah' => $this->jumlahPesanan,
            'satuan' => $this->satuan
        ]);

        $this->notifySuccess('Berhasil menambahkan pesanan ke keranjang');
        $this->closeModal($this->modalId);

    }

    public function tambahJumlahPesanan() {
        $this->jumlahPesanan++;
    }
    public function kurangiJumlahPesanan() {
        $this->jumlahPesanan++;
    }

    public function infoProduk($id) {
        $this->produk = Produk::query()->findOrFail($id);

        $this->openModal($this->modalId);
    }

    #[Computed]
    public function produkList() {
        return Produk::query()

            ->when($this->search, function($query){

                $query->where('nama_produk', 'like', '%'.$this->search.'%');

            })
            ->get();
    }


    public function render()
    {
        return view('livewire.katalog');
    }
}
