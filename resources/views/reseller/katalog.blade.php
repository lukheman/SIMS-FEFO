@extends('layouts.main')

@section('title', 'Toko Kecil')

@section('sidebar-menu')
@include('reseller.menu')
@endsection

@section('content')

<div class="row mb-3">

    <div class="col-12">
        <form action="{{ route('reseller.katalog')}}" method="get">

            <div class="input-group">
                <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Cari produk">
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">Cari</button>
                </span>
            </div>
        </form>
    </div>


</div>

<div class="row">

    @foreach ($produk as $item)
    <div class="col-12 col-md-3 mb-4">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-body p-2">
                <div class="position-relative rounded" style="height: 180px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; overflow: hidden;">
                    <img src="{{ asset('storage/' . $item->gambar) }}"
                         class="img-fluid product-image"
                         alt="{{ $item->nama_produk }}"
                         style="max-height: 100%; object-fit: contain;">

                    @if ($item->persediaan->jumlah === 0)
                    <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center"
                         style="background: rgba(0,0,0,0.5);">
                        <span class="text-white font-weight-bold" style="font-size: 1.5rem;">Kosong</span>
                    </div>
                    @endif
                </div>

                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-truncate" style="max-width: 65%;">{{ $item->nama_produk }}</strong>
                        <span class="badge badge-info">{{ $item->label_persediaan}}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-muted">Rp. {{ number_format($item->harga_jual, 2, ',', '.') }}</small>
                        <small class="text-muted text-right">Exp: {{ $item->exp }}</small>
                    </div>

                    <div class="text-right">
                        <button type="button"
                                class="btn btn-sm btn-primary btn-tambah-pesanan"
                                data-toggle="modal"
                                data-target="#modal-tambah-pesanan"
                                data-id-produk="{{ $item->id }}"
                                data-gambar-produk="{{ $item->gambar }}"
                                {{ $item->persediaan->jumlah == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-cart-plus"></i> Pesan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="modal fade show" id="modal-tambah-pesanan" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pemesanan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-tambah-pesanan">
                @csrf

                <input type="hidden" id="id-produk" name="id_produk">


                <div class="modal-body">
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
                                <label for="satuan">Satuan</label>
                                <select name="satuan" id="satuan" class="form-control form-control-sm" style="width: 140px;">
                                    <option id="unit-besar" selected></option>
                                    <option id="unit-kecil"></option>
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
                        <i class="nav-icon fas fa-cart-plus"></i> Tambah Ke Keranjang
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>

@endsection

@section('custom-script')

<script>
    function formatRupiah(angka) {
        let number_string = angka.toString(),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return 'Rp. ' + rupiah;
    }

    $(document).ready(() => {

        let selectedProduk; // This will hold the product data fetched via AJAX

        // Function to update the displayed price based on quantity and selected unit
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


        $('#form-tambah-pesanan').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('pesanan.store') }}",
                method: "POST",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success) {
                        showToast(data.message);
                    } else {
                        showToast(data.message, icon = 'warning');
                    }
                    $('#modal-tambah-pesanan').modal('hide');
                },
                error: function(error) {
                    showToast('Gagal melakukan pembelian', icon = 'error', reload = false);
                }
            });
        });

        $('.btn-tambah-pesanan').click(function() {

            let modalTambahPesanan = $('#modal-tambah-pesanan');
            let idProduk = $(this).data('id-produk');
            let gambarProduk = $(this).data('gambar-produk');

            const baseUrl = "{{ asset('storage') }}";
            $('#img-gambar-produk').attr('src', `${baseUrl}/${gambarProduk}`);

            // Reset quantity to 1 when modal is opened for a new product
            $('#jumlah').val(1);

            $.ajax({
                url: `{{ route('produk.show', '') }}/${idProduk}`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    let produk = data.data;

                    selectedProduk = produk; // Store fetched product data globally

                    modalTambahPesanan.find('#id-produk').val(produk.id);
                    modalTambahPesanan.find('#nama-produk').text(produk.nama_produk);
                    modalTambahPesanan.find('#deskripsi-produk').text(produk.deskripsi);

                    // Set initial options and selected unit
                    modalTambahPesanan.find('#unit-besar').attr('value', produk.unit_besar).text(produk.unit_besar);
                    modalTambahPesanan.find('#unit-kecil').attr('value', produk.unit_kecil).text(produk.unit_kecil);
                    $('#satuan').val(produk.unit_besar); // Default to large unit
                    $('#satuan-text').text(produk.unit_besar); // Update text display

                    // Call updateTotalPrice to set the initial price when product data is loaded
                    updateTotalPrice();

                },
                error: function(error) {
                    Swal.fire({
                        title: 'Gagal mengambil data produk', // Updated error message
                        icon: "error",
                    }).then(() => window.location.reload());
                }
            });

        });

        $('#btn-tambah-jumlah').click(function() {
            let jumlah = parseInt($('#jumlah').val());
            $('#jumlah').val(jumlah + 1);
            updateTotalPrice(); // Recalculate price on quantity change
        });

        $('#btn-kurang-jumlah').click(function() {
            let jumlah = parseInt($('#jumlah').val());
            if (jumlah > 1) {
                $('#jumlah').val(jumlah - 1);
                updateTotalPrice(); // Recalculate price on quantity change
            }
        });

    });
</script>
@endsection
