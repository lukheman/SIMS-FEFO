<x-layout>

<div class="row">
    <div class="col-8">
        <div id="reader"></div>
    </div>
    <div class="col-4" id="info-pesanan">
        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Pesan</label>
            <input type="text" id="tanggal" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <label for="nama-pemesan" class="form-label">Nama Pemesan</label>
            <input type="text" id="nama-pemesan" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea id="alamat" class="form-control" readonly></textarea>
        </div>
        <div class="mb-3">
            <label for="total-harga" class="form-label">Total Harga</label>
            <input type="text" id="total-harga" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <button class="btn btn-sm btn-secondary w-100" id="btn-detail-transaksi" data-bs-toggle="modal" data-bs-target="#modal-detail-transaksi">
                <i class="fas fa-info-circle"></i> Detail Pesanan
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-detail-transaksi" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Pesanan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="table-detail-transaksi">
                    <thead>
                        <tr>
                            <th style="width: 10px">No</th>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between"></div>
        </div>
    </div>
</div>
</x-layout>

@push('scripts')

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    const scanner = new Html5Qrcode("reader");

    function onScanSuccess(content) {
        scanner.stop();

        $.ajax({
            url: `{{ route('kurir.konfirmasi-pembayaran', ':id') }}`.replace(':id', content),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { status_pembayaran: '{{ \App\Enums\StatusPembayaran::LUNAS }}' },
            success: function (data) {
                let infoPesanan = $('#info-pesanan');

                if (data.success) {
                    showToast(data.message, icon='success', reload=false);

                    let transaksi = data.data;

                    infoPesanan.find('#tanggal').val(transaksi.tanggal);
                    infoPesanan.find('#nama-pemesan').val(transaksi.user.name);
                    infoPesanan.find('#alamat').val(transaksi.user.alamat);
                    infoPesanan.find('#total-harga').val(formatRupiah(transaksi.total_harga));
                    infoPesanan.find('#btn-detail-transaksi').attr('data-id-transaksi', transaksi.id);
                } else {
                    showToast(data.message, icon='warning', reload=false);
                }

                setTimeout(() => {
                    scanner.start({ facingMode: "environment" }, { fps: 10, qrbox: 450 }, onScanSuccess);
                }, 3000);
            },
            error: function (error) {
                console.error(error);
                Swal.fire({
                    title: 'Terjadi kesalahan',
                    icon: 'error',
                    text: 'Silakan coba lagi atau hubungi administrator.',
                });

                scanner.start({ facingMode: "environment" }, { fps: 10, qrbox: 450 }, onScanSuccess);
            }
        });
    }

    $(document).ready(function () {
        Html5Qrcode.getCameras().then(cameras => {
            if (cameras && cameras.length) {
                scanner.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: 450 },
                    onScanSuccess
                );
            } else {
                alert("Kamera tidak ditemukan");
            }
        }).catch(err => {
            console.error("Camera error:", err);
        });

        $('#btn-detail-transaksi').click(function () {
            let idTransaksi = $(this).data('id-transaksi');

            $.ajax({
                url: '{{ route('transaksi.detail') }}',
                method: 'POST',
                data: { 'id_transaksi': idTransaksi },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.success) {
                        $("#table-detail-transaksi tbody").empty();
                        data.data.forEach((item, index) => {
                            let newRow = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.produk.nama_produk}</td>
                                    <td>${item.jumlah}</td>
                                    <td>${item.label_harga_satuan}</td>
                                    <td>${item.label_total_harga_jual}</td>
                                </tr>`;
                            $("#table-detail-transaksi tbody").append(newRow);
                        });
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });
    });

</script>

@endpush
