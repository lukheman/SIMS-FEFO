<div>

<!-- Modal -->

<div class="modal fade" id="modal-pesan-produk" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @isset($produk)

<div class="row g-4 align-items-center">
  <!-- Kolom kiri: gambar produk -->
  <div class="col-12 col-md-5 text-center">
    <div class="border rounded-3 p-3 bg-light shadow-sm">
      <img id="img-gambar-produk" class="img-fluid rounded" width="220" alt="Gambar Produk">
    </div>
  </div>

  <!-- Kolom kanan: detail produk -->
  <div class="col-12 col-md-7">
    <div class="mb-3">
      <h5 class="fw-semibold mb-1" id="nama-produk">{{ $produk->nama_produk }}</h5>
      <p class="text-muted small mb-2" id="deskripsi-produk">{{ $produk->deskripsi}}</p>
      <p class="fs-5 fw-bold text-primary mb-1" id="total-harga-display">{{ $produk->label_harga_jual}}</p>
      <p class="text-info small mb-3">
        <i class="bi bi-info-circle"></i>
        Pemesanan dalam satuan <strong id="satuan-text">{{ $satuan ?? $produk->unit_besar }}</strong>
      </p>
    </div>

    <div class="mb-3">
      <label for="satuan" class="form-label fw-semibold">Pilih Satuan</label>
      <select wire:model.live="satuan" id="satuan" class="form-select form-select-sm w-auto d-inline-block">
        <option id="unit-besar" value="0" selected>{{ $produk->unit_besar}}</option>
        <option id="unit-kecil" value="1">{{ $produk->unit_kecil }}</option>
      </select>
    </div>

    <div class="d-flex align-items-center">
      <div class="input-group input-group-sm w-auto">
        <button wire:click="tambahJumlahPesanan" type="button" class="btn btn-outline-secondary" id="btn-kurang-jumlah">
          <i class="bi bi-dash"></i>
        </button>
        <input wire:model="jumlahPesanan" type="number" name="jumlah" id="jumlah" class="form-control text-center" value="1" style="max-width: 80px;">
        <button wire:click="kurangiJumlahPesanan" type="button" class="btn btn-outline-secondary" id="btn-tambah-jumlah">
          <i class="bi bi-plus"></i>
        </button>
      </div>
      <small class="text-muted ms-2">(atur jumlah)</small>
    </div>
  </div>
</div>

        @endisset


      </div>
      <div class="modal-footer">
        <button wire:click="addToCart" type="button" class="btn btn-primary">
        <i class="bi bi-cart-plus"></i>
        Tambahkan ke Keranjang
      </button>
      </div>
    </div>
  </div>
</div>

<div class="row mb-4">
    <div class="col-12">
            <input wire:model.live="search" type="text" class="form-control me-2 shadow-sm" placeholder="Cari produk...">
    </div>
</div>

<div class="row">
    @foreach ($this->produkList as $item)
    <div class="col-12 col-md-4 col-lg-3 mb-4">
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-shadow">
            <div class="position-relative" style="height: 200px; background: #f8f9fa; display:flex; align-items:center; justify-content:center;">
                <img src="{{ asset('storage/' . $item->gambar) }}"
                     class="img-fluid product-image"
                     alt="{{ $item->nama_produk }}"
                     style="max-height: 100%; object-fit: contain; transition: transform 0.3s;">
                @if ($item->persediaan->jumlah === 0)
                <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50">
                    <span class="text-white fw-bold fs-4">Kosong</span>
                </div>
                @endif
            </div>

            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title fw-bold text-truncate mb-0" style="max-width:70%;">{{ $item->nama_produk }}</h6>
                    <span class="badge bg-info">{{ $item->label_persediaan }}</span>
                </div>

                <p class="mb-1 text-muted small">Exp: {{ $item->exp }}</p>
                <p class="fw-bold text-primary mb-3">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</p>

                <div class="mt-auto text-end">
                    <button wire:click="infoProduk({{ $item->id }})" type="button"
                            class="btn btn-sm btn-outline-primary rounded-pill px-3 btn-tambah-pesanan"
                            {{ $item->persediaan->jumlah == 0 ? 'disabled' : '' }}>
                        <i class="bi bi-cart-plus me-1"></i> Pesan
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
</div>
