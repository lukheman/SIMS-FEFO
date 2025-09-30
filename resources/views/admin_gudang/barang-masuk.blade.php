@extends('layouts.main')

@section('title', 'Admin Gudang')

@section('sidebar-menu')
    @include('admin_gudang.menu')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <button class="btn btn-outline-primary" data-toggle="modal" data-target="#modal-scan-barcode" id="btn-add-mutasi">
            <i class="fas fa-barcode"></i> Scan Barcode
        </button>
    </div>
    <div class="card-body">
        <div class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12">
                    <table id="datatable" class="table table-bordered table-striped dataTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Produk</th>
                                <th>Jumlah Dipesan</th>
                                <th>Harga Beli</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang_masuk as $item)
                                <tr>
                                    <td>{{ $item->tanggal }}</td>
                                    <td>{{ $item->produk->nama_produk }}</td>
                                    <td>{{ $item->label_jumlah_unit_dipesan}}</td>
                                    <td>{{ $item->produk->label_harga_beli}}</td>
                                    <td>{{ $item->total_harga_beli}}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning btn-update-mutasi" data-id-mutasi="{{ $item->id }}" data-toggle="modal" data-target="#modal-update-mutasi">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-delete-mutasi" data-id-mutasi="{{ $item->id }}">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-7 offset-md-5">
                    <div class="dataTables_paginate paging_simple_numbers" id="datatable_paginate">
                        <ul class="pagination"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Scan Barcode -->
<div class="modal fade" id="modal-scan-barcode" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Scan Barcode</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="scanner"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Mutasi -->
<div class="modal fade" id="modal-add-mutasi" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Persediaan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-add-mutasi">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="jenis" value="masuk">
                    <input type="hidden" name="id_produk" id="id-produk">
                    <div class="form-group">
                        <label for="kode-produk">Barcode Produk</label>
                        <input type="text" class="form-control" name="kode_produk" id="kode-produk" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama-produk">Nama Produk</label>
                        <input type="text" class="form-control" name="nama_produk" id="nama-produk" placeholder="Nama Produk" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah (<span class="unit-kecil"></span>)</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah-bal">Jumlah (<span class="unit-besar"></span>)</label>
                        <input type="number" name="jumlah_bal" id="jumlah-bal" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Update Mutasi -->
<div class="modal fade" id="modal-update-mutasi" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Persediaan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-update-mutasi">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                    <input type="hidden" name="id_mutasi" id="id-mutasi">
                    <input type="hidden" name="id_produk" id="id-produk">
                    <div class="form-group">
                        <label for="kode-produk">Barcode Produk</label>
                        <input type="text" class="form-control" name="kode_produk" id="kode-produk" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama-produk">Nama Produk</label>
                        <input type="text" class="form-control" name="nama_produk" id="nama-produk" placeholder="Nama Produk" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah (<span class="unit-kecil"></span>)</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah-bal">Jumlah (<span class="unit-besar"></span>)</label>
                        <input type="number" name="jumlah_bal" id="jumlah-bal" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('custom-script')
<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
$(document).ready(function () {
    let currentProduk = null;

    const handleAjax = (url, method, data, successMessage, errorMessage) => {
        $.ajax({
            url,
            method: method === 'PUT' ? 'POST' : method,
            data,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: (data) => {
                Swal.fire({ title: successMessage, icon: 'success' }).then(() => window.location.reload());
            },
            error: (error) => {
                console.error(error);
                Swal.fire({ title: errorMessage, icon: 'error' }).then(() => window.location.reload());
            }
        });
    };

    const setupJumlahConversion = (formId, tingkatKonversi) => {
        $(`${formId} #jumlah`).on('change', function() {
            const pcs = $(this).val();
            $(`${formId} #jumlah-bal`).val(pcs / tingkatKonversi);
        });
        $(`${formId} #jumlah-bal`).on('change', function() {
            const bal = $(this).val();
            $(`${formId} #jumlah`).val(bal * tingkatKonversi);
        });
    };

    const populateMutasiForm = (form, mutasi) => {
        form.find('#id-mutasi').val(mutasi.id);
        form.find('#id-produk').val(mutasi.id_produk);
        form.find('#kode-produk').val(mutasi.produk.kode_produk);
        form.find('#nama-produk').val(mutasi.produk.nama_produk);
        form.find('#jumlah').val(mutasi.jumlah);
        form.find('#jumlah-bal').val(mutasi.jumlah / mutasi.produk.tingkat_konversi);
        form.find('.unit-kecil').text(mutasi.produk.unit_kecil);
        form.find('.unit-besar').text(mutasi.produk.unit_besar);
        setupJumlahConversion('#form-update-mutasi', mutasi.produk.tingkat_konversi);
    };

    $('#form-add-mutasi').on('submit', function(e) {
        e.preventDefault();
        handleAjax(
            '{{ route("mutasi.store") }}',
            'POST',
            $(this).serialize(),
            'Persediaan berhasil ditambahkan',
            'Persediaan gagal ditambahkan'
        );
    });

    $('#form-update-mutasi').on('submit', function(e) {
        e.preventDefault();
        const idMutasi = $(this).find('#id-mutasi').val();
        handleAjax(
            `{{ route('mutasi.update', ':id') }}`.replace(':id', idMutasi),
            'PUT',
            $(this).serialize(),
            'Persediaan berhasil diperbarui',
            'Persediaan gagal diperbarui'
        );
    });

    $('#datatable').on('click', '.btn-update-mutasi', function() {
        const idMutasi = $(this).data('id-mutasi');
        $.ajax({
            url: `{{ route('mutasi.show', ':id') }}`.replace(':id', idMutasi),
            method: 'GET',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: (data) => {
                const mutasi = data.data;
                if (data.success) {
                    populateMutasiForm($('#form-update-mutasi'), mutasi);
                } else {
                    showToast(data.message, 'error');
                }
            },
            error: () => showToast('Gagal memuat data mutasi', 'error')
        });
    });

    $('#datatable').on('click', '.btn-delete-mutasi', function() {
        const idMutasi = $(this).data('id-mutasi');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Persediaan akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                handleAjax(
                    `{{ route('mutasi.destroy', ':id') }}`.replace(':id', idMutasi),
                    'DELETE',
                    {},
                    'Data barang masuk berhasil dihapus',
                    'Data barang masuk gagal dihapus'
                );
            }
        });
    });

    const startScanner = () => {
        Quagga.init({
            inputStream: {
                type: 'LiveStream',
                target: document.querySelector('#scanner'),
                constraints: { facingMode: 'environment' }
            },
            decoder: { readers: ['code_128_reader', 'ean_reader', 'upc_reader'] }
        }, (err) => {
            if (err) {
                console.error(err);
                return;
            }
            Quagga.start();
        });

        Quagga.onDetected((result) => {
            const barcode = result.codeResult.code;
            Quagga.stop();
            $.ajax({
                url: `{{ route('restock.exist', ':code') }}`.replace(':code', barcode),
                method: 'GET',
                success: (data) => {
                    if (data.success) {
                        $.ajax({
                            url: `{{ route('produk.kode-produk', ':code') }}`.replace(':code', barcode),
                            method: 'GET',
                            success: (produkData) => {
                                if (produkData.success) {
                                    currentProduk = produkData.data;
                                    $('#modal-scan-barcode').modal('hide');
                                    $('#modal-add-mutasi').modal('show');
                                    $('#form-add-mutasi #kode-produk').val(currentProduk.kode_produk);
                                    $('#form-add-mutasi #nama-produk').val(currentProduk.nama_produk);
                                    $('#form-add-mutasi #id-produk').val(currentProduk.id);
                                    $('#form-add-mutasi .unit-besar').text(currentProduk.unit_besar);
                                    $('#form-add-mutasi .unit-kecil').text(currentProduk.unit_kecil);
                                    setupJumlahConversion('#form-add-mutasi', currentProduk.tingkat_konversi);
                                } else {
                                    showToast(produkData.message, 'error');
                                }
                            },
                            error: () => showToast('Gagal memuat data produk', 'error')
                        });
                    } else {
                        showToast(data.message, 'error');
                    }
                    setTimeout(startScanner, 500);
                },
                error: () => {
                    showToast('Gagal memeriksa restock', 'error');
                    setTimeout(startScanner, 500);
                }
            });
        });
    };

    startScanner();
});
</script>
@endsection
