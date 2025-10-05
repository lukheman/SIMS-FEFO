@php
    use App\Enums\MetodePembayaran;
@endphp
<div class="card shadow-sm">

<!-- Info Produk -->
<div class="modal fade" id="modal-pesan-produk" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @isset($form->pesanan)

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
      <h5 class="fw-semibold mb-1" id="nama-produk">{{ $form->pesanan->produk->nama_produk }}</h5>
      <p class="text-muted small mb-2" id="deskripsi-produk">{{ $form->pesanan->produk->deskripsi}}</p>
      <p class="fs-5 fw-bold text-primary mb-1" id="total-harga-display">{{ $form->pesanan->produk->label_harga_jual}}</p>
      <p class="text-info small mb-3">
        <i class="bi bi-info-circle"></i>
        Pemesanan dalam satuan <strong id="satuan-text">{{ $form->satuan ?? $form->pesanan->produk->unit_besar }}</strong>
      </p>
    </div>

    <div class="mb-3">
      <label for="satuan" class="form-label fw-semibold">Pilih Satuan</label>
      <select wire:model.live="form.satuan" id="satuan" class="form-select form-select-sm w-auto d-inline-block">
        <option id="unit-besar" value="0" selected>{{ $form->pesanan->produk->unit_besar}}</option>
        <option id="unit-kecil" value="1">{{ $form->pesanan->produk->unit_kecil }}</option>
      </select>
    </div>

    <div class="d-flex align-items-center">
      <div class="input-group input-group-sm w-auto">
        <button wire:click="kurangiJumlahPesanan" type="button" class="btn btn-outline-secondary" id="btn-kurang-jumlah">
          <i class="bi bi-dash"></i>
        </button>
        <input wire:model="form.jumlah" type="number" name="jumlah" id="jumlah" class="form-control text-center" value="1" style="max-width: 80px;">
        <button wire:click="tambahJumlahPesanan" type="button" class="btn btn-outline-secondary" id="btn-tambah-jumlah">
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
        <button wire:click="saveToCart" type="button" class="btn btn-primary">
        <i class="bi bi-cart-plus"></i>
        Simpan ke Keranjang
      </button>
      </div>
    </div>
  </div>
</div>

<!-- Metode Pembayaran -->
<div class="modal fade" id="modal-metode-pembayaran" tabindex="-1"  wire:ignore.self>
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0 rounded-4">
      <div class="modal-header">
        <h5 class="modal-title fw-semibold" id="modalMetodePembayaranLabel">Pilih Metode Pembayaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>

<form wire:submit.prevent="checkout">
  <div class="modal-body">
    <div class="row g-3">
      <div class="col-6">
        <div wire:click="setMetodePembayaran('transfer')"
             class="card {{ $metode_pembayaran === MetodePembayaran::TRANSFER ? 'border-2 border-primary' : 'border' }} shadow-sm text-center py-3"
             style="cursor:pointer;">
          <div class="card-body">
            <div class="text-warning fs-1 mb-2">
              <i class="bi bi-cash-stack"></i>
            </div>
            <h6 class="mb-0 fw-semibold">Transfer</h6>
          </div>
        </div>
      </div>

      <div class="col-6">
        <div wire:click="setMetodePembayaran('cod')"
             class="card {{ $metode_pembayaran === MetodePembayaran::COD ? 'border-2 border-primary' : 'border' }} shadow-sm text-center py-3"
             style="cursor:pointer;">
          <div class="card-body">
            <div class="text-success fs-1 mb-2">
              <i class="bi bi-cash-coin"></i>
            </div>
            <h6 class="mb-0 fw-semibold">COD</h6>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal-footer border-0">
    <button type="submit" class="btn btn-primary">Pesan</button>
  </div>
</form>
    </div>
  </div>
</div>

    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <button wire:click="$dispatch('openModal', {id : 'modal-metode-pembayaran'})" type="button" class="btn btn-outline-primary me-2"
                    {{ $this->pesananList->count() == 0 ? 'disabled' : '' }}>
                <i class="bi bi-cash-stack me-1"></i> Checkout
            </button>

            <button type="button"
                    class="btn btn-outline-danger"
                    id="btn-delete-pilihan-pesanan"
                    style="display: none;">
                <i class="bi bi-trash me-1"></i> Hapus
            </button>
        </div>
    </div>

<div class="card-body">
    <div class="table-responsive">
        <table id="datatable" class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>
                        <div class="form-check">
                            <input wire:model.live="selectAll" class="form-check-input" type="checkbox" style="width: 25px; height: 25px;">
                        </div>
                    </th>
                    <th>Nama Produk</th>
                    <th>Jumlah Dipesan</th>
                    <th>Harga</th>
                    <th>Total Harga</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($this->pesananList as $item)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input wire:model.live="selectedIdPesanan" type="checkbox" value="{{ $item->id }}" class="form-check-input" style="width: 25px; height: 25px;">
                            </div>
                        </td>
                        <td>{{ $item->produk->nama_produk }}</td>
                        <td>{{ $item->label_jumlah_unit_dipesan }}</td>
                        <td>{{ $item->produk->label_harga_jual }}</td>
                        <td>{{ $item->label_total_harga_jual }}</td>
                        <td class="text-end">
                            <button wire:click="delete({{ $item->id }})" class="btn btn-outline-danger btn-sm me-1">
                                <i class="bi bi-trash"></i> Hapus
                            </button>

                            <button wire:click="infoPesanan({{ $item->id }})" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus"></i> Tambah Pesanan
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-bag-x fs-3 d-block mb-2"></i>
                            Tidak ada pesanan dalam keranjang.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination (opsional, jika DataTables dipakai) -->
    <x-pagination :items="$this->pesananList"/>
</div>
</div>
