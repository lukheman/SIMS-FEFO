@extends('layouts.main')

@section('title', 'Admin Gudang')

@section('sidebar-menu')

@include('admin_gudang.menu')

@endsection

@section('content')
<div class="card">
    <div class="card-header">

        <button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#modal-pesanan" id="btn-modal-pesanan"> <i class="fas fa-plus"></i> Tambah Pesanan</button>

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
                                <th>Tanggal Pesan</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($pesanan as $item)
                            <tr>
                                <td> {{ $item->created_at }}</td>
                                <td class="text-center"> {{ $item->produk->kode_produk }}</td>
                                <td> {{ $item->produk->nama_produk }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-danger btn-delete-pesanan"
                                        data-id-pesanan="{{ $item->id }}">
                                        <i class="fas fa-trash"> </i>
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

<!-- modal-pesanan: modal untuk menampilkan form tambah pesanan -->
<div class="modal fade show" id="modal-pesanan" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Persediaan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-add-pesanan">
                @csrf
                <div class="modal-body">

                    <!-- <input type="hidden" name="id_produk" id="id-produk"> -->

                    <div class="form-group">
                        <label for="kode-produk">Kode Produk</label>
                        <select class="form-control" name="id_produk" id="kode-produk" width="100%">

                        </select>
                    </div>


                    <div class="form-group">
                        <label for="nama-produk">Nama Produk</label>
                        <input type="text" class="form-control" name="nama_produk" id="nama-produk">
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"> </i>
                        Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal-pesanan -->

@endsection

@section('custom-script')
<script>

$(document).ready(function () {

$('#kode-produk').select2({
    dropdownParent: $('#modal-pesanan'),
    placeholder: 'Pilih Produk',
    allowClear: true,
    width: '100%'
});


    $('#btn-modal-pesanan').click(function() {

        $.ajax({
            url: "{{ route('produk.index') }}",
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {

                // Kosongkan dulu isi <select> sebelum menambahkan opsi baru
                $('#kode-produk').empty().append('<option disabled selected>Pilih Produk</option>');

                $.each(data.data, function(index, produk) {
                    $('#kode-produk').append(
                        $('<option>', {
                            value: produk.id,
                            text: `${produk.kode_produk}`
                        })
                    );
                });

                // Jika pakai Select2, refresh tampilannya
            if ($.fn.select2) {
                $('#kode-produk').select2({
                    dropdownParent: $('#modal-pesanan'),
                    placeholder: 'Pilih Produk',
                    allowClear: true,
                    width: '100%'
                });
            }

            },
            error: function (error) {
                console.log(error);
            },
        });
    });

    $('#form-add-pesanan').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route('restock.store') }}',
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                showToast(data.message);
            },
            error: function (error) {
                console.log(error);
                Swal.fire({
                    title: 'Pesediaan gagal ditambahkan',
                    icon: "error",
                }).then(() => window.location.reload());
            },
        })
    });


    // handler untuk menghapus data
    $('#datatable').on('click', '.btn-delete-pesanan', function() {

        let idRestock = $(this).data('id-pesanan');

        $.ajax({
            url: `{{ route('restock.destroy', ':id') }}`.replace(':id', idRestock),
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if(data.success) {
                    showToast(data.message);
                }
            },
            error: function (error) {
                Swal.fire({
                    title: 'Data barang masuk gagal dihapus.',
                    icon: "error",
                }).then(() => window.location.reload());
            }
        });

    });

    $('#kode-produk').on('input', function () {
        const id = $(this).val();
        const url = `{{ route('produk.show', ':id') }}`.replace(':id', id);

        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#nama-produk').val(response.data.nama_produk);
                    $('#id-produk').val(response.data.id);
                } else {
                    $('#nama-produk').val('');
                    $('#id-produk').val('');
                    showToast(response.message, 'warning', false);
                }
            },
            error: function (xhr, status, error) {
                $('#nama-produk').val('');
                console.error('AJAX error:', error);
                showToast('Terjadi kesalahan saat mengambil data produk.', 'error', false);
            }
        });
    });


});



</script>

@endsection
