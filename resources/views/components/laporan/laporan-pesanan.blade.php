<div class="card card-outline card-warning">
    <div class="card-header">
        <h3 class="card-title">Perhatian</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
        <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        Catatan pesanan dibuat secara otomatis ketika pesanan diterima.
    </div>
    <!-- /.card-body -->
</div>

<div class="card">
    <div class="card-header">

<button class="btn btn-outline-danger" id="btn-cetak-laporan-pesanan"
    data-bs-toggle="modal" data-bs-target="#modal-cetak-laporan-pesanan">
    <i class="fas fa-print"></i>
    Cetak Laporan Pesanan
</button>

    </div>
    <div class="card-body">
        <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                </div>
                <div class="col-sm-12 col-md-6">

                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table id="datatable" class="table table-bordered table-striped dataTable dtr-inline"
                        aria-describedby="datatable_info">
                        <thead>
                            <tr>
                                <th>Tanggal </th>
                                <th>Pemesan </th>
                                <th>Status Transaksi</th>
                                <th>Metode Pembayaran</th>
                                <th>Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($pesanan as $item)
                            <tr>
                                <td> {{ $item->tanggal }}</td>
                                <td> {{ $item->user->name }}</td>
                                <td>
                                    <x-status-transaksi :status="$item->status" />
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $item->metode_pembayaran }}</span>

                                </td>
                                <td>

                                    <button class="btn btn-sm btn-success btn-status-pembayaran-lunas" data-id-transaksi="{{ $item->id }}" {{ $item->metode_pembayaran === \App\Enums\MetodePembayaran::COD ? 'disabled' : ''}} >
                                        <i class="fas fa-money-check"></i> Lunas</button>
                                </td>

                            </tr>
                            @endforeach

                        </tbody>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-5">
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers" id="datatable_paginate">
                        <ul class="pagination">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- modal-cetak-laporan-pesanan - modal untuk menampilkan form tambah data produk -->
<div class="modal fade" id="modal-cetak-laporan-pesanan" tabindex="-1" aria-labelledby="modalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Periode Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="{{ route('laporan.laporan-pesanan') }}" method="post">
                <input type="hidden" name="ttd" value="{{ $ttd }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="periode" class="form-label">Periode</label>
                        <input type="month" class="form-control" name="periode" id="periode">
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-print"></i>
                        Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')

<script>

$(document).ready(() => {
    // Gunakan event delegation untuk .btn-delete-pesanan
    $(document).on('click', '.btn-delete-pesanan', function () {
        let idpesanan = $(this).data('id-pesanan');

        // Confirm deletion with SweetAlert
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Log pesanan akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('mutasi.destroy', ':id') }}".replace(':id', idpesanan),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        showToast(data.message);
                    },
                    error: function (error) {
                        Swal.fire({
                            title: 'Log pesanan gagal dihapus',
                            icon: "error",
                        }).then(() => window.location.reload());
                    }
                });
            }
        });
    });
});

</script>

@endpush
