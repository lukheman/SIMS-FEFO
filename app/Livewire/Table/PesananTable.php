<?php

namespace App\Livewire\Table;

use App\Enums\Role;
use App\Enums\StatusPembayaran;
use App\Enums\StatusTransaksi;
use App\Models\Mutasi;
use App\Models\Transaksi;
use App\Models\User;
use App\Traits\WithModal;
use App\Traits\WithNotify;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Transaksi as Transaksil;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

#[Title('Pesanan')]
class PesananTable extends Component
{
    use WithModal;
    use WithNotify;
    use WithPagination;

    public $kurirList;
    public ?User $selectedKurir = null;

    public $selectedTransaksi;

    #[Url]
    public $activeTab = 'semua';

    public function setTab($tab) {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function mount()
    {
        $this->kurirList = User::query()->where('role', Role::KURIR)->get();
        $this->closeModal('modal-pilih-kurir');
    }

    public function pesananDiterima($id)
    {
        Transaksi::query()->find($id)->update([
            'status' => StatusTransaksi::DITERIMA,
        ]);

        $this->notifySuccess('Pesanan telah diserahkan ke penerima');
    }


    public function saveKurir()
    {
        try {
            \Illuminate\Support\Facades\DB::transaction(function () {
                if ($this->selectedTransaksi->status === StatusTransaksi::DIPROSES) {

                    foreach ($this->selectedTransaksi->pesanan as $pesanan) {
                        // Hitung jumlah unit kecil yang perlu dikurangi
                        $jumlahKurangi = $pesanan->satuan
                            ? $pesanan->jumlah
                            : $pesanan->jumlah_pcs;

                        // Kurangi stok menggunakan metode FEFO
                        $hasilFefo = \App\Services\FefoService::kurangiStok($pesanan->produk, $jumlahKurangi);

                        // Catat mutasi barang keluar per batch
                        foreach ($hasilFefo as $hasil) {
                            $pesanan->produk->mutasi()->create([
                                'jumlah' => $hasil['jumlah_dikurangi'],
                                'satuan' => $pesanan->satuan,
                                'jenis' => 'keluar',
                                'id_persediaan' => $hasil['persediaan']->id,
                            ]);
                        }
                    }
                }

                // Perbarui status transaksi dan kurir
                $this->selectedTransaksi->update([
                    'id_kurir' => $this->selectedKurir->id,
                    'status' => StatusTransaksi::DIKIRIM,
                ]);
            });

            $this->closeModal('modal-pilih-kurir');

            $this->notifySuccess(
                "Kurir {$this->selectedKurir->name} telah ditugaskan untuk mengantar pesanan."
            );

            $this->reset('selectedKurir');

        } catch (\Exception $e) {
            $this->notifyError($e->getMessage());
        }
    }

    public function openModalSelectKurir($id)
    {
        $this->selectedTransaksi = Transaksi::query()
            ->with('kurir', 'pesanan', 'pesanan.produk', 'pesanan.produk.persediaan', 'pesanan.produk.mutasi')
            ->find($id);

        $this->selectedKurir = $this->selectedTransaksi->kurir;

        $this->openModal('modal-pilih-kurir');
    }

    public function selectKurir($id)
    {
        $this->selectedKurir = User::query()->find($id);
    }

    public function transaksiLunas($id)
    {
        Transaksi::query()->find($id)->update([
            'status_pembayaran' => StatusPembayaran::LUNAS,
            'status' => StatusTransaksi::DIPROSES,
        ]);
        $this->notifySuccess('Status Pembayaran diubah menjadi Lunas');
    }

    public function detailTransaksi($id)
    {
        $this->selectedTransaksi = Transaksil::query()->with('pesanan', 'pesanan.produk')->findOrFail($id);
        $this->openModal('modal-detail-transaksi');
    }

    #[Computed]
    public function transaksiList()
    {
        $user = getActiveUser();

        if ($user->role === Role::KASIR) {
            $query = Transaksi::query()->withWhereHas('user');
        } elseif ($user->role === Role::KURIR) {
            $query = Transaksi::query()
                ->withWhereHas('user')
                ->where('id_kurir', $user->id)
                ->where('status', '!=', StatusTransaksi::SELESAI);
        } else {
            $query = Transaksi::query();
        }

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


    public function render()
    {
        return view('livewire.table.pesanan-table');
    }
}
