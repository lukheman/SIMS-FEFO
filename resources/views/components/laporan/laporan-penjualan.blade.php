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
        Catatan penjualan dibuat secara otomatis ketika pesanan diterima.
    </div>
    <!-- /.card-body -->
</div>

<div class="card">
    <div class="card-header">

        <button class="btn btn-outline-danger" id="btn-cetak-laporan-penjualan" data-toggle="modal"
            data-target="#modal-cetak-laporan-penjualan">
            <i class="fas fa-print"></i>
            Cetak Laporan Penjualan</button>

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
                            <tr class="text-center">
                                <th>Tanggal </th>
                                <th>Nama Produk </th>
                                <th>Jumlah Terjual</th>
                                <th>Harga Produk</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($penjualan as $item)
                            <tr>
                                <td> {{ $item->tanggal }}</td>
                                <td> {{ $item->produk->nama_produk }}</td>
                                <td class="text-right"> {{ $item->label_jumlah_unit_terjual}} </td>
                                <td class="text-right"> {{ $item->label_harga_jual }} </td>
                                <td class="text-right"> {{ $item->label_total_harga_jual }} </td>
                                <td class="text-right">
                                    <button class="btn btn-sm btn-danger btn-delete-penjualan"
                                        data-id-penjualan="{{ $item->id }}">
                                        <i class="fas fa-trash"></i>
                                        Hapus</button>
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


<!-- modal-cetak-laporan-penjualan - modal untuk menampilkan form tambah data produk -->
<div class="modal fade show" id="modal-cetak-laporan-penjualan" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Periode Laporan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('laporan.laporan-penjualan') }}" method="post">
                <input type="hidden" name="ttd" value="{{ $ttd }}">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="periode">Periode</label>
                        <input type="month" class="form-control" name="periode" id="periode">
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-print"></i>
                        Cetak</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    <!-- end modal-cetak-laporan-penjualan -->
</div>

@push('scripts')

<script>

$(document).ready(() => {
    // Gunakan event delegation untuk .btn-delete-penjualan
    $(document).on('click', '.btn-delete-penjualan', function () {
        let idPenjualan = $(this).data('id-penjualan');

        // Confirm deletion with SweetAlert
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Log penjualan akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('mutasi.destroy', ':id') }}".replace(':id', idPenjualan),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        showToast(data.message);
                    },
                    error: function (error) {
                        Swal.fire({
                            title: 'Log penjualan gagal dihapus',
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
