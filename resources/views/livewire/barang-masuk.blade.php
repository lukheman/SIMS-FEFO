<div class="card">




  <div class="card-header">

    @if ($isLaporan)

      <button type="button" class="btn btn-sm btn-outline-danger" id="btn-cetak-laporan-penjualan" data-bs-toggle="modal"
        data-bs-target="#modal-cetak-barang-masuk">
        <i class="bi bi-printer"></i>
        Cetak Laporan Barang Masuk</button>

    @else

      <button type="button" class="btn btn-sm btn-outline-primary" wire:click="searchProdukModal">
        <i class="bi bi-box-arrow-in-down"></i>

        Produk Masuk

      </button>

    @endif

  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table class="table table-bordered" role="table">
      <thead>
        <tr>
          <th scope="col">Tanggal</th>
          <th scope="col">Nama Produk</th>
          <th scope="col">Jumlah Dipesan</th>
          <th scope="col">Harga Beli</th>
          <th scope="col">Total Harga</th>

          @if (!@$isLaporan)
            <th scope="col" class="text-end">Aksi</th>
          @endif


        </tr>
      </thead>
      <tbody>

        @foreach ($this->produkMasuk as $item)

          <tr>
            <td>{{ $item->tanggal }}</td>
            <td>{{ $item->produk->nama_produk }}</td>
            <td>{{ $item->label_jumlah_unit_dipesan}}</td>
            <td>{{ $item->produk->label_harga_beli}}</td>
            <td>{{ $item->label_total_harga_beli}}</td>

            @if (!@$isLaporan)
              <td class="text-end">
                <button wire:click="detailSupplyProduk({{ $item->id }})" class="btn btn-sm btn-outline-info" title="Detail">
                  <i class="bi bi-info-circle"></i> Detail
                </button>
                <button wire:click="editSupplyProduk({{ $item->id }})" class="btn btn-sm btn-outline-warning" title="Edit">
                  <i class="bi bi-pencil"></i> Edit
                </button>
                <button wire:click="deleteSupplyProduk({{ $item->id}})" class="btn btn-sm btn-outline-danger" title="Hapus">
                  <i class="bi bi-trash"></i> Hapus
                </button>
              </td>
            @endif
          </tr>

        @endforeach

      </tbody>
    </table>
  </div>

  <div class="card-footer clearfix">
    <x-pagination :items="$this->produkMasuk" />
  </div>



  <div class="modal fade" id="modal-produk" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">

          <h1 class="modal-title fs-5" id="exampleModalLabel">

            Tambah Produk Masuk
          </h1>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="row">
            @isset($produk)
              <div class="col-12">
                <div class="position-relative"
                  style="height: 200px; background: #f8f9fa; display:flex; align-items:center; justify-content:center;">
                  <img src="{{ asset('storage/' . $produk->gambar) }}" class="img-fluid product-image"
                    alt="{{ $produk->nama_produk }}"
                    style="max-height: 100%; object-fit: contain; transition: transform 0.3s;">
                </div>

                <div class="card-body d-flex flex-column">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-truncate mb-0">
                      {{ $produk->kode_produk }} - {{ $produk->nama_produk }}
                    </small>
                  </div>

                  {{-- Input jumlah --}}
                  <div class="mb-3">
                    <label for="jumlah" class="form-label small mb-1">Jumlah ({{ $produk->unit_kecil ?? 'Unit Kecil' }})</label>
                    <input type="number" wire:model.live="jumlah" min="0" step="any" class="form-control form-control-sm rounded-3"
                      placeholder="Masukkan jumlah">
                  </div>

                  <div class="mb-3">
                    <label for="jumlah_bal" class="form-label small mb-1">Jumlah ({{ $produk->unit_besar ?? 'Unit Besar' }})</label>
                    <input type="number" wire:model.live="jumlah_bal" min="0" step="any" class="form-control form-control-sm rounded-3"
                      placeholder="Masukkan jumlah unit besar">
                  </div>

                  <button wire:click="addSupplyProduk" type="button"
                    class="btn btn-sm btn-outline-primary w-full px-3 btn-tambah-pesanan">
                    <i class="bi bi-box-arrow-in-down me-1"></i> Tambahkan Barang Masuk
                  </button>
                </div>
              </div>
            @endisset
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-edit-mutasi" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Update Barang Masuk</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            @if($mutasi && $produk)
              <div class="col-12">
                <div class="mb-3">
                  <label class="form-label small mb-1">Produk</label>
                  <input type="text" class="form-control form-control-sm rounded-3" value="{{ $produk->kode_produk }} - {{ $produk->nama_produk }}" readonly>
                </div>
                <div class="mb-3">
                  <label for="jumlah" class="form-label small mb-1">Jumlah ({{ $produk->unit_kecil ?? 'Unit Kecil' }})</label>
                  <input type="number" wire:model.live="jumlah" min="0" step="any" class="form-control form-control-sm rounded-3">
                </div>
                <div class="mb-3">
                  <label for="jumlah_bal" class="form-label small mb-1">Jumlah ({{ $produk->unit_besar ?? 'Unit Besar' }})</label>
                  <input type="number" wire:model.live="jumlah_bal" min="0" step="any" class="form-control form-control-sm rounded-3">
                </div>
                <button wire:click="updateSupplyProduk" type="button" class="btn btn-sm btn-outline-warning w-full px-3">
                  <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-detail-mutasi" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Detail Barang Masuk</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            @if($mutasi)
              <div class="col-12">
                <table class="table table-borderless table-sm">
                  <tbody>
                    <tr>
                      <td class="text-muted" width="40%">Tanggal</td>
                      <td class="fw-semibold">{{ $mutasi->tanggal }}</td>
                    </tr>
                    <tr>
                      <td class="text-muted">Kode Produk</td>
                      <td class="fw-semibold">{{ $mutasi->produk->kode_produk }}</td>
                    </tr>
                    <tr>
                      <td class="text-muted">Nama Produk</td>
                      <td class="fw-semibold">{{ $mutasi->produk->nama_produk }}</td>
                    </tr>
                    <tr>
                      <td class="text-muted">Jumlah Dipesan</td>
                      <td class="fw-semibold">{{ $mutasi->label_jumlah_unit_dipesan }}</td>
                    </tr>
                    <tr>
                      <td class="text-muted">Harga Beli</td>
                      <td class="fw-semibold">{{ $mutasi->produk->label_harga_beli }}</td>
                    </tr>
                    <tr>
                      <td class="text-muted">Total Harga Beli</td>
                      <td class="fw-semibold">{{ $mutasi->label_total_harga_beli }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-cari-produk" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">

          <h1 class="modal-title fs-5" id="exampleModalLabel">

            Cari Produk
          </h1>

          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="row mb-4">
            <div class="col-12">
              <input wire:model.live="search" type="text" class="form-control me-2 shadow-sm"
                placeholder="Cari produk...">
            </div>
          </div>

          <div class="row">
            @foreach ($this->produkList as $item)
              <div class="col-12 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-shadow">
                  <div class="position-relative"
                    style="height: 200px; background: #f8f9fa; display:flex; align-items:center; justify-content:center;">
                    <img src="{{ asset('storage/' . $item->gambar) }}" class="img-fluid product-image"
                      alt="{{ $item->nama_produk }}"
                      style="max-height: 100%; object-fit: contain; transition: transform 0.3s;">
                  </div>

                  <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <small class="text-truncate mb-0">{{ $item->kode_produk }} - {{ $item->nama_produk }}</small>
                    </div>

                    <button wire:click="addProduk({{ $item->id }})" type="button"
                      class="btn btn-sm btn-outline-primary w-full px-3 btn-tambah-pesanan">
                      <i class="bi bi-box-arrow-in-down me-1"></i> Tambahkan Barang Masuk
                    </button>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- modal-cetak-barang-masuk - modal untuk menampilkan form tambah data produk -->
  <div class="modal fade" id="modal-cetak-barang-masuk" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Periode Laporan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <form action="{{ route('laporan.laporan-barang-masuk') }}" method="post">
          <input type="hidden" name="ttd" value="Pemilik">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="periode" class="form-label">Periode</label>
              <input type="month" class="form-control" name="periode" id="periode">
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-sm btn-outline-danger">
              <i class="bi bi-printer"></i>
              Cetak
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
