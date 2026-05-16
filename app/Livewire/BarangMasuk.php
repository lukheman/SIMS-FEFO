<?php

namespace App\Livewire;

use App\Enums\Role;
use App\Models\Mutasi;
use App\Models\Produk;
use App\Services\FefoService;
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

    public $jumlah = 1;

    public $jumlah_bal = '';

    public ?string $tanggal_exp = null;

    public ?Mutasi $mutasi; // selected mutasi

    public function mount()
    {
        $user = getActiveUser();

        if ($user->role === Role::PIMPINAN) {
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
        if (getActiveUser()->role === Role::PIMPINAN) {
            abort(403, 'Unauthorized action.');
        }

        $this->mutasi = Mutasi::query()
            ->with('produk', 'produk.persediaan')
            ->find($id);

        $this->deleteConfirmation('Persediaan produk juga akan dikurangi! Yakin untuk menghapus data mutasi ini?');
    }

    #[On('deleteConfirmed')]
    public function deleteSupplyProdukConfirmed()
    {

        // Kembalikan stok ke batch persediaan yang sesuai
        if ($this->mutasi->id_persediaan) {
            $persediaan = $this->mutasi->persediaan;
            if ($persediaan) {
                $persediaan->jumlah -= $this->mutasi->jumlah;
                if ($persediaan->jumlah <= 0) {
                    $persediaan->delete();
                } else {
                    $persediaan->save();
                }
            }
        }

        $this->notifySuccess('Berhasil menghapus data mutasi');
        $this->notifySuccess("Berhasil mengurangi persediaan produk {$this->mutasi->produk->nama_produk} sebanyak {$this->mutasi->jumlah}");

        $this->mutasi->delete();

        $this->reset('mutasi');

    }

    public function addSupplyProduk()
    {
        if (getActiveUser()->role === Role::PIMPINAN) {
            abort(403, 'Unauthorized action.');
        }

        // Gunakan FefoService untuk menambah stok ke batch
        $batch = FefoService::tambahStok($this->produk, $this->jumlah, $this->tanggal_exp);

        // Catat mutasi barang masuk dengan referensi batch
        $this->produk->mutasi()->create([
            'jumlah' => $this->jumlah,
            'jenis' => 'masuk',
            'id_persediaan' => $batch->id,
        ]);

        $this->closeModal('modal-produk');
        $this->notifySuccess('Berhasil menambahkan barang masuk');
        $this->reset('jumlah', 'tanggal_exp');
    }

    public function addProduk($id)
    {

        $this->produk = Produk::query()->with('persediaan', 'mutasi')->find($id);
        
        if ($this->produk && $this->produk->tingkat_konversi > 0) {
            $this->jumlah_bal = $this->jumlah / $this->produk->tingkat_konversi;
        }

        $this->closeModal('modal-cari-produk');
        $this->openModal('modal-produk');

    }

    public function updatedJumlah($value)
    {
        if ($this->produk && $this->produk->tingkat_konversi > 0) {
            $this->jumlah_bal = $value !== '' ? ((float)$value / $this->produk->tingkat_konversi) : '';
        }
    }

    public function updatedJumlahBal($value)
    {
        if ($this->produk && $this->produk->tingkat_konversi > 0) {
            $this->jumlah = $value !== '' ? ((float)$value * $this->produk->tingkat_konversi) : '';
        }
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
    public function produkMasuk()
    {


        return Mutasi::query()
            ->with('produk', 'persediaan')
            ->where('jenis', 'masuk')
            ->paginate(10);

    }

    public function render()
    {
        return view('livewire.barang-masuk');
    }
}
