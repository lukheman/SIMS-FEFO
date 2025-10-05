<div class="card">

<div class="modal fade" id="modal-detail-transaksi" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-detail-transaksi-label">Detail Transaksi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <table class="table table-bordered" id="table-detail-transaksi">
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
                    <td>{{ $loop->index + 1}}</td>
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

      <div class="modal-footer justify-content-between">
      </div>
    </div>
  </div>
</div>

    <div class="card-header"></div>
    <div class="card-body">
        <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap5">
            <div class="row">
            </div>
            <div class="row">
                <div class="col-12">
                    <!-- TODO: buat menjadi lebih estetik -->
                    <table id="datatable" class="table table-bordered table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>Tanggal</th>
                                <th>Status Transaksi</th>
                                <th>Metode Pembayaran</th>
                                <th>Status Pembayaran</th>
                                <th>Konfirmasi</th>
                                <th>Info</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($this->transaksiList as $item)
                            <tr class="text-center">
                                <td>{{ $item->tanggal }}</td>

                                <td>
                                <div class="badge bg-{{ $item->status->getColor()}}">{{ $item->status }}</div>
                                </td>

                                <td>
                                    <span class="badge bg-success">{{ $item->metode_pembayaran }}</span>
                                </td>

                                <td>
                                <div class="badge bg-{{ $item->status_pembayaran->getColor()}}">{{ $item->status_pembayaran->label() }}</div>

                                </td>


                                <td>
                                    <button wire:click="pesananSelesai({{ $item->id}})" type="button" class="btn btn-sm btn-outline-success" {{ $item->status === \App\Enums\StatusTransaksi::DITERIMA ? '' : 'disabled' }}>
                                        <i class="bi bi-check2-circle"></i> Pesanan selesai
                                    </button>
                                </td>

                                <td>
                                    <button wire:click="detailTransaksi({{ $item->id}})" type="button" class="btn btn-sm btn-outline-secondary" >
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
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <x-pagination :items="$this->transaksiList" />
                </div>
            </div>
        </div>
    </div>
</div>
