@extends('layouts.main')

@section('title', 'Admin Gudang')

@section('sidebar-menu')

@include('admin_gudang.menu')

@endsection

<!-- TODO: validasi ketika biaya Penyimpanan dan pemesanan tidak boleh 0 -->
@section('content')
<div class="card">
    <div class="card-header">

        <div class="row">
            <div class="col-6">
                <button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#modal-scanner">
                <i class="fas fa-plus"></i> Tambah Produk</button>
            </div>

            <div class="col-6 d-flex justify-content-end">
            </div>
        </div>

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
                                <th>Tanggal Kadaluarsa</th>
                                <th>Harga Beli/bal (Rp)</th>
                                <th>Harga Jual/bal (Rp)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($produk as $item)
                            <tr>
                                <td class="text-center"> {{ $item->kode_produk }}</td>
                                <td> {{ $item->nama_produk }}</td>
                                <td> {{ $item->exp }}</td>
                                <td> {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                <td> {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                <td class="text-center">
                                        <button class="btn btn-sm btn-info btn-info-produk" data-toggle="modal"
                                            data-target="#modal-info-produk" data-id-produk="{{ $item->id }}">
                                            <i class="fas fa-info"></i>
                                            Info</button>
                                        <button class="text-white btn btn-sm btn-warning btn-update-produk" data-toggle="modal"
                                            data-target="#modal-update-produk" data-id-produk="{{ $item->id }}">
                                            <i class="fas fa-edit"></i>
                                            Edit</button>
                                        <button class="btn btn-sm btn-danger btn-delete-produk"
                                            data-id-produk="{{ $item->id }}">
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


<div class="modal fade show" id="modal-scanner" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Scan Barcode</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="scanner"></div>
            </div>
        </div>
    </div>
</div>

<!-- modal-add-produk - modal untuk menampilkan form tambah data produk -->
<div class="modal fade show" id="modal-add-produk" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Produk</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-add-produk">
                @csrf
                <div class="modal-body">

                    <div class="row input-produk-main">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode-produk">Barcode Produk</label>
                                <input type="text" class="form-control" name="kode_produk" id="kode-produk">
                            </div>

                            <div class="form-group">
                                <label for="nama-produk">Nama Produk</label>
                                <input type="text" class="form-control" name="nama_produk" id="nama-produk"
                                    placeholder="Nama Produk">
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="tanggal-kadaluarsa">Tanggal Kadaluarsa</label>
                                <input type="date" class="form-control" name="exp" id="tanggal-kadaluarsa" min="{{ date('Y-m-d') }}">
                            </div>

                            <div class="form-group">
                                <label for="gambar">Gambar</label>
                                <input type="file" class="form-control" name="gambar" id="gambar">
                            </div>



                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-4">

                                    <div class="form-group">
                                        <label for="unit-kecil">Unit Kecil</label>
                                        <select name="unit_kecil" class="form-control" id="unit-kecil">

                                            @foreach (\App\Constants\UnitKecilProduk::values() as $item)

                                            <option value="{{ $item }}">{{ $item}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">

                                    <div class="form-group">

                                        <label for="unit-besar">Unit Besar</label>
                                        <select name="unit_besar" class="form-control" id="unit-besar">

                                            @foreach (\App\Constants\UnitBesarProduk::values() as $item)

                                            <option value="{{ $item }}">{{ $item}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">

                                    <div class="form-group">
                                        <label for="tingkat_konversi">Jumlah <span class="unit-kecil"></span>/<span class="unit-besar"></span> </label>
                                        <input type="number" class="form-control" name="tingkat_konversi" id="tingkat_konversi" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-4">

                            <div class="form-group">
                                <label for="harga-beli">Harga Beli/<span class="unit-besar"></span> (Rp.)</label>
                                <input type="number" class="form-control" name="harga_beli" id="harga-beli" min="0">
                            </div>
                                </div>
                                <div class="col-4">

                            <div class="form-group">
                                <label for="harga-jual">Harga Jual/<span class="unit-besar"></span> (Rp.)</label>
                                <input type="number" class="form-control" name="harga_jual" id="harga-jual" min="0">
                            </div>
                                </div>
                                <div class="col-4">

                            <div class="form-group">
                                <label for="harga_jual_unit_kecil">Harga Jual/<span class="unit-kecil"></span> (Rp.)</label>
                                <input type="number" class="form-control" name="harga_jual_unit_kecil" id="harga_jual_unit_kecil" min="0">
                            </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="row d-none input-produk-biaya">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="biaya-pemesanan">Biaya Pemesanan</label>
                                <input type="number" class="form-control" name="biaya_pemesanan" id="biaya-pemesanan"
                                    placeholder="Biaya Pemesanan" min="0">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="biaya-penyimpanan">Biaya Penyimpanan</label>
                                <input type="number" class="form-control" name="biaya_penyimpanan"
                                    id="biaya-penyimpanan" placeholder="Biaya Penyimpanan" min="0">
                            </div>
                        </div>
                    </div>

                </div>


                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>

                    <div class="row btn-group-biaya" style="display: none;">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary btn-back-to-main-input">  <i class="fas fa-arrow-left"></i> Kembali</button>
                            <button type="submit" class="btn btn-primary"> <i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </div>


                    <button type="button" class="btn btn-primary btn-show-form-biaya"> Lanjut <i class="fas fa-arrow-right"></i> </button>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal-add-produk -->

<!-- modal-update-produk - modal untuk menampilkan form tambah data produk -->
<div class="modal fade" id="modal-update-produk" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Produk</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-update-produk">
                @csrf

                <input type="hidden" id="id-produk" disabled>

                <div class="modal-body">

                    <div class="row input-produk-main">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kode-produk">Barcode Produk</label>
                                <input type="text" class="form-control" name="kode_produk" id="kode-produk">
                            </div>

                            <div class="form-group">
                                <label for="nama-produk">Nama Produk</label>
                                <input type="text" class="form-control" name="nama_produk" id="nama-produk"
                                    placeholder="Nama Produk">
                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="tanggal-kadaluarsa">Tanggal Kadaluarsa</label>
                                <input type="date" class="form-control" name="exp" id="tanggal-kadaluarsa" min="{{ date('Y-m-d') }}">
                            </div>

                            <div class="form-group">
                                <label for="gambar">Gambar</label>
                                <input type="file" class="form-control" name="gambar" id="gambar">
                            </div>



                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-4">

                                    <div class="form-group">
                                        <label for="unit-kecil">Unit Kecil</label>
                                        <select name="unit_kecil" class="form-control" id="unit-kecil">

                                            @foreach (\App\Constants\UnitKecilProduk::values() as $item)

                                            <option value="{{ $item }}">{{ $item}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">

                                    <div class="form-group">

                                        <label for="unit-besar">Unit Besar</label>
                                        <select name="unit_besar" class="form-control" id="unit-besar">

                                            @foreach (\App\Constants\UnitBesarProduk::values() as $item)

                                            <option value="{{ $item }}">{{ $item}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">

                                    <div class="form-group">
                                        <label for="tingkat_konversi">Jumlah <span class="unit-kecil"></span>/<span class="unit-besar"></span> </label>
                                        <input type="number" class="form-control" name="tingkat_konversi" id="tingkat_konversi" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-4">

                            <div class="form-group">
                                <label for="harga-beli">Harga Beli/<span class="unit-besar"></span> (Rp.)</label>
                                <input type="number" class="form-control" name="harga_beli" id="harga-beli" min="0">
                            </div>
                                </div>
                                <div class="col-4">

                            <div class="form-group">
                                <label for="harga-jual">Harga Jual/<span class="unit-besar"></span> (Rp.)</label>
                                <input type="number" class="form-control" name="harga_jual" id="harga-jual" min="0">
                            </div>
                                </div>
                                <div class="col-4">

                            <div class="form-group">
                                <label for="harga_jual_unit_kecil">Harga Jual/<span class="unit-kecil"></span> (Rp.)</label>
                                <input type="number" class="form-control" name="harga_jual_unit_kecil" id="harga_jual_unit_kecil" min="0">
                            </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="row d-none input-produk-biaya">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="biaya-pemesanan">Biaya Pemesanan</label>
                                <input type="number" class="form-control" name="biaya_pemesanan" id="biaya-pemesanan"
                                    placeholder="Biaya Pemesanan" min="0">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="biaya-penyimpanan">Biaya Penyimpanan</label>
                                <input type="number" class="form-control" name="biaya_penyimpanan"
                                    id="biaya-penyimpanan" placeholder="Biaya Penyimpanan" min="0">
                            </div>
                        </div>
                    </div>

                </div>


                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>

                    <div class="row btn-group-biaya" style="display: none;">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary btn-back-to-main-input" >  <i class="fas fa-arrow-left"></i> Kembali</button>
                            <button type="submit" class="btn btn-primary"> <i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </div>


                    <button type="button" class="btn btn-primary btn-show-form-biaya"> Lanjut <i class="fas fa-arrow-right"></i> </button>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal-update-produk -->

<!-- modal-info-produk - modal untuk menampilkan form tambah data produk -->
<div class="modal fade" id="modal-info-produk" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Info Produk</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row input-produk-main">
                    <!-- Gambar -->
                    <div class="col-md-4">
                        <img src="" class="img-fluid border mb-3" id="gambar-produk">
                    </div>

                    <!-- Kolom kiri -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="kode-produk">Barcode Produk</label>
                            <input type="text" class="form-control" id="kode-produk" readonly>
                        </div>

                        <div class="form-group">
                            <label for="nama-produk">Nama Produk</label>
                            <input type="text" class="form-control" id="nama-produk" readonly>
                        </div>

                        <div class="form-group">
                            <label for="tanggal-kadaluarsa">Tanggal Kadaluarsa</label>
                            <input type="date" class="form-control" id="tanggal-kadaluarsa" readonly>
                        </div>
                    </div>

                    <!-- Kolom kanan -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="unit-kecil">Unit Kecil</label>
                            <input type="text" class="form-control" id="unit-kecil" readonly>
                        </div>

                        <div class="form-group">
                            <label for="unit-besar">Unit Besar</label>
                            <input type="text" class="form-control" id="unit-besar" readonly>
                        </div>

                        <div class="form-group">
                            <label for="tingkat_konversi">Jumlah <span class="unit-kecil"></span>/<span class="unit-besar"></span></label>
                            <input type="number" class="form-control" id="tingkat_konversi" readonly>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <!-- Harga -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="harga-beli">Harga Beli/<span class="unit-besar"></span> (Rp)</label>
                            <input type="number" class="form-control" id="harga-beli" readonly>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="harga-jual">Harga Jual/<span class="unit-besar"></span> (Rp)</label>
                            <input type="number" class="form-control" id="harga-jual" readonly>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="harga_jual_unit_kecil">Harga Jual/<span class="unit-kecil"></span> (Rp)</label>
                            <input type="number" class="form-control" id="harga_jual_unit_kecil" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Biaya -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="biaya-pemesanan">Biaya Pemesanan</label>
                            <input type="number" class="form-control" id="biaya-pemesanan" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="biaya-penyimpanan">Biaya Penyimpanan</label>
                            <input type="number" class="form-control" id="biaya-penyimpanan" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi Produk</label>
                    <textarea class="form-control" id="deskripsi" rows="3" readonly></textarea>
                </div>
            </div>

            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<!-- end modal-info-produk -->
@endsection

@section('custom-script')

<script>
$(document).ready(function () {
    const updateUnitText = () => {
        $('.unit-besar').text($('#form-add-produk #unit-besar').val());
        $('.unit-kecil').text($('#form-add-produk #unit-kecil').val());
    };

    updateUnitText();

    $('#form-add-produk #unit-besar, #form-add-produk #unit-kecil').on('change', updateUnitText);

    $('.btn-show-form-biaya').click(function () {
        $('.input-produk-biaya').addClass('d-block');
        $('.input-produk-main').removeClass('d-flex').addClass('d-none');
        $(this).removeClass('d-block').addClass('d-none');
        $('.btn-group-biaya').addClass('d-block');
    });

    $('.btn-back-to-main-input').click(function () {
        $('.input-produk-main').addClass('d-flex');
        $('.input-produk-biaya').removeClass('d-block').addClass('d-none');
        $('.btn-show-form-biaya').addClass('d-block');
        $('.btn-group-biaya').removeClass('d-block').addClass('d-none');
    });

    const handleAjaxError = (error) => {
        let message = "Terjadi kesalahan";
        if (error.responseJSON?.message) {
            message = error.responseJSON.message;
        } else if (error.responseText) {
            message = error.responseText;
        } else if (error.statusText) {
            message = error.statusText;
        }
        showToast(message, 'warning', false);
    };

    $('#form-add-produk').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        $.ajax({
            url: "{{ route('produk.store') }}",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (data) {
                data.success ? showToast(data.message) : Swal.fire({ title: "Hubungi admin", icon: "danger" }).then(() => window.location.reload());
            },
            error: handleAjaxError
        });
    });

    $('#form-update-produk').on('submit', function (e) {
        e.preventDefault();
        const idProduk = $('#id-produk').val();
        const formData = new FormData(this);
        formData.append('_method', 'PUT');
        $.ajax({
            url: `{{ route('produk.update', ':id') }}`.replace(':id', idProduk),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (data) { showToast(data.message); },
            error: handleAjaxError
        });
    });

    $('#datatable').on('click', '.btn-delete-produk', function () {
        const idProduk = $(this).data('id-produk');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Produk akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ route('produk.destroy', ':id') }}`.replace(':id', idProduk),
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (data) {
                        if (data.success) showToast(data.message);
                    },
                    error: () => Swal.fire({ title: 'Produk gagal dihapus', icon: "error" }).then(() => window.location.reload())
                });
            }
        });
    });

    function populateProdukForm(form, produk) {
        form.find('#id-produk').val(produk.id);
        form.find('#kode-produk').val(produk.kode_produk);
        form.find('#nama-produk').val(produk.nama_produk);
        form.find('#harga-jual').val(produk.harga_jual);
        form.find('#harga-beli').val(produk.harga_beli);
        form.find('#biaya-penyimpanan').val(produk.biaya_penyimpanan?.biaya ?? '');
        form.find('#biaya-pemesanan').val(produk.biaya_pemesanan?.biaya ?? '');
        form.find('#deskripsi').val(produk.deskripsi);
        form.find('#tanggal-kadaluarsa').val(produk.exp);
        form.find('#harga_jual_unit_kecil').val(produk.harga_jual_unit_kecil);
        form.find('#tingkat_konversi').val(produk.tingkat_konversi);
        form.find('#unit-kecil').val(produk.unit_kecil);
        form.find('#unit-besar').val(produk.unit_besar);
        form.find('.unit-kecil').text(produk.unit_kecil);
        form.find('.unit-besar').text(produk.unit_besar);

        if (form.find('#gambar-produk').length) {
            form.find('#gambar-produk')
                .val(produk.gambar)
                .attr('src', "{{ asset('storage') }}/" + produk.gambar)
                .attr('alt', produk.nama_produk);
        }
    }

    $('#datatable').on('click', '.btn-update-produk', function () {
        const idProduk = $(this).data('id-produk');
        const formUpdateProduk = $('#form-update-produk');
        $.ajax({
            url: `{{ route('produk.show', '') }}/${idProduk}`,
            method: 'GET',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (data) {
                populateProdukForm(formUpdateProduk, data.data);
            },
            error: function (error) {
                console.error(error);
                Swal.fire({ title: 'Produk gagal dimuat', icon: "error" }).then(() => window.location.reload());
            }
        });
    });

    $('#datatable').on('click', '.btn-info-produk', function () {
        const idProduk = $(this).data('id-produk');
        const modalInfoProduk = $('#modal-info-produk');
        $.ajax({
            url: `{{ route('produk.show', ':id') }}`.replace(':id', idProduk),
            method: 'GET',
            success: function (data) {
                populateProdukForm(modalInfoProduk, data.data);
            },
            error: function (error) {
                console.error(error);
                Swal.fire({ title: 'Data produk gagal didapatkan', icon: "error" }).then(() => window.location.reload());
            }
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
// Fungsi untuk mulai scan barcode
function startScanner() {
    Quagga.init({
        inputStream: {
            type: "LiveStream",
            target: document.querySelector('#scanner'), // Menampilkan stream kamera di elemen ini
            constraints: {
                facingMode: "environment" // Menggunakan kamera belakang (untuk perangkat mobile)
            }
        },
        decoder: {
            readers: ["code_128_reader", "ean_reader", "upc_reader"]
        }
    }, function(err) {
            if (err) {
                console.error(err);
                return;
            }
            Quagga.start(); // Memulai pemindaian
        });

    // Event listener untuk mendapatkan hasil pemindaian
    Quagga.onDetected(function(result) {
        const barcode = result.codeResult.code;

        document.getElementById('kode-produk').value = barcode; // Menampilkan hasil scan

        $('#modal-scanner').modal('hide');

        $('#modal-add-produk').modal('show');



    });
}

// Mulai scanner
startScanner();
</script>
@endsection
