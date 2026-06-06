<?php

namespace App\Livewire\Table;

use App\Models\Kategori;
use App\Traits\WithConfirmation;
use App\Traits\WithModal;
use App\Traits\WithNotify;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Enums\State;

#[Title('Kategori')]
class KategoriTable extends Component
{
    use WithConfirmation;
    use WithModal;
    use WithNotify;
    use WithPagination;

    public $id_kategori;
    public $nama_kategori;
    public $deskripsi;

    public string $search = '';

    public string $modalId = 'modal-kategori';

    public $currentState = State::CREATE;

    public function rules()
    {
        return [
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ];
    }

    #[Computed]
    public function kategoriList()
    {
        return Kategori::query()
            ->when($this->search, function ($query) {
                $query->where('nama_kategori', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);
    }

    public function add(): void
    {
        $this->reset(['id_kategori', 'nama_kategori', 'deskripsi']);
        $this->currentState = State::CREATE;
        $this->openModal($this->modalId);
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        $this->id_kategori = $kategori->id;
        $this->nama_kategori = $kategori->nama_kategori;
        $this->deskripsi = $kategori->deskripsi;
        $this->currentState = State::UPDATE;
        $this->openModal($this->modalId);
    }

    public function save(): void
    {
        $this->validate();

        if ($this->currentState === State::CREATE) {
            Kategori::create([
                'nama_kategori' => $this->nama_kategori,
                'deskripsi' => $this->deskripsi,
            ]);
            $this->notifySuccess('Berhasil menambahkan kategori');
        } elseif ($this->currentState === State::UPDATE) {
            $kategori = Kategori::findOrFail($this->id_kategori);
            $kategori->update([
                'nama_kategori' => $this->nama_kategori,
                'deskripsi' => $this->deskripsi,
            ]);
            $this->notifySuccess('Berhasil memperbarui kategori');
        }

        $this->closeModal($this->modalId);
    }

    public function delete($id): void
    {
        $this->id_kategori = $id;
        $this->deleteConfirmation('Yakin untuk menghapus data kategori ini?');
    }

    #[On('deleteConfirmed')]
    public function deleteConfirmed(): void
    {
        Kategori::findOrFail($this->id_kategori)->delete();
        $this->notifySuccess("Berhasil menghapus kategori");
    }

    public function render()
    {
        return view('livewire.table.kategori-table');
    }
}
