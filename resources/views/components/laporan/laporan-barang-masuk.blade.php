<div class="card">
    <div class="card-header">

        <button class="btn btn-outline-danger" id="btn-cetak-laporan-barang-masuk" data-toggle="modal"
            data-target="#modal-cetak-laporan-barang-masuk">
            <i class="fas fa-print"></i>
            Cetak Laporan</button>

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
                    <table id="datatable" class="table table-bordered table-striped dataTable dtr-inline">
                        <thead>
                            <tr>
                                <th>Tanggal </th>
                                <th>Jenis Produk </th>
                                <th>Jumlah Dipesan</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($barang_masuk as $item)
                            <tr>
                                <td> {{ $item->tanggal }}</td>
                                <td> {{ $item->produk->nama_produk }}</td>
                                <td> {{ $item->label_jumlah_unit_dipesan}}</td>
                                <td> {{ $item->label_total_harga_beli}}</td>
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

<!-- modal-cetak-laporan-barang-masuk - modal untuk menampilkan form tambah data produk -->
<div class="modal fade show" id="modal-cetak-laporan-barang-masuk" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Periode Laporan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('laporan.laporan-barang-masuk') }}" method="post">
                @csrf
                <input type="hidden" name="ttd" value="Admin Gudang">
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
</div>
<!-- end modal-cetak-laporan-barang-masuk -->

@push('scripts')

<script>
$(document).ready(function () {

    // handler untuk menghapus data
    $('.btn-delete-persediaan').click(function () {

        let idPersediaan = $(this).data('id-persediaan');

        // Confirm deletion with SweetAlert
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Persediaan akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {

                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ route('persediaan.destroy', '') }}/${idPersediaan}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            Swal.fire({
                                title: data.message,
                                icon: "success",
                            }).then(() => window.location.reload());
                        },
                        error: function (error) {
                            Swal.fire({
                                title: 'Persediaan gagal dihapus',
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
