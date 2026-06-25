<div class="card mb-4">


<!-- Modal Pilih Metode -->
<div class="modal fade" id="modal-pilih-metode" tabindex="-1" wire:ignore.self>
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Metode Input Produk</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <button class="btn btn-primary w-100 mb-3" data-bs-target="#modal-scanner" data-bs-toggle="modal">
            <i class="bi bi-upc-scan"></i> Scan Barcode
        </button>
        <button class="btn btn-secondary w-100" data-bs-target="#modal-produk" data-bs-toggle="modal">
            <i class="bi bi-keyboard"></i> Tulis Manual
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-scanner" tabindex="-1" wire:ignore.self>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal-scanner">Scanner Barcode</h1>
        <button type="button" class="btn-close" data-bs-target="#modal-pilih-metode" data-bs-toggle="modal" aria-label="Kembali"></button>
      </div>
      <div class="modal-body">
                <div id="scanner"></div>
      </div>
      <div class="modal-footer">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-produk" tabindex="-1" wire:ignore.self>
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">

        <h1 class="modal-title fs-5" id="exampleModalLabel">

                            @if ($currentState === \App\Enums\State::CREATE)
                                Tambah Produk
                            @elseif ($currentState === \App\Enums\State::UPDATE)
                                Perbarui Produk
                            @elseif ($currentState === \App\Enums\State::SHOW)
                                Detail Produk
                            @endif
                    </h1>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
<form>
    <div class="row">
        <div class="col-md-4 col-12">
            <div class="mb-3">
                <label for="id_kategori" class="form-label fw-semibold">Kategori</label>
                <select wire:model="form.id_kategori" class="form-select" id="id_kategori" @if ($currentState === \App\Enums\State::SHOW) disabled @endif>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
                @error('form.id_kategori')
                    <small class="d-block mt-1 text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="mb-3">
                <label for="nama_produk" class="form-label fw-semibold">Nama Produk</label>
                <input wire:model="form.nama_produk" type="text" class="form-control" id="nama_produk"
                    placeholder="Masukkan nama produk" @if ($currentState === \App\Enums\State::SHOW) disabled @endif>
                @error('form.nama_produk')
                    <small class="d-block mt-1 text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="mb-3">
                <label for="kode_produk" class="form-label fw-semibold">Kode Produk</label>
                <div class="input-group">
                    <input wire:model="form.kode_produk" type="text" class="form-control" id="kode_produk"
                        placeholder="Scan atau Manual" @if ($currentState === \App\Enums\State::SHOW) disabled @endif>
                    @if ($currentState !== \App\Enums\State::SHOW)
                        <button class="btn btn-secondary" type="button" data-bs-target="#modal-scanner" data-bs-toggle="modal">
                            <i class="bi bi-upc-scan"></i> Scan
                        </button>
                    @endif
                </div>
                @error('form.kode_produk')
                    <small class="d-block mt-1 text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-12">
            <div class="mb-3">
                <label for="harga_beli" class="form-label fw-semibold">Harga Beli</label>
                <input wire:model="form.harga_beli" type="number" step="0.01" class="form-control" id="harga_beli"
                    placeholder="Masukkan harga beli" @if ($currentState === \App\Enums\State::SHOW) disabled @endif>
                @error('form.harga_beli')
                    <small class="d-block mt-1 text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="mb-3">
                <label for="harga_jual" class="form-label fw-semibold">Harga Jual</label>
                <input wire:model="form.harga_jual" type="number" step="0.01" class="form-control" id="harga_jual"
                    placeholder="Masukkan harga jual" @if ($currentState === \App\Enums\State::SHOW) disabled @endif>
                @error('form.harga_jual')
                    <small class="d-block mt-1 text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="mb-3">
                <label for="tanggal_exp" class="form-label fw-semibold">Tanggal Kadaluarsa</label>
                <input wire:model="form.tanggal_exp" type="date" class="form-control" id="tanggal_exp"
                    @if ($currentState === \App\Enums\State::SHOW) disabled @endif>
                @error('form.tanggal_exp')
                    <small class="d-block mt-1 text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>



    <div class="mb-3">
        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
        <textarea wire:model="form.deskripsi" class="form-control" id="deskripsi" rows="3" placeholder="Masukkan deskripsi produk"
            @if ($currentState === \App\Enums\State::SHOW) disabled @endif></textarea>
        @error('form.deskripsi')
            <small class="d-block mt-1 text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-3">
        <label for="gambar" class="form-label fw-semibold">Gambar Produk</label>

        {{-- Preview gambar existing (dari database) --}}
        @if ($form->gambarPath && !$form->gambar)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $form->gambarPath) }}"
                    alt="Gambar Produk"
                    class="img-thumbnail"
                    style="max-height: 160px; object-fit: contain;">
                <div class="mt-1">
                    <small class="text-muted">Gambar saat ini</small>
                </div>
            </div>
        @endif

        {{-- Preview gambar baru yang baru saja diupload --}}
        @if ($form->gambar)
            <div class="mb-2">
                <img src="{{ $form->gambar->temporaryUrl() }}"
                    alt="Preview Gambar"
                    class="img-thumbnail"
                    style="max-height: 160px; object-fit: contain;">
                <div class="mt-1">
                    <small class="text-success">Preview gambar baru</small>
                </div>
            </div>
        @endif

        @if ($currentState !== \App\Enums\State::SHOW)
            <input wire:model="form.gambar" type="file" accept="image/*" class="form-control" id="gambar">
            @error('form.gambar')
                <small class="d-block mt-1 text-danger">{{ $message }}</small>
            @enderror
        @endif
    </div>

    <div class="row">
        <div class="col-md-6 col-12">
            <div class="mb-3">
                <label for="harga_jual_unit_kecil" class="form-label fw-semibold">Harga Jual (Unit Kecil)</label>
                <input wire:model="form.harga_jual_unit_kecil" type="number" step="0.01" class="form-control"
                    id="harga_jual_unit_kecil" placeholder="Masukkan harga jual unit kecil"
                    @if ($currentState === \App\Enums\State::SHOW) disabled @endif>
                @error('form.harga_jual_unit_kecil')
                    <small class="d-block mt-1 text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="col-md-6 col-12">
            <div class="mb-3">
                <label for="tingkat_konversi" class="form-label fw-semibold">Tingkat Konversi</label>
                <input wire:model="form.tingkat_konversi" type="number" class="form-control" id="tingkat_konversi"
                    placeholder="Jumlah unit kecil per unit besar" @if ($currentState === \App\Enums\State::SHOW) disabled @endif>
                @error('form.tingkat_konversi')
                    <small class="d-block mt-1 text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-12">
            <div class="mb-3">
                <label for="unit_kecil" class="form-label fw-semibold">Unit Kecil</label>
                <select wire:model="form.unit_kecil" class="form-select" id="unit_kecil"
                    @if ($currentState === \App\Enums\State::SHOW) disabled @endif>
                    <option value="">Pilih Unit Kecil</option>
                    @foreach (\App\Enums\UnitKecilProduk::values() as $unit)
                        <option value="{{ $unit }}">{{ $unit }}</option>
                    @endforeach
                </select>
                @error('form.unit_kecil')
                    <small class="d-block mt-1 text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="col-md-6 col-12">
            <div class="mb-3">
                <label for="unit_besar" class="form-label fw-semibold">Unit Besar</label>
                <select wire:model="form.unit_besar" class="form-select" id="unit_besar"
                    @if ($currentState === \App\Enums\State::SHOW) disabled @endif>
                    <option value="">Pilih Unit Besar</option>
                    @foreach (\App\Enums\UnitBesarProduk::values() as $unit)
                        <option value="{{ $unit }}">{{ $unit }}</option>
                    @endforeach
                </select>
                @error('form.unit_besar')
                    <small class="d-block mt-1 text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
</form>
      </div>
      <div class="modal-footer">
    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                        @if ($currentState === \App\Enums\State::CREATE)
                            <button type="button" wire:click="save" class="btn btn-primary">Tambahkan</button>
                        @elseif ($currentState === \App\Enums\State::UPDATE)
                            <button type="button" wire:click="save" class="btn btn-primary">Perbarui</button>
                        @endif
      </div>
    </div>
  </div>
</div>

                  <div class="card-header">

            <button type="button" class="btn btn-primary" wire:click="add">
                    <i class="bi bi-plus"></i>

            Tambah Produk

            </button>

                    </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table class="table table-bordered" role="table">
                      <thead>
                        <tr>
                          <th scope="col">Kode Produk</th>
                          <th scope="col">Nama Produk</th>
                          <th scope="col">Kategori</th>
                          <th scope="col">Harga Beli/bal (Rp.)</th>
                          <th scope="col">Harga Jual/bal (Rp.)</th>
                          <th scope="col">Tanggal Exp</th>
                          <th scope="col">Persediaan</th>
                          <th scope="col" class="text-end">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>

                        @foreach ($this->produkList as $produk)

                            <tr>
                                <td>{{ $produk->kode_produk }}</td>
                                <td>{{ $produk->nama_produk }}</td>
                                <td>{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                                <td>{{ number_format($produk->harga_beli, 0, ',', '.') }}</td>
                                <td>{{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                                <td>{{ $produk->tanggal_exp ? \Carbon\Carbon::parse($produk->tanggal_exp)->format('d M Y') : '-' }}</td>
                                <td>{{ $produk->totalPersediaan() }}</td>
<td class="text-end">
    <button wire:click="detail({{ $produk->id }})" class="btn btn-info me-1">
        <i class="bi bi-eye"></i> Lihat
    </button>

    <button wire:click="edit({{ $produk->id }})" class="btn btn-warning me-1">
        <i class="bi bi-pencil-square"></i> Edit
    </button>

    <button wire:click="delete({{ $produk->id }}, '{{ $produk->role }}')" class="btn btn-danger">
        <i class="bi bi-trash"></i> Hapus
    </button>
</td>
                            </tr>

                        @endforeach

                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer clearfix">
                        <x-pagination :items="$this->produkList" />
                  </div>
                </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let scannerStarted = false;

    // Fungsi untuk memulai scanner
    function startScanner() {
        if (scannerStarted) return; // Cegah inisialisasi ganda

        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#scanner'),
                constraints: {
                    facingMode: "environment" // Kamera belakang (HP)
                }
            },
            decoder: {
                readers: ["code_128_reader", "ean_reader", "upc_reader"]
            },
        }, function(err) {
            if (err) {
                console.error("Quagga init error:", err);
                return;
            }
            Quagga.start();
            scannerStarted = true;
            console.log("Scanner dimulai.");
        });

        Quagga.onDetected(function(result) {
            const barcode = result.codeResult.code;
            console.log("Barcode terdeteksi:", barcode);

            @this.set('form.kode_produk', barcode);

            // Tutup modal scanner dan buka modal produk
            const scannerModal = bootstrap.Modal.getInstance(document.getElementById('modal-scanner'));
            scannerModal.hide();

            // Berhenti scanning setelah deteksi pertama
            Quagga.stop();
            scannerStarted = false;

            Livewire.dispatch('openModal', {id: 'modal-produk'});
        });
    }

    // Saat modal scanner dibuka
    const modalScanner = document.getElementById('modal-scanner');
    modalScanner.addEventListener('shown.bs.modal', function() {
        console.log("Modal scanner dibuka");
        setTimeout(startScanner, 500); // Delay agar elemen #scanner sudah ter-render
    });

    // Saat modal scanner ditutup
    modalScanner.addEventListener('hidden.bs.modal', function() {
        console.log("Modal scanner ditutup");
        if (scannerStarted) {
            Quagga.stop();
            scannerStarted = false;
        }
    });
});
</script>
@endpush
