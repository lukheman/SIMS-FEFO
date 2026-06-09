<?php

namespace App\Livewire;

use App\Enums\MetodePembayaran;
use App\Enums\StatusTransaksi;
use App\Livewire\Forms\PesananForm;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Transaksi;
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
        $this->user = getActiveUser();
        $this->user->load('keranjang');
    }

    public function prosesCheckoutKeranjang() {
        if (empty($this->selectedIdPesanan)) {
            $this->notifyError('Pilih minimal satu pesanan untuk checkout!');
            return;
        }

        return redirect()->route('reseller.checkout-keranjang', [
            'pesanan_ids' => implode(',', $this->selectedIdPesanan)
        ]);
    }

    public function saveToCart() {

        if ($this->form->update()) {
            $this->notifySuccess('Berhasil mengubah pesanan di keranjang');
            $this->closeModal($this->modalId);
        } else {
            $this->notifyError('Stok tidak mencukupi untuk jumlah ini!');
        }

    }

    public function tambahJumlahPesanan() {
        if (!$this->form->tambahJumlahPesanan()) {
            $this->notifyError('Stok tidak mencukupi!');
        }
    }
    public function kurangiJumlahPesanan() {
        $this->form->kurangiJumlahPesanan();
    }


    public function infoPesanan($id) {
        $pesanan = Pesanan::query()->findOrFail($id);

        $this->form->fillFromModel($pesanan);
        $this->form->showMentokWarning = false;

        $this->openModal($this->modalId);
    }

    public function updatedFormJumlah($value) {
        $this->form->showMentokWarning = false;
        if (!$this->form->isStokCukup()) {
            $this->notifyError('Stok tidak mencukupi!');
            $multiplier = $this->form->satuan ? 1 : $this->form->pesanan->produk->tingkat_konversi;
            if ($multiplier > 0) {
                $this->form->jumlah = floor($this->form->pesanan->produk->totalPersediaan() / $multiplier);
            }
        }
    }

    public function updatedFormSatuan($value) {
        $this->form->showMentokWarning = false;
        if (!$this->form->isStokCukup()) {
            $this->notifyError('Stok tidak mencukupi untuk satuan ini!');
            $multiplier = $value ? 1 : $this->form->pesanan->produk->tingkat_konversi;
            if ($multiplier > 0) {
                $this->form->jumlah = floor($this->form->pesanan->produk->totalPersediaan() / $multiplier);
            }
        }
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
