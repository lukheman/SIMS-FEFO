<?php

namespace App\Livewire;

use App\Enums\StatusTransaksi;
use App\Models\Transaksi as TransaksiModel;
use App\Traits\WithModal;
use App\Traits\WithNotify;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Transaksi')]
class Transaksi extends Component
{
    use WithPagination;
    use WithModal;
    use WithNotify;

    public $selectedTransaksi;

    public $user;

    public function mount() {
        $this->user = getActiveUser();
        $this->user->load('transaksi');
    }

    public function pesananSelesai($id) {
        TransaksiModel::query()->find($id)->update([
            'status' => StatusTransaksi::SELESAI
        ]);

        $this->notifySuccess('Pesanan telah selesai');
    }

    public function detailTransaksi($id) {
        $this->selectedTransaksi = TransaksiModel::query()->with('pesanan', 'pesanan.produk')->findOrFail($id);
        $this->openModal('modal-detail-transaksi');
    }

    #[Computed]
    public function transaksiList() {


        return TransaksiModel::query()
            ->where('id_reseller', $this->user->id)
            ->paginate(10);

    }

    public function render()
    {
        return view('livewire.transaksi');
    }
}
