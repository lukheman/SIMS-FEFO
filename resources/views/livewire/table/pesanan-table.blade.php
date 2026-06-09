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
  <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
    @if (getActiveUser()->role === Role::KURIR || getActiveUser()->role === Role::KASIR)
    <div class="card-header bg-white border-bottom p-0">
        <ul class="nav nav-pills nav-fill d-flex flex-nowrap overflow-auto" style="font-weight: 500; font-size: 0.95rem; white-space: nowrap; border-bottom: 1px solid #e9ecef; cursor: pointer;">
            <li class="nav-item">
                <a wire:click.prevent="setTab('semua')" class="nav-link rounded-0 py-3 {{ $activeTab === 'semua' ? 'active bg-transparent text-danger border-bottom border-danger border-2' : 'text-dark' }}">All</a>
            </li>
            <li class="nav-item">
                <a wire:click.prevent="setTab('pending')" class="nav-link rounded-0 py-3 {{ $activeTab === 'pending' ? 'active bg-transparent text-danger border-bottom border-danger border-2' : 'text-dark' }}">
                    Pending
                    @php
                        $q = \App\Models\Transaksi::where('status', 'pending');
                        if (getActiveUser()->role === Role::KURIR) $q->where('id_kurir', getActiveUser()->id);
                        $c = $q->count();
                    @endphp
                    @if($c > 0) <span class="text-danger">({{ $c }})</span> @endif
                </a>
            </li>
            <li class="nav-item">
                <a wire:click.prevent="setTab('diproses')" class="nav-link rounded-0 py-3 {{ $activeTab === 'diproses' ? 'active bg-transparent text-danger border-bottom border-danger border-2' : 'text-dark' }}">
                    Diproses
                    @php
                        $q = \App\Models\Transaksi::where('status', 'diproses');
                        if (getActiveUser()->role === Role::KURIR) $q->where('id_kurir', getActiveUser()->id);
                        $c = $q->count();
                    @endphp
                    @if($c > 0) <span class="text-danger">({{ $c }})</span> @endif
                </a>
            </li>
            <li class="nav-item">
                <a wire:click.prevent="setTab('dikirim')" class="nav-link rounded-0 py-3 {{ $activeTab === 'dikirim' ? 'active bg-transparent text-danger border-bottom border-danger border-2' : 'text-dark' }}">
                    Dikirim
                    @php
                        $q = \App\Models\Transaksi::where('status', 'dikirim');
                        if (getActiveUser()->role === Role::KURIR) $q->where('id_kurir', getActiveUser()->id);
                        $c = $q->count();
                    @endphp
                    @if($c > 0) <span class="text-danger">({{ $c }})</span> @endif
                </a>
            </li>
            <li class="nav-item">
                <a wire:click.prevent="setTab('diterima')" class="nav-link rounded-0 py-3 {{ $activeTab === 'diterima' ? 'active bg-transparent text-danger border-bottom border-danger border-2' : 'text-dark' }}">
                    Diterima
                    @php
                        $q = \App\Models\Transaksi::where('status', 'diterima');
                        if (getActiveUser()->role === Role::KURIR) $q->where('id_kurir', getActiveUser()->id);
                        $c = $q->count();
                    @endphp
                    @if($c > 0) <span class="text-danger">({{ $c }})</span> @endif
                </a>
            </li>
            <li class="nav-item">
                <a wire:click.prevent="setTab('selesai')" class="nav-link rounded-0 py-3 {{ $activeTab === 'selesai' ? 'active bg-transparent text-danger border-bottom border-danger border-2' : 'text-dark' }}">Selesai</a>
            </li>
            <li class="nav-item">
                <a wire:click.prevent="setTab('ditolak')" class="nav-link rounded-0 py-3 {{ $activeTab === 'ditolak' ? 'active bg-transparent text-danger border-bottom border-danger border-2' : 'text-dark' }}">Ditolak</a>
            </li>
            <li class="nav-item">
                <a wire:click.prevent="setTab('batal')" class="nav-link rounded-0 py-3 {{ $activeTab === 'batal' ? 'active bg-transparent text-danger border-bottom border-danger border-2' : 'text-dark' }}">Batal</a>
            </li>
        </ul>
    </div>
    @endif
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
                  <th>Ubah Status Pesanan</th>
                  <th>Pilih Kurir</th>
                  <th>Nota</th>
                @elseif(getActiveUser()->role === Role::KURIR)
                  <th>Ubah Status</th>
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
                      @if($item->status === \App\Enums\StatusTransaksi::DIPROSES)
                        dikemas
                      @else
                        {{ $item->status }}
                      @endif
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
                      <select wire:change="updateStatusTransaksi({{ $item->id }}, $event.target.value)" class="form-select form-select-sm" style="min-width: 120px;">
                        @foreach (\App\Enums\StatusTransaksi::cases() as $status)
                          <option value="{{ $status->value }}" {{ $item->status === $status ? 'selected' : '' }}>
                            {{ ucfirst($status->value) }}
                          </option>
                        @endforeach
                      </select>
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
                      <select wire:change="updateStatusTransaksi({{ $item->id }}, $event.target.value)" class="form-select form-select-sm" style="min-width: 120px;">
                        <option value="diproses" {{ $item->status === \App\Enums\StatusTransaksi::DIPROSES ? 'selected' : '' }}>Dikemas</option>
                        <option value="dikirim" {{ $item->status === \App\Enums\StatusTransaksi::DIKIRIM ? 'selected' : '' }}>Dikirim</option>
                        <option value="diterima" {{ $item->status === \App\Enums\StatusTransaksi::DITERIMA ? 'selected' : '' }}>Diterima</option>
                      </select>
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
        <div class="row">
          <div class="col-12">
              <x-pagination :items="$this->transaksiList" />
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
