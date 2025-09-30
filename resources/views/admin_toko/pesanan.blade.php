@extends('layouts.main')

@section('title', 'Admin Toko')

@section('sidebar-menu')

@include('admin_toko.menu')

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
                            <tr>
                                <th>Tanggal </th>
                                <th>Pemesan </th>
                                <th>Status Transaksi</th>
                                <th>Metode Pembayaran</th>
                                <th>Status Pembayaran</th>
                                <th>Ubah Status Pembayaran</th>
                                <th>Pilih Kurir</th>
                                <th>Bukti Pembayaran</th>
                                <th>Nota</th>
                                <th>Info</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($pesanan as $item)
                            <tr>
                                <td> {{ $item->tanggal }}</td>
                                <td> {{ $item->user->name }}</td>
                                <td>
                                    <x-status-transaksi :status="$item->status" />
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $item->metode_pembayaran }}</span>

                                </td>
                                <td>
                                    <x-status-pembayaran :status="$item->status_pembayaran" />
                                </td>
                                <td>

                                    <button class="btn btn-sm btn-success btn-status-pembayaran-lunas" data-id-transaksi="{{ $item->id }}" {{ $item->metode_pembayaran === \App\Constants\MetodePembayaran::COD ? 'disabled' : ''}} >
                                        <i class="fas fa-money-check"></i> Lunas</button>
                                </td>

                                <td>


                                    <form>
                                        <select data-id-transaksi="{{ $item->id }}" class="form-control pilih-kurir"
                                            {{ $item->status === \App\Constants\StatusTransaksi::DIPROSES || $item->status === \App\Constants\StatusTransaksi::DIKIRIM ? '' : 'disabled'  }}>
                                            @if (!$item->id_kurir)
                                                <option value="">Pilih Kurir</option>
                                            @endif
                                            @foreach ($kurir as $itemKurir)
                                            <option value="{{ $itemKurir->id }}" {{ $item->id_kurir === $itemKurir->id ? 'selected' : ''}}>{{ $itemKurir->name }}</option>
                                            @endforeach
                                        </select>
                                    </form>


                                </td>

                                <td>

                                    @if ($item->metode_pembayaran === \App\Constants\MetodePembayaran::TRANSFER)

                                        @if (isset($item->bukti_pembayaran))

                                            <button type="button" class="btn btn-sm btn-primary btn-lihat-bukti-pembayaran" data-id-transaksi="{{ $item->id }}" data-bukti-pembayaran="{{ $item->bukti_pembayaran }}" data-toggle="modal" data-target="#modal-lihat-bukti-pembayaran">
                                                <i class="fa fa-eye"></i> Lihat Bukti Pembayaran </button>
                                        @else

                                            <button type="button" class="btn btn-sm btn-primary btn-lihat-bukti-pembayaran disabled">
                                            <i class="fa fa-eye"></i> Bukti Pembayaran Belum Ada </button>
                                        @endif
                                    @endif

                                </td>

                                <td>

                                    <form action="{{ route('admintoko.nota') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id_transaksi" value="{{ $item->id }}">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="nav-icon fas fa-print"></i>
                                        Cetak</button>
                                    </form>

                                </td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-secondary btn-detail-transaksi"
                                        data-id-transaksi="{{ $item->id }}" data-toggle="modal"
                                        data-target="#modal-detail-transaksi">
                                        <i class="nav-icon fas fa-info"></i>
                                        Info</button>
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

<!-- modal-lihat-bukti-pembayaran -->
<div class="modal fade show" id="modal-lihat-bukti-pembayaran" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bukti Pembayaran</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body text-center">

                <img src="" alt="" id="img-bukti-pembayaran" class="img-fluid">

            </div>
            <div class="modal-footer justify-content-between">
            </div>
        </div>
    </div>
</div>


<!-- modal-update-persediaan - modal untuk menampilkan form tambah data produk -->
<div class="modal fade show" id="modal-detail-transaksi" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Pesanan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
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
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- end modal-update-persediaan -->
@endsection

@section('custom-script')

<script>

    $(document).ready(() => {
        function updateTransactionStatus(idTransaksi, status, successMessage) {
            $.ajax({
                url: `{{ route('transaksi.update', '')}}/${idTransaksi}`,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {'status': status},
                success: function (data) {
                    showToast(data.success ? successMessage : data.message, icon = data.success ? 'success' : 'warning');
                },
                error: function (error) {
                    console.log(error);
                    Swal.fire({
                        title: 'Terjadi kesalahan',
                        icon: 'error',
                        text: 'Silakan coba lagi atau hubungi administrator.',
                    });
                }
            });
        }

        function updateStatusPembayaran(idTransaksi, statusPembayaran, successMessage) {
            $.ajax({
                url: `{{ route('transaksi.update-status-pembayaran', ':id')}}`.replace(':id', idTransaksi),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {'status_pembayaran': statusPembayaran},
                success: function (data) {
                    console.log(data);
                    showToast(data.success ? successMessage : 'Gagal mengubah status pembayaran', icon = data.success ? 'success' : 'error');
                },
                error: function (error) {
                    console.log(error);
                    Swal.fire({
                        title: 'Terjadi kesalahan',
                        icon: 'error',
                        text: 'Silakan coba lagi atau hubungi administrator.',
                    });
                }
            });
        }

        $('.btn-status-pembayaran-lunas').click(function () {
            let idTransaksi = $(this).data('id-transaksi');
            updateStatusPembayaran(idTransaksi, '{{ \App\Constants\StatusPembayaran::LUNAS }}', 'Status pembayaran berhasil diubah menjadi lunas');
        });


        $('.btn-setujui-pesanan').click(function () {
            let idTransaksi = $(this).data('id-transaksi');
            updateTransactionStatus(idTransaksi, 'diproses', 'Pesanan berhasil disetujui');
        });

        $('.btn-kirim-pesanan').click(function () {
            let idTransaksi = $(this).data('id-transaksi');
            updateTransactionStatus(idTransaksi, 'dikirim', 'Pesanan diserahkan ke kurir');
        });

        $('.btn-tolak-pesanan').click(function () {
            let idTransaksi = $(this).data('id-transaksi');
            updateTransactionStatus(idTransaksi, 'ditolak', 'Pesanan berhasil ditolak');
        });

        $('.btn-detail-transaksi').on('click', function () {

            let idTransaksi = $(this).data('id-transaksi');

            $.ajax({
                url: '{{route('transaksi.detail')}}',
                method: 'POST',
                data: {
                    'id_transaksi': idTransaksi
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.success) {

                        $("#table-detail-transaksi tbody").empty();
                        data.data.forEach((item, index) => {

                            const isPending = data.data.status === '{{ \App\Constants\StatusTransaksi::PENDING }}';
                            const rowClass = isPending && !item.cukup ? 'text-danger' : '';

                            let newRow = `
                                <tr class="${rowClass}">
                                    <td>${index + 1}</td>
                                    <td>${item.produk.nama_produk}</td>
                                    <td>${item.label_jumlah_pesanan}</td>
                                    <td>${item.label_harga_satuan}</td>
                                    <td>${item.label_total_harga_jual}</td>
                                </tr>`;

                            $("#table-detail-transaksi tbody").append(newRow);
                        });
                    }
                },
                error: function (error) {
                    console.log(error);
                },
            });

        });

        $('.btn-cetak-nota').click(function () {
            let idTransaksi = $(this).data('id-transaksi');

            $.ajax({
                url: '{{route('admintoko.nota')}}',
                method: 'POST',
                data: {
                    'id_transaksi': idTransaksi
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    console.log(data);
                },
                error: function (error) {
                    console.log(error);
                },
            });

        });

        $('.btn-lihat-bukti-pembayaran').click(function() {

            const idTransaksi = $(this).data('id-transaksi');
            $('#id-transaksi').val(idTransaksi);

            const buktiPembayaran = $(this).data('bukti-pembayaran');
            const baseUrl = "{{ asset('storage') }}";
            $('#img-bukti-pembayaran').attr('src', `${baseUrl}/${buktiPembayaran}`);

        });


    });
</script>

<script>

$(document).ready(function() {

    $('.pilih-kurir').change(function() {

        const idTransaksi = $(this).data('id-transaksi');

        if($(this).val() !== '') {

            $.ajax({
                url: "{{ route('transaksi.update-kurir', ':id')}}".replace(':id', idTransaksi),
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { 'id_kurir': $(this).val() },
                success: function (data) {
                    showToast(data.success ? data.message : 'Pesanan gagal diserahkan kepada kurir', icon = data.success ? 'success' : 'warning');
                },
                error: function (error) {
                    console.log(error);
                    Swal.fire({
                        title: 'Terjadi kesalahan',
                        icon: 'error',
                        text: 'Silakan coba lagi atau hubungi administrator.',
                    });
                }
            });

        }

    });

});

</script>
@endsection
