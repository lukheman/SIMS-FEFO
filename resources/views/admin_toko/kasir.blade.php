@extends('layouts.main')

@section('title', 'Admin Toko')

@section('sidebar-menu')
    @include('admin_toko.menu')
@endsection

@section('content')
<div class="row">
    <!-- Bagian Scanner -->
    <div class="col-12 col-md-7 mb-3 mb-md-0">
        <div id="scanner" class="border rounded p-3 bg-light" style="min-height: 300px;"></div>
    </div>

    <!-- Bagian Transaksi -->
    <div class="col-12 col-md-5">
        <div class="row">
            <!-- Total Harga -->
            <div class="col-12 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">Total Harga</h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="form-group mb-0">
                            <input type="text" value="Rp. 0" class="form-control form-control-lg text-right font-weight-bold" readonly id="total-harga">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Pembelian -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">Daftar Pembelian</h5>
                    </div>
                    <div class="card-body pt-3">
                        <form id="form-daftar-pesanan"></form>
                        <button class="btn btn-primary btn-block mt-3" id="btn-simpan-transaksi">
                            <i class="fas fa-save mr-1"></i> Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-script')
<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
let pesanan = {};

function formatRupiah(angka) {
    return 'Rp. ' + Number(angka).toLocaleString('id-ID');
}

function startScanner() {
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
}

function tambahDaftarPesanan(produk) {
    $('#form-daftar-pesanan').append(`
        <div class="form-group" id="item-${produk.kode_produk}">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <input type="text" class="form-control form-control-sm" value="${produk.nama_produk}" readonly>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control text-right harga-jual" id="harga-${produk.kode_produk}" value="${produk.harga_jual_unit_kecil}" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text" id="satuan">/ ${produk.unit_kecil}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control text-center jumlah" min="1" id="jumlah-${produk.kode_produk}" value="1" title="Jumlah dalam satuan yang dipilih">
                    </div>
                </div>
                <div class="col-md-6 mb-2">
                    <select class="form-control form-control-sm satuan" name="satuan-${produk.kode_produk}" id="satuan-${produk.kode_produk}">
                        <option value="${produk.unit_kecil}" data-harga="${produk.harga_jual_unit_kecil}">${produk.unit_kecil}</option>
                        <option value="${produk.unit_besar}" data-harga="${produk.harga_jual}">${produk.unit_besar}</option>
                    </select>
                </div>
            </div>
        </div>
    `);
}

function updateTotalHarga() {
    let totalHarga = 0;
    for (let barcode in pesanan) {
        totalHarga += pesanan[barcode].jumlah * pesanan[barcode].harga_jual;
    }
    $('#total-harga').val(formatRupiah(totalHarga));
}

function onScanSuccess(result) {
    Quagga.stop();
    let barcode = result.codeResult.code;

    if (barcode in pesanan) {
        pesanan[barcode].jumlah += 1;
        $(`#jumlah-${barcode}`).val(pesanan[barcode].jumlah);
        updateTotalHarga();
    } else {
        $.ajax({
            url: "{{ route('produk.kode-produk', ':barcode') }}".replace(':barcode', barcode),
            method: 'GET',
            success: (data) => {
                if (data.success) {
                    showToast(`${data.data.kode_produk} - ${data.data.nama_produk}`, 'success', false);
                    pesanan[barcode] = {
                        jumlah: 1,
                        harga_jual: data.data.harga_jual_unit_kecil,
                        satuan: data.data.unit_kecil,
                        produk: data.data
                    };
                    tambahDaftarPesanan(data.data);
                    updateTotalHarga();
                } else {
                    showToast(data.message, 'warning', false);
                }
                setTimeout(startScanner, 500);
            },
            error: (err) => {
                console.error(err);
                setTimeout(startScanner, 500);
            }
        });
    }
}

$(document).ready(function() {
    $('#total-harga').val('Rp. 0');
    startScanner();
    Quagga.onDetected(onScanSuccess);

    $('#btn-simpan-transaksi').click(function() {
        $.ajax({
            url: "{{ route('admintoko.transaksi') }}",
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { pesanan },
            success: (data) => {
                showToast(data.message, data.success ? 'success' : 'error', false);
            },
            error: (err) => {
                console.error(err);
                showToast('Gagal menyimpan transaksi', 'error', false);
            }
        });
    });

    $(document).on('change', '.jumlah', function() {
        const barcode = $(this).attr('id').replace('jumlah-', '');
        pesanan[barcode].jumlah = parseInt($(this).val()) || 1;
        updateTotalHarga();
    });

    $(document).on('change', '.satuan', function() {
        const barcode = $(this).attr('id').replace('satuan-', '');
        const selectedOption = $(this).find('option:selected');
        const harga = parseFloat(selectedOption.data('harga')) || 0;
        pesanan[barcode].harga_jual = harga;
        pesanan[barcode].satuan = $(this).val();
        $(`#harga-${barcode}`).val(harga);
        $('#satuan').text('/ ' + $(this).val());
        updateTotalHarga();
    });
});
</script>
@endsection
