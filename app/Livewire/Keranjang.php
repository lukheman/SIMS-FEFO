<?php

namespace App\Livewire;

use App\Enums\MetodePembayaran;
use App\Enums\StatusTransaksi;
use App\Livewire\Forms\PesananForm;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\TransaksiMode;
use App\Traits\WithConfirmation;
use App\Traits\WithModal;
use App\Traits\WithNotify;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Keranjang as KeranjangModel;

#[Title('Keranjang')]
class Keranjang extends Component
{
    use WithPagination;
    use WithNotify;
    use WithConfirmation;
    use WithModal;

    public $user;

    public ?MetodePembayaran $metode_pembayaran;

    public PesananForm $form;

    public string $modalId = 'modal-pesan-produk';

    public array $selectedIdPesanan = [];

    public $selectAll = false;

    public function updatedSelectAll($value) {
        if($value) {
            $this->selectedIdPesanan = $this->getPluckIdPesanan();

        } else {
            $this->selectedIdPesanan = [];
        }
    }

    public function updatedSelectedIdPesanan() {
        // $this->selectedIdPesanan = $this->getPluckIdPesanan();
         $a = $this->selectedIdPesanan;
        $b = $this->getPluckIdPesanan();

        sort($a);
        sort($b);

        $this->selectAll = ($a == $b);
    }

    public function getPluckIdPesanan(): array {
        return Pesanan::query()
                ->where('id_keranjang_belanja', $this->user->keranjang->id)
                ->get()
                ->pluck('id')
                ->toArray();

    }

    public function mount() {
        $this->metode_pembayaran = MetodePembayaran::TRANSFER;
        $this->user = getActiveUser();
        $this->user->load('keranjang');
    }

    public function checkout() {

        $transaksi = TransaksiMode::query()->create([
            'id_reseller' => $this->user->id,
            'metode_pembayaran' => $this->metode_pembayaran,
            'status' => $this->metode_pembayaran === MetodePembayaran::COD ? StatusTransaksi::DIPROSES : StatusTransaksi::PENDING,
        ]);

        // Update pesanan terkait
        Pesanan::query()->whereIn('id', $this->selectedIdPesanan)
            ->where('id_keranjang_belanja', $this->user->keranjang->id)
            ->update([
                'id_transaksi' => $transaksi->id,
                'id_keranjang_belanja' => null,
            ]);

        $this->notifySuccess('Berhasil melakukan checkout');
        $this->closeModal('modal-metode-pembayaran');
        $this->reset('selectedIdPesanan', 'selectAll');


    }

    public function setMetodePembayaran($metode) {
        $this->metode_pembayaran = MetodePembayaran::from($metode);
    }

    public function saveToCart() {

        // TODO:  cek ketersediaan produk sebelum menambahkan ke keranjang
        $this->form->update();

        $this->notifySuccess('Berhasil menambahkan pesanan ke keranjang');
        $this->closeModal($this->modalId);

    }

    public function tambahJumlahPesanan() {
        // TODO: buat validasi apakah persediaan produk mencukupi atau tidak
        $this->form->tambahJumlahPesanan();
    }
    public function kurangiJumlahPesanan() {
        $this->form->kurangiJumlahPesanan();
    }


    public function infoPesanan($id) {
        $pesanan = Pesanan::query()->findOrFail($id);

        $this->form->fillFromModel($pesanan);


        $this->openModal($this->modalId);
    }

    #[Computed]
    public function pesananList() {
        return Pesanan::query()
            ->with('produk')
            ->where('id_keranjang_belanja', $this->user->keranjang->id)
            ->paginate(10);
    }

    public function delete($id): void
    {
        $pesanan = Pesanan::query()->findOrFail($id);

        $this->form->fillFromModel($pesanan);
        $this->deleteConfirmation('Yakin untuk menghapus pesanan ini dari keranjang ?');
    }

    #[On('deleteConfirmed')]
    public function deleteConfirmed(): void
    {
        $this->notifySuccess("Berhasil menghapus {$this->form->pesanan->produk->nama_produk}");
        $this->form->delete();
    }

    public function render()
    {
        return view('livewire.keranjang');
    }
}
