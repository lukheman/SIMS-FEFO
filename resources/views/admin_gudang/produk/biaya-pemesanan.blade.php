@extends('layouts.main')

@section('title', 'Admin Gudang')

@section('sidebar-menu')

    @include('admin_gudang.menu')

@endsection

@section('content')
<div class="card">
    <div class="card-header">

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
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Biaya Pemesanan (Rp.)</th>

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($produk as $item)
                            <tr>
                                <td class="text-center"> {{ $item->kode_produk }}</td>
                                <td> {{ $item->nama_produk }}</td>
                                <td class="text-center"> {{  number_format($item->biayaPemesanan->biaya, 0, ',', '.') }}</td>
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

<!-- modal-add-persediaan - modal untuk menampilkan form tambah data produk -->
<div class="modal fade show" id="modal-add-persediaan" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Persediaan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-add-persediaan">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <label for="nama-produk">Nama Produk</label>
                        <select name="id_produk" id="nama-produk" class="form-control">
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="periode">Periode</label>
                        <input type="month" name="periode" id="periode" class="form-control" min="0"
                            placeholder="Periode">
                    </div>

                    <!-- <div class="form-group"> -->
                    <!--     <label for="stock">Stok</label> -->
                    <!--     <input type="number" name="stock" id="stock" class="form-control" min="0" -->
                    <!--         placeholder="Stok Produk"> -->
                    <!-- </div> -->

                    <!-- <div class="form-group"> -->
                    <!--     <label for="stock-min">Stok Minimal</label> -->
                    <!--     <input type="number" name="stock_min" id="stock-min" class="form-control" min="0" -->
                    <!--         placeholder="Stok Minimal"> -->
                    <!-- </div> -->

                    <!-- <div class="form-group"> -->
                    <!--     <label for="stock-max">Stok Maksimal</label> -->
                    <!--     <input type="number" name="stock_max" id="stock-max" class="form-control" min="0" -->
                    <!--         placeholder="Stok Minimal"> -->
                    <!-- </div> -->

                    <div class="form-group">
                        <label for="lead-time">Waktu Tunggu</label>
                        <input type="number" name="lead_time" id="lead-time" class="form-control" min="0"
                            placeholder="Waktu Tunggu">
                    </div>

                    <div class="form-group">
                        <label for="rata-rata-penggunaan">Rata-rata penggunaan</label>
                        <input type="number" class="form-control" name="rata_rata_penggunaan" id="rata-rata-penggunaan"
                            placeholder="Rata-rata penggunaan" min="0">
                    </div>

                    <div class="form-group">
                        <label for="biaya-penyimpanan">Biaya Penyimpanan</label>
                        <input type="number" class="form-control" name="biaya_penyimpanan" id="biaya-penyimpanan"
                            placeholder="Biaya Penyimpanan" min="0">
                    </div>

                    <div class="form-group">
                        <label for="biaya-pemesanan">Biaya Pemesanan</label>
                        <input type="number" class="form-control" name="biaya_pemesanan" id="biaya-pemesanan"
                            placeholder="Biaya Pemesanan" min="0">
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal-add-persediaan -->

<!-- modal-update-persediaan - modal untuk menampilkan form tambah data produk -->
<div class="modal fade show" id="modal-update-persediaan" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Persediaan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-update-persediaan">
                @csrf
                <div class="modal-body">

                    <input type="hidden" id="id-persediaan" disabled>

                    <div class="form-group">
                        <label for="nama-produk">Nama Produk</label>

                        <!-- <select name="id_produk" id="nama-produk" class="form-control"> -->
                        <!-- </select> -->
                        <input type="text" name="id_produk" id="nama-produk" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="stock">Stok</label>
                        <input type="number" name="stock" id="stock" class="form-control" min="0"
                            placeholder="Stok Produk" readonly>
                    </div>

                    <!-- <div class="form-group"> -->
                    <!--     <label for="stock-min">Stok Minimal</label> -->
                    <!--     <input type="number" name="stock_min" id="stock-min" class="form-control" min="0" -->
                    <!--         placeholder="Stok Minimal"> -->
                    <!-- </div> -->

                    <!-- <div class="form-group"> -->
                    <!--     <label for="stock-max">Stok Maksimal</label> -->
                    <!--     <input type="number" name="stock_max" id="stock-max" class="form-control" min="0" -->
                    <!--         placeholder="Stok Minimal"> -->
                    <!-- </div> -->

                    <div class="form-group">
                        <label for="lead-time">Waktu Tunggu</label>
                        <input type="number" name="lead_time" id="lead-time" class="form-control" min="0"
                            placeholder="Waktu Tunggu">
                    </div>

                    <div class="form-group">
                        <label for="rata-rata-penggunaan">Rata-rata penggunaan</label>
                        <input type="number" class="form-control" name="rata_rata_penggunaan" id="rata-rata-penggunaan"
                            placeholder="Rata-rata penggunaan" min="0">
                    </div>

                    <div class="form-group">
                        <label for="biaya-penyimpanan">Biaya Penyimpanan</label>
                        <input type="number" class="form-control" name="biaya_penyimpanan" id="biaya-penyimpanan"
                            placeholder="Biaya Penyimpanan" min="0">
                    </div>

                    <div class="form-group">
                        <label for="biaya-pemesanan">Biaya Pemesanan</label>
                        <input type="number" class="form-control" name="biaya_pemesanan" id="biaya-pemesanan"
                            placeholder="Biaya Pemesanan" min="0">
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal-update-persediaan -->
@endsection

@section('custom-script')
<script>

    $(document).ready(function () {
        $('#form-add-persediaan').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('persediaan.store') }}',
                method: 'POST',
                data: $(this).serialize(),
                // data: {
                //     id_produk: 1,
                //     stock: 100,
                //     stock_min: 20,
                //     stock_max: 200,
                //     lead_time: 5,
                //     reorder_point: 30,
                //     safety_stock: 10,
                //     rata_rata_penggunaan: 15,
                //     biaya_penyimpanan: 5000.00,
                //     biaya_pemesanan: 20000.00
                // },
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
                        title: 'Pesediaan gagal ditambahkan',
                        icon: "error",
                    }).then(() => window.location.reload());
                },
            })
        });

        // handler untuk mengupdate data
        $('#form-update-persediaan').on('submit', function (e) {
            e.preventDefault();

            let idPersediaan = $('#id-persediaan').val();

            $.ajax({
                url: `{{ route('persediaan.update', '') }}/${idPersediaan}`,
                method: 'PUT',
                data: $(this).serialize(),
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
                        title: 'persediaan gagal diperbarui',
                        icon: "error",
                    }).then(() => window.location.reload());
                }
            });
        });

        $('#btn-add-persediaan').click(() => {
            $.ajax({
                url: '{{ route('produk.index') }}',
                method: 'GET',
                success: function (data) {
                    data.data.forEach((item) => {
                        $('#nama-produk').append(new Option(`${item.kode_produk} | ${item.nama_produk}`, item.id))
                    });
                },
                error: function (error) {
                    // Swal.fire({
                    //     title: 'Produk gagal dihapus',
                    //     icon: "error",
                    // }).then(() => window.location.reload());
                    console.log(error);
                }

            });
        });

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

        // handle untuk update persediaan
        $('.btn-update-persediaan').click(function () {

            let idPersediaan = $(this).data('id-persediaan');

            let formUpdatePersediaan = $('#form-update-persediaan');

            $.ajax({
                url: `{{ route('persediaan.show', '') }}/${idPersediaan}`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    let persediaan = data.data;
                    console.log(persediaan[0]);

                    formUpdatePersediaan.find('#id-persediaan').val(persediaan.id);
                    formUpdatePersediaan.find('#nama-produk').val(`${persediaan.produk.kode_produk} |${persediaan.produk.nama_produk}`);
                    formUpdatePersediaan.find('#stock').val(persediaan.stock);
                    // formUpdatePersediaan.find('#stock-min').val(persediaan.stock_min);
                    // formUpdatePersediaan.find('#stock-max').val(persediaan.stock_max);
                    formUpdatePersediaan.find('#lead-time').val(persediaan.lead_time);
                    formUpdatePersediaan.find('#rata-rata-penggunaan').val(persediaan.rata_rata_penggunaan);
                    formUpdatePersediaan.find('#biaya-penyimpanan').val(persediaan.biaya_penyimpanan);
                    formUpdatePersediaan.find('#biaya-pemesanan').val(persediaan.biaya_pemesanan);
                    // formUpdatePersediaan.find('#deskripsi').val(persediaan.deskripsi);

                },
                error: function (error) {
                    Swal.fire({
                        title: 'Produk gagal dihapus',
                        icon: "error",
                    }).then(() => window.location.reload());
                }
            });

        });

    });



</script>
@endsection
