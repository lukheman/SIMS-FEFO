<?php

namespace App\Livewire;

use App\Enums\StatusTransaksi;
use App\Models\Transaksi as Transaksil;
use App\Traits\WithModal;
use App\Traits\WithNotify;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Enums\MetodePembayaran;

#[Title('Transaksi')]
class Transaksi extends Component
{
    use WithPagination;
    use WithModal;
    use WithNotify;

    public $selectedTransaksi;

    public $user;

    #[Url]
    public $activeTab = 'semua';

    public function mount() {
        $this->user = getActiveUser();
        $this->user->load('transaksi');
    }

    public function pesananSelesai($id) {
        Transaksil::query()->find($id)->update([
            'status' => StatusTransaksi::SELESAI
        ]);

        $this->notifySuccess('Pesanan telah selesai');
    }

    public function detailTransaksi($id) {
        $this->selectedTransaksi = Transaksil::query()->with('pesanan', 'pesanan.produk')->findOrFail($id);
        $this->openModal('modal-detail-transaksi');
    }

    #[Computed]
    public function transaksiList() {

        $query = Transaksil::query()->where('id_reseller', $this->user->id);

        switch ($this->activeTab) {
            case 'pending':
                $query->where('status', StatusTransaksi::PENDING);
                break;
            case 'diproses':
                $query->where('status', StatusTransaksi::DIPROSES);
                break;
            case 'dikirim':
                $query->where('status', StatusTransaksi::DIKIRIM);
                break;
            case 'ditolak':
                $query->where('status', StatusTransaksi::DITOLAK);
                break;
            case 'diterima':
                $query->where('status', StatusTransaksi::DITERIMA);
                break;
            case 'selesai':
                $query->where('status', StatusTransaksi::SELESAI);
                break;
            case 'batal':
                $query->where('status', StatusTransaksi::BATAL);
                break;
        }

        return $query->latest('tanggal')->paginate(10);

    }

    public function setTab($tab) {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.transaksi');
    }
}
