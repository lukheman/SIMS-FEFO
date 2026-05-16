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

    public ?Produk $produk = null;

    public string $modalId = 'modal-pesan-produk';

    public int $satuan = 0;

    public int $jumlahPesanan = 1;

    public bool $showMentokWarning = false;

    public function getSisaStok()
    {
        if (!isset($this->produk)) return 0;
        return $this->produk->totalPersediaan();
    }

    public function isStokCukup($jumlah, $satuan)
    {
        if (!isset($this->produk)) return false;
        
        $multiplier = $satuan ? 1 : $this->produk->tingkat_konversi;
        $totalPcsRequested = $jumlah * $multiplier;
        
        return $this->getSisaStok() >= $totalPcsRequested;
    }

    public function getMaxPesanan()
    {
        if (!isset($this->produk)) return 1;
        $multiplier = $this->satuan ? 1 : $this->produk->tingkat_konversi;
        return $multiplier > 0 ? floor($this->getSisaStok() / $multiplier) : 1;
    }

    public function addToCart() {

        if (!$this->isStokCukup($this->jumlahPesanan, $this->satuan)) {
            $this->notifyError('Stok tidak mencukupi!');
            return;
        }

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
        if ($this->isStokCukup($this->jumlahPesanan + 1, $this->satuan)) {
            $this->jumlahPesanan++;
            $this->showMentokWarning = false;
        } else {
            $this->showMentokWarning = true;
        }
    }
    public function kurangiJumlahPesanan() {
        $this->showMentokWarning = false;
        if ($this->jumlahPesanan > 1) {
            $this->jumlahPesanan--;
            if (!$this->isStokCukup($this->jumlahPesanan, $this->satuan)) {
                $multiplier = $this->satuan ? 1 : $this->produk->tingkat_konversi;
                if ($multiplier > 0) {
                    $this->jumlahPesanan = max(1, floor($this->getSisaStok() / $multiplier));
                }
            }
        }
    }

    public function updatedJumlahPesanan($value) {
        $this->showMentokWarning = false;
        if (!$this->isStokCukup((int) $value, $this->satuan)) {
            $this->notifyError('Stok tidak mencukupi!');
            $multiplier = $this->satuan ? 1 : $this->produk->tingkat_konversi;
            if ($multiplier > 0) {
                $this->jumlahPesanan = max(1, floor($this->getSisaStok() / $multiplier));
            } else {
                $this->jumlahPesanan = 1;
            }
        }
    }

    public function updatedSatuan($value) {
        if (!$this->isStokCukup($this->jumlahPesanan, $this->satuan)) {
            $this->notifyError('Stok tidak mencukupi untuk satuan ini!');
            $multiplier = $this->satuan ? 1 : $this->produk->tingkat_konversi;
            if ($multiplier > 0) {
                $this->jumlahPesanan = max(1, floor($this->getSisaStok() / $multiplier));
            } else {
                $this->jumlahPesanan = 1;
            }
        }
    }

    public function infoProduk($id) {
        $this->produk = Produk::query()->findOrFail($id);
        $this->showMentokWarning = false;
        $this->jumlahPesanan = 1;
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
