<?php

namespace App\Livewire\Table;

use App\Models\Mutasi;
use App\Traits\WithConfirmation;
use App\Traits\WithNotify;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Penjualan')]
class PenjualanTable extends Component
{
    use WithPagination;
    use WithConfirmation;
    use WithNotify;

    public ?Mutasi $penjualan;

    public function deletePenjualan($id): void
    {
        $this->penjualan = Mutasi::query()
            ->with('produk', 'produk.persediaan')
            ->find($id);

        $this->deleteConfirmation('Persediaan produk juga akan dikurangi! Yakin untuk menghapus data mutasi ini?');
    }

    #[On('deleteConfirmed')]
    public function deletePenjualanConfirmed() {

        // $persediaan = $this->penjualan()->produk->persediaan;
        //
        // if($persediaan->jumlah > 0) {
        //     $persediaan->jumlah -= $this->penjualan->jumlah;
        //     $persediaan->save();
        // }


        $this->notifySuccess('Berhasil menghapus data penjualan');
        // $this->notifySuccess("Berhasil mengurangi persediaan produk {$this->penjualan->produk->nama_produk} sebanyak {$this->penjualan->jumlah}");

        $this->penjualan->delete();

        $this->reset('penjualan');

    }

    #[Computed]
    public function penjualanList() {
        return Mutasi::query()
            ->with('produk')
            ->where('jenis', 'keluar')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.table.penjualan-table');
    }
}
