<div class="card mb-4">

    <!-- Modal -->
    <div class="modal fade" id="modal-kategori" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">
                        @if ($currentState === \App\Enums\State::CREATE)
                            Tambah Kategori
                        @elseif ($currentState === \App\Enums\State::UPDATE)
                            Perbarui Kategori
                        @endif
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label fw-semibold">Nama Kategori</label>
                            <input wire:model="nama_kategori" type="text" class="form-control" id="nama_kategori"
                                placeholder="Masukkan nama kategori">
                            @error('nama_kategori')
                                <small class="d-block mt-1 text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                            <textarea wire:model="deskripsi" class="form-control" id="deskripsi" rows="3"
                                placeholder="Masukkan deskripsi kategori (opsional)"></textarea>
                            @error('deskripsi')
                                <small class="d-block mt-1 text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    @if ($currentState === \App\Enums\State::CREATE)
                        <button type="button" wire:click="save" class="btn btn-primary">Tambahkan</button>
                    @elseif ($currentState === \App\Enums\State::UPDATE)
                        <button type="button" wire:click="save" class="btn btn-primary">Perbarui</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card-header d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-sm btn-outline-primary" wire:click="add">
            <i class="bi bi-plus"></i> Tambah Kategori
        </button>

        <div style="width: 300px;">
            <input wire:model.live="search" type="text" class="form-control form-control-sm" placeholder="Cari kategori...">
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table class="table table-bordered" role="table">
            <thead>
                <tr>
                    <th scope="col" style="width: 50px;">#</th>
                    <th scope="col">Nama Kategori</th>
                    <th scope="col">Deskripsi</th>
                    <th scope="col" class="text-end" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->kategoriList as $index => $kategori)
                    <tr>
                        <td>{{ $this->kategoriList->firstItem() + $index }}</td>
                        <td>{{ $kategori->nama_kategori }}</td>
                        <td>{{ $kategori->deskripsi ?? '-' }}</td>
                        <td class="text-end">
                            <button wire:click="edit({{ $kategori->id }})" class="btn btn-sm btn-outline-warning me-1">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button wire:click="delete({{ $kategori->id }})" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                @if($this->kategoriList->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada data kategori.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
        <x-pagination :items="$this->kategoriList" />
    </div>
</div>
