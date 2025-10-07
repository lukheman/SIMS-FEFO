@php
    use App\Enums\Role;
@endphp

<div class="card">

  {{-- Modal Pilih Kurir --}}
  <div class="modal fade" id="modal-pilih-kurir" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Pilih Kurir</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            @foreach ($kurirList as $item)
              <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100
                    @isset($selectedKurir)
                        {{ $item->id === $selectedKurir->id ? 'border-2 border-primary' : 'border-0' }}
                    @endisset">
                  <div class="card-body text-center">
                    <img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('images/default-user.png') }}"
                         class="rounded-circle mb-3"
                         alt="{{ $item->name }}"
                         width="80" height="80"
                         style="object-fit: cover;">
                    <h6 class="fw-semibold mb-2">{{ $item->name }}</h6>

                    <button wire:click="selectKurir({{ $item->id }})"
                            type="button"
                            class="btn btn-outline-primary btn-sm">
                      <i class="bi bi-person-check"></i> Pilih
                    </button>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <div class="modal-footer">
          <button wire:click="saveKurir" type="button" class="btn btn-outline-primary">
            <i class="bi bi-truck me-1"></i> Tugaskan Kurir
          </button>
        </div>

      </div>
    </div>
  </div>

  {{-- Modal Detail Transaksi --}}
  <div class="modal fade" id="modal-detail-transaksi" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Detail Transaksi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total Harga</th>
              </tr>
            </thead>
            <tbody>
              @isset($selectedTransaksi)
                @foreach ($selectedTransaksi->pesanan as $item)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td>{{ $item->label_jumlah_pesanan }}</td>
                    <td>{{ $item->label_harga_satuan }}</td>
                    <td>{{ $item->label_total_harga_jual }}</td>
                  </tr>
                @endforeach
              @endisset
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

  {{-- Card Body --}}
  <div class="card-body">
    <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap5">
      <div class="row mb-3">
        <div class="col-md-6"></div>
        <div class="col-md-6"></div>
      </div>

      <div class="row">
        <div class="col-12">
          <table id="datatable" class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-light text-center">
              <tr>
                <th>Tanggal</th>
                <th>Pemesan</th>
                <th>Status Transaksi</th>
                <th>Metode Pembayaran</th>
                <th>Status Pembayaran</th>

                @if (getActiveUser()->role === Role::KASIR)
                  <th>Ubah Status Pembayaran</th>
                  <th>Pilih Kurir</th>
                  <th>Nota</th>
                @elseif(getActiveUser()->role === Role::KURIR)
                  <th>Konfirmasi</th>
                @endif

                <th>Info</th>
              </tr>
            </thead>

            <tbody>
              @forelse ($this->transaksiList as $item)
                <tr class="text-center">
                  <td>{{ $item->tanggal }}</td>
                  <td>{{ $item->user->name }}</td>

                  <td>
                    <div class="badge bg-{{ $item->status->getColor() }}">
                      {{ $item->status }}
                    </div>
                  </td>

                  <td>
                    <span class="badge bg-success">{{ $item->metode_pembayaran }}</span>
                  </td>

                  <td>
                    <div class="badge bg-{{ $item->status_pembayaran->getColor() }}">
                      {{ $item->status_pembayaran->label() }}
                    </div>
                  </td>

                  {{-- ADMIN TOKO --}}
                  @if (getActiveUser()->role === Role::KASIR)
                    <td>
                      <button wire:click="transaksiLunas({{ $item->id }})"
                              class="btn btn-sm btn-success"
                              {{ $item->metode_pembayaran === \App\Enums\MetodePembayaran::COD ? 'disabled' : '' }}>
                        <i class="bi bi-cash-stack"></i> Lunas
                      </button>
                    </td>

                    <td>
                      <button wire:click="openModalSelectKurir({{ $item->id }})"
                              type="button"
                              class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-person-check"></i> Pilih Kurir
                      </button>
                    </td>

                    <td>
                      <form action="{{ route('kasir.nota') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_transaksi" value="{{ $item->id }}">
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                          <i class="bi bi-printer"></i> Cetak
                        </button>
                      </form>
                    </td>
                  @endif

                  {{-- KURIR --}}
                  @if (getActiveUser()->role === Role::KURIR)
                    <td>
                      <button wire:click="pesananDiterima({{ $item->id}})" type="button" class="btn btn-sm btn-outline-success"
                              {{ $item->status === \App\Enums\StatusTransaksi::DITERIMA ? 'disabled' : '' }}>
                        <i class="bi bi-check2-circle"></i> Pesanan Diterima
                      </button>
                    </td>
                  @endif

                  <td>
                    <button wire:click="detailTransaksi({{ $item->id }})"
                            type="button"
                            class="btn btn-sm btn-outline-secondary">
                      <i class="bi bi-info-circle"></i> Detail Pesanan
                    </button>
                  </td>
                </tr>
            @empty

                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-bag-x fs-3 d-block mb-2"></i>
                            Tidak ada pesanan
                        </td>
                    </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>
