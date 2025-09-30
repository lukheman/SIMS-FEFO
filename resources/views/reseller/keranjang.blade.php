@extends('layouts.main')

@section('title', 'Toko Kecil')

@section('sidebar-menu')
@include('reseller.menu')
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-outline-primary" id="btn-show-modal-metode-pembayaran"  {{ $pesanan->count() == 0 ? 'disabled' : '' }}>
        <i class="fas fa-cash-register"></i>
        Checkout</button>

        <button type="button" class="btn btn-outline-danger" id="btn-delete-pilihan-pesanan" style="display: none;">
        <i class="fas fa-trash"></i>Hapus</button>
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
                    <!-- TODO: buat menjadi lebih estetik -->
                    <table id="datatable" class="table table-bordered table-striped dataTable dtr-inline"
                        aria-describedby="datatable_info">
                        <thead>
                            <tr>
                                <!-- TODO: ganti jadi select all -->
                                <th><input type="checkbox" id="select-all" style="width: 25px; height: 25px;"></th>
                                <th>Nama Produk</th>
                                <th>Jumlah Dipesan</th>
                                <th>Harga</th>
                                <th>Total Harga</th>
                                <th class="column-aksi text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($pesanan as $item)
                            <tr>
                                <td > <input type="checkbox" class="select-row" data-id-pesanan="{{ $item->id }}"  style="width: 25px; height: 25px;"> </td>
                                <td> {{ $item->produk->nama_produk }}</td>
                                <td> {{ $item->label_jumlah_unit_dipesan }}</td>
                                <td> {{ $item->produk->label_harga_jual }}</td>
                                <td> {{ $item->label_total_harga_jual }}</td>
                                <td class="text-right">
                                    <button class="btn btn-outline-danger btn-sm btn-delete-pesanan" data-id-pesanan="{{ $item->id }}">
                                        <i class="fas fa-trash"></i>
                                        Hapus</button>

                                    <button class="btn btn-sm btn-outline-primary btn-tambah-pesanan" data-id-pesanan="{{ $item->id }}" data-toggle="modal" data-target="#modal-update-pesanan">
                                        <i class="fas fa-plus"></i>
                                        Tambah Pesanan</button>
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

<div class="modal fade show" id="modal-update-pesanan" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Pesanan</h4> <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-update-pesanan">
                @csrf @method('PUT') <input type="hidden" id="id-pesanan" name="id_pesanan">
                <input type="hidden" id="id-produk" name="id_produk"> <input type="hidden" id="hidden-satuan-value" name="satuan" value="0"> <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center mb-3 mb-sm-0">
                            <img id="img-gambar-produk" class="img-fluid rounded border p-2" width="200" alt="Gambar Produk">
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-2">
                                <h5 class="mb-1" id="nama-produk"></h5>
                                <p class="text-muted mb-1"><small id="deskripsi-produk"></small></p>
                                <p class="text-primary font-weight-bold mb-1" id="total-harga-display"></p> <p class="text-info mb-2"><small><i class="fas fa-info-circle"></i> Pemesanan dalam satuan <strong id="satuan-text">bal</strong></small></p>
                            </div>

                            <div class="form-group">
                                <label for="select-satuan">Satuan</label>
                                <select name="selected_unit_display" id="satuan" class="form-control form-control-sm" style="width: 140px;">
                                    <option id="unit-besar" value="unit_besar_placeholder"></option>
                                    <option id="unit-kecil" value="unit_kecil_placeholder"></option>
                                </select>
                            </div>

                            <div class="input-group input-group-sm" style="width: 140px;">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-outline-secondary" id="btn-kurang-jumlah">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" name="jumlah" id="jumlah" class="form-control text-center" value="1">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="btn-tambah-jumlah">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="nav-icon fas fa-save"></i> Simpan Perubahan </button>
                </div>
            </form>
        </div>
        </div>
    </div>

<div class="modal fade show" id="modal-metode-pembayaran" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pilih Metode Pembayaran</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-checkout">
            <div class="modal-body">

                    <input type="hidden" name="metode_pembayaran" id="selected-method">

                <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <div class="info-box mb-3 payment-method" data-method="transfer">
                                    <span class="info-box-icon bg-warning elevation-1"><i class="fas text-light fa-money-bill-wave"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Transfer</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box mb-3 payment-method" data-method="cod">
                                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-hand-holding-usd"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">COD</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                            </div>
                        </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Pesan</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('custom-script')

<script>



    function getSelectedItem() {

        let idPesananDipilih = [];

        $('.select-row:checked').each(function() {
            let idPesanan = $(this).data('id-pesanan');
            idPesananDipilih.push(idPesanan);
        });

        return idPesananDipilih;

    }

    $(document).ready(() => {

        let selectedProduk; // This will hold the product data fetched via AJAX

          $('.payment-method').on('click', function () {
            // Reset semua box ke style awal
            $('.payment-method').css({
                'border': 'none',
                'box-shadow': 'none',
                'transition': 'all 0.2s ease-in-out'
            });

            // Tambahkan style ke elemen yang dipilih
            $(this).css({
                'border': '2px solid #007bff',
                'box-shadow': '0 0 10px rgba(0, 123, 255, 0.5)',
                'transition': 'all 0.2s ease-in-out',
                'cursor': 'pointer'
            });

            // Ambil metode (opsional)
            const method = $(this).data('method');
            $('#selected-method').val(method); // jika pakai input hidden
        });

        $('#select-all').click(function() {
            $('.select-row').prop('checked', this.checked);
        });

        $('.select-row, #select-all').click(function() {

            if (!this.checked) {
                $('#select-all').prop('checked', false);
            }

            $('.select-row').each(function() {
                if(this.checked) {
                    $('.column-aksi').hide();
                    $('#btn-delete-pilihan-pesanan').show();
                    return false;
                } else {
                    $('.column-aksi').show();
                    $('#btn-delete-pilihan-pesanan').hide();
                }
            });

        });

        $('#form-update-pesanan').on('submit', function(e) {
            e.preventDefault();


            const idPesanan = $('#form-update-pesanan #id-pesanan').val();
            console.log(idPesanan);

            $.ajax({
                url: "{{ route('pesanan.update', ':id')}}".replace(':id', idPesanan),
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $(this).serialize(),
                success: function(data) {
                    if(data.success) {
                        showToast(data.message);
                    }
                },
                error: function(error) {
                    console.log(error);
                    // showToast('Hubungi administrator', icon='error', reload=false);
                }


            });


        });

        const updateTotalPrice = () => {
            if (!selectedProduk) return; // Ensure product data is loaded

            let quantity = parseInt($('#jumlah').val());
            let selectedUnit = $('#satuan').val();
            let unitPrice = 0;

            if (selectedUnit === selectedProduk.unit_besar) {
                unitPrice = selectedProduk.harga_jual;
            } else if (selectedUnit === selectedProduk.unit_kecil) {
                unitPrice = selectedProduk.harga_jual_unit_kecil;
            }

            let totalPrice = quantity * unitPrice;
            $('#total-harga-display').text(formatRupiah(totalPrice));
        };

        // Update the satuan text and recalculate total price dynamically when the unit changes
        $('#satuan').on('change', function() {
            $('#satuan-text').text($(this).val());
            updateTotalPrice(); // Recalculate price on unit change
        });

        // Listen for changes in the quantity input field
        $('#jumlah').on('change keyup', function() {
            // Ensure quantity is a valid number, default to 1 if not
            let quantity = parseInt($(this).val());
            if (isNaN(quantity) || quantity < 1) {
                $(this).val(1);
            }
            updateTotalPrice(); // Recalculate price on quantity change
        });

        $('.btn-tambah-pesanan').click(function() {

            const idPesanan = $(this).data('id-pesanan');

            let modalUpdatePesanan = $('#modal-update-pesanan');

            $.ajax({
                url: "{{ route('pesanan.show', ':id')}}".replace(':id', idPesanan),
                mehtod: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {

                    if(data.success) {

                        let pesanan = data.data;
                        selectedProduk = pesanan.produk;
                        const baseUrl = "{{ asset('storage/') }}";
                        $('#img-gambar-produk').attr('src', `${baseUrl}/${pesanan.produk.gambar}`);
                        modalUpdatePesanan.find('#id-pesanan').val(pesanan.id);
                        modalUpdatePesanan.find('#nama-produk').text(pesanan.produk.nama_produk);
                        modalUpdatePesanan.find('#harga-jual').text(formatRupiah(pesanan.produk.harga_jual));
                        modalUpdatePesanan.find('#deskripsi-produk').text(pesanan.produk.deskripsi);
                        modalUpdatePesanan.find('#jumlah').val(pesanan.jumlah);

                    // Set initial options and selected unit
                    modalUpdatePesanan.find('#unit-besar').attr('value', pesanan.produk.unit_besar).text(pesanan.produk.unit_besar);
                    modalUpdatePesanan.find('#unit-kecil').attr('value', pesanan.produk.unit_kecil).text(pesanan.produk.unit_kecil);
                    $('#satuan').val(pesanan.satuan ? pesanan.produk.unit_kecil : pesanan.produk.unit_besar); // Default to large unit
                    $('#satuan-text').text(pesanan.produk.unit_besar); // Update text display

                    updateTotalPrice();

                    }
                },
                error: function (error) {
                    // console.log(error);
                    showToast( 'Hubungi administrator', icon='danger', reload=false);
                }

            });

        });

    $('#btn-tambah-jumlah').click(function () {
        let jumlah = parseInt($('#jumlah').val())
        $('#jumlah').val(jumlah + 1);

            updateTotalPrice(); // Recalculate price on quantity change
    });

    $('#btn-kurang-jumlah').click(function () {
        let jumlah = parseInt($('#jumlah').val())
        if (jumlah > 1) {
            $('#jumlah').val(jumlah - 1);

            updateTotalPrice(); // Recalculate price on quantity change
        }
    });

        $('#btn-delete-pilihan-pesanan').click(function() {

            const ids = [];
            $('.select-row:checked').each(function() {
                ids.push($(this).data('id-pesanan'));
            });

            $.ajax({
                url: `{{ route('pesanan.destroy-many') }}`,
                method: 'POST',
                data: {
                    ids
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    showToast( data.message);
                },
                error: function (error) {
                    showToast( 'Pesanan gagal dihapus');
                }
            });


        });


        $('#btn-checkout').click(function() {
            $('#modal-metode-pembayaran').modal('hide');
        });

        $('#btn-show-modal-metode-pembayaran').click(function() {
            const idPesananDipilih = getSelectedItem();
            if(idPesananDipilih.length == 0) {

                showToast('warning', 'Silakan pilih barang yang ingin Anda checkout.')

            } else {
                $('#modal-metode-pembayaran').modal('show');
            }
        });

        $('#form-checkout').on('submit', function(e) {
            e.preventDefault();

            const data = $(this).serializeArray();
            data.push({ name: 'pesanan_dipilih', value: getSelectedItem()});

            $.ajax({
                url: '{{ route('transaksi.store') }}',
                method: 'POST',
                data: $.param(data),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if(data.success) {
                        showToast(data.message);
                    } else {
                        showToast(data.message, icon="warning", reload=false);
                    }
                },
                error: function (error) {
                    showToast( 'Gagal melakukan transaksi', icon='error', reload=false);
                }

            });
        });

        $('.btn-delete-pesanan').click(function() {
            const idPesanan = $(this).data('id-pesanan');

            $.ajax({
                url: `{{ route('pesanan.destroy', ':id') }}`.replace(':id', idPesanan),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    showToast( data.message);
                },
                error: function (error) {
                    showToast( 'Pesanan gagal dihapus');
                }
            });

        });

    });

</script>

@endsection
