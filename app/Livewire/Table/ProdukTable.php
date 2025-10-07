<?php

namespace App\Livewire\Table;

use App\Livewire\Forms\ProdukForm;
use App\Models\Produk;
use App\Traits\WithConfirmation;
use App\Traits\WithModal;
use App\Traits\WithNotify;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Enums\State;

#[Title('Produk')]
class ProdukTable extends Component
{
    use WithConfirmation;
    use WithModal;
    use WithNotify;
    use WithPagination;
    use WithFileUploads;

    public ProdukForm $form;

    public string $modalFormState = 'create';

    public string $search = '';

    public string $modalId = 'modal-produk';

    public $currentState = State::CREATE;

    #[Computed]
    public function produkList()
    {
        return Produk::query()
            ->with('persediaan')
            ->when($this->search, function ($query) {
                $query->where('nama_produk', 'like', '%' . $this->search . '%')
                      ->orWhere('kode_produk', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);
    }

    public function add(): void
    {
        $this->form->reset();
        $this->modalFormState = 'create';
        $this->openModal('modal-scanner');
    }

    public function cancel(): void
    {
        $this->closeModal($this->modalId);
    }

    public function delete($id): void
    {
        $this->form->produk = Produk::find($id);
        $this->deleteConfirmation('Yakin untuk menghapus data produk ini ?');
    }

    #[On('deleteConfirmed')]
    public function deleteConfirmed(): void
    {
        $this->notifySuccess("Berhasil menghapus produk: {$this->form->produk->kode_produk} - {$this->form->produk->nama_produk}");
        $this->form->delete();
    }

    public function save(): void
    {
        if ($this->currentState === State::CREATE) {
            $this->form->store();
            $this->notifySuccess('Berhasil menambahkan produk baru');
        } elseif ($this->currentState === State::UPDATE) {
            $this->form->update();
            $this->notifySuccess('Berhasil memperbarui produk');
        }

        $this->closeModal($this->modalId);
    }

    public function detail($id) {

        $this->currentState = State::SHOW;

        $produk = Produk::find($id);
        $this->form->fillFromModel($produk);
        $this->openModal($this->modalId);

    }

    public function edit($id) {

        $this->detail($id);
        $this->currentState = State::UPDATE;

    }

    public function render()
    {
        return view('livewire.table.produk-table');
    }
}
