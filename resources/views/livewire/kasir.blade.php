<div class="row">
  <!-- Kolom kiri: Cari barang & daftar belanja -->
  <div class="col-md-8">
    <div class="card mb-3">
      <div class="card-header bg-primary text-white">
        Cari Barang
      </div>
      <div class="card-body">
        <div class="input-group mb-3">
          <input wire:model.live="search" type="text" class="form-control" placeholder="Ketik nama / kode barang">
          <button class="btn btn-primary" type="button">Cari</button>
        </div>

        <!-- Hasil Pencarian -->
        <div class="table-responsive">
          <table class="table table-sm">
            <thead class="table-light">
              <tr>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <!-- <th>Persediaan</th> -->
                <th class="text-end">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($this->produkList as $produk)
              <tr>
                <td>{{ $produk->kode_produk }}</td>
                <td>{{ $produk->nama_produk }}</td>
                <td>Rp {{ number_format($produk->harga_jual_unit_kecil, 0, ',', '.') }}</td>
                <!-- <td>{{ $produk->persediaan->jumlah}}</td> -->
                <td class="text-end">
                  <button wire:click="addProduk({{ $produk->id }})" class="btn btn-sm btn-success">Tambah</button>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center">Tidak ada produk ditemukan</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header bg-secondary text-white">
        Daftar Belanja
      </div>
      <div class="card-body p-0">
        <table class="table mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Kode</th>
              <th>Nama Barang</th>
              <th>Qty</th>
              <th>Harga</th>
              <th>Total</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($daftarBelanja as $item)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $item['kode'] }}</td>
              <td>{{ $item['nama'] }}</td>
              <td>{{ $item['qty'] }}</td>
              <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
              <td>Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
              <td class="text-end">
                <button wire:click="deleteProduk({{ $item['id'] }})" class="btn btn-sm btn-danger">Hapus</button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center">Belum ada barang ditambahkan</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Kolom kanan: Ringkasan transaksi -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header bg-success text-white">
        Ringkasan Transaksi
      </div>
      <div class="card-body">
        <p><strong>Total Item:</strong> {{ collect($daftarBelanja)->sum('qty') }}</p>
        <p><strong>Total Harga:</strong> Rp {{ number_format(collect($daftarBelanja)->sum('total'), 0, ',', '.') }}</p>

        <div class="mb-3">
          <label for="bayar" class="form-label">Bayar</label>
          <input type="number" class="form-control" id="bayar" placeholder="Masukkan jumlah bayar" wire:model="bayar">
        </div>

<p><strong>Kembalian:</strong> Rp {{ max(0, $bayar - $this->totalHarga) }}</p>
<button wire:show="!showNextTransactionButton" wire:click="selesaikanTransaksi" class="btn btn-success w-100">Selesaikan Transaksi</button>

<button wire:show="showNextTransactionButton" wire:click="clearTransaction" class="btn btn-success w-100">Transaksi Selanjutnya</button>

@if (session()->has('success'))
  <div class="alert alert-success mt-2">
    {{ session('success') }}
  </div>
@endif

@if (session()->has('error'))
  <div class="alert alert-danger mt-2">
    {{ session('error') }}
  </div>
@endif
      </div>
    </div>
  </div>
</div>
