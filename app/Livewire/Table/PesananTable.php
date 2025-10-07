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

#[Title('Pesanan')]
class PesananTable extends Component
{
    use WithModal;
    use WithNotify;
    use WithPagination;

    public $kurirList;
    public ?User $selectedKurir = null;

    public $selectedTransaksi;

    public function mount() {
        $this->kurirList = User::query()->where('role', Role::KURIR)->get();
        $this->closeModal('modal-pilih-kurir');
    }

    public function pesananDiterima($id) {
        Transaksi::query()->find($id)->update([
            'status' => StatusTransaksi::DITERIMA,
        ]);

        $this->notifySuccess('Pesanan telah diserahkan ke penerima');
    }


    public function saveKurir() {
    if ($this->selectedTransaksi->status === StatusTransaksi::DIPROSES) {

        foreach ($this->selectedTransaksi->pesanan as $pesanan) {
            $persediaan = $pesanan->produk->persediaan;

            // Kurangi stok sesuai jenis satuan
            $persediaan->jumlah -= $pesanan->satuan
                ? $pesanan->jumlah
                : $pesanan->jumlah_pcs;

            $persediaan->save();

            $this->notifySuccess(
                "Stok produk {$pesanan->produk->nama_produk} telah diperbarui."
            );

            // Catat mutasi barang keluar
            $pesanan->produk->mutasi()->create([
                'jumlah' => $pesanan->jumlah,
                'satuan' => $pesanan->satuan,
                'jenis' => 'keluar',
            ]);

            $this->notifySuccess(
                "Perubahan stok untuk {$pesanan->produk->nama_produk} telah dicatat dalam mutasi."
            );
        }
    }

    // Perbarui status transaksi dan kurir
    $this->selectedTransaksi->update([
        'id_kurir' => $this->selectedKurir->id,
        'status' => StatusTransaksi::DIKIRIM,
    ]);

    $this->closeModal('modal-pilih-kurir');

    $this->notifySuccess(
        "Kurir {$this->selectedKurir->name} telah ditugaskan untuk mengantar pesanan."
    );

    $this->reset('selectedKurir');
}

    public function openModalSelectKurir($id) {
        $this->selectedTransaksi = Transaksi::query()
            ->with('kurir', 'pesanan', 'pesanan.produk', 'pesanan.produk.persediaan', 'pesanan.produk.mutasi')
            ->find($id);

        $this->selectedKurir = $this->selectedTransaksi->kurir;

        $this->openModal('modal-pilih-kurir');
    }

    public function selectKurir($id) {
        $this->selectedKurir = User::query()->find($id);
    }

    public function transaksiLunas($id) {
        Transaksi::query()->find($id)->update([
            'status_pembayaran' => StatusPembayaran::LUNAS,
            'status' => StatusTransaksi::DIPROSES,
        ]);
        $this->notifySuccess('Status Pembayaran diubah menjadi Lunas');
    }

    public function detailTransaksi($id) {
        $this->selectedTransaksi = Transaksil::query()->with('pesanan', 'pesanan.produk')->findOrFail($id);
        $this->openModal('modal-detail-transaksi');
    }

    #[Computed]
    public function transaksiList() {
        $user = getActiveUser();

        if ($user->role === Role::KASIR) {
            return Transaksi::query()
                ->withWhereHas('user')
                ->paginate();
        } elseif($user->role === Role::KURIR) {
            return Transaksi::query()
                ->withWhereHas('user')
                ->where('id_kurir', $user->id)
                ->where('status', '!=', StatusTransaksi::SELESAI)
                ->paginate();

        }

    }


    public function render()
    {
        return view('livewire.table.pesanan-table');
    }
}
