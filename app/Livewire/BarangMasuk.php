<?php

namespace App\Livewire;

use App\Enums\Role;
use App\Models\Mutasi;
use App\Models\Produk;
use App\Traits\WithConfirmation;
use App\Traits\WithModal;
use App\Traits\WithNotify;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Barang Masuk')]
class BarangMasuk extends Component
{

    use WithModal;
    use WithNotify;
    use WithConfirmation;

    public bool $isLaporan = false;

    public string $search = '';

    public ?Produk $produk;

    public int $jumlah = 1;

    public ?Mutasi $mutasi; // selected mutasi

    public function mount() {
        $user = getActiveUser();

        if($user->role === Role::PIMPINAN) {
            $this->isLaporan = true;
        } else {
            $this->isLaporan = false;
        }

    }

    public function searchProdukModal(): void
    {
        $this->openModal('modal-cari-produk');
    }


    public function deleteSupplyProduk($id): void
    {
        $this->mutasi = Mutasi::query()
            ->with('produk', 'produk.persediaan')
            ->find($id);

        $this->deleteConfirmation('Persediaan produk juga akan dikurangi! Yakin untuk menghapus data mutasi ini?');
    }

    #[On('deleteConfirmed')]
    public function deleteSupplyProdukConfirmed() {

        $persediaan = $this->mutasi->produk->persediaan;

        if($persediaan->jumlah > 0) {
            $persediaan->jumlah -= $this->mutasi->jumlah;
            $persediaan->save();
        }


        $this->notifySuccess('Berhasil menghapus data mutasi');
        $this->notifySuccess("Berhasil mengurangi persediaan produk {$this->mutasi->produk->nama_produk} sebanyak {$this->mutasi->jumlah}");

        $this->mutasi->delete();

        $this->reset('mutasi');

    }

    public function addSupplyProduk() {

        $persediaan = $this->produk->persediaan;
        $persediaan->jumlah += $this->jumlah;
        $persediaan->save();

        $this->produk->mutasi()->create([
            'jumlah' => $this->jumlah,
            'jenis' => 'masuk',
        ]);

        $this->closeModal('modal-produk');
        $this->notifySuccess('Berhasil menambahkan barang masuk');
        $this->reset();
    }

    public function addProduk($id) {

        $this->produk = Produk::query()->with('persediaan', 'mutasi')->find($id);
        $this->closeModal('modal-cari-produk');
        $this->openModal('modal-produk');

    }

    #[Computed]
    public function produkList()
    {
        return Produk::query()
            ->when($this->search, function ($query) {
                $query->where('nama_produk', 'like', '%' . $this->search . '%')
                      ->orWhere('kode_produk', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);
    }

    #[Computed]
    public function produkMasuk() {


        return Mutasi::query()
            ->with('produk')
            ->where('jenis', 'masuk')
            ->paginate(10);

    }

    public function render()
    {
        return view('livewire.barang-masuk');
    }
}
