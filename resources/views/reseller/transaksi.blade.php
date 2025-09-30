@extends('layouts.main')

@section('title', 'Toko Kecil')

@section('sidebar-menu')
@include('reseller.menu')
@endsection

@section('content')
<div class="card">
    <div class="card-header"></div>
    <div class="card-body">
        <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12 col-md-6">

                    <a href="{{ route('reseller.transaksi', ['belumbayar' => 0] )}}"  class="btn btn-sm btn-outline-primary {{ request()->has('belumbayar') || count(request()->all()) === 0 ? 'active' : ''}}" id="btn-belum-bayar">Belum Bayar</a>
                    <a href="{{ route('reseller.transaksi', ['pending' => 1] )}}"     class="btn btn-sm btn-outline-primary {{ request()->has('pending') ? 'active' : ''}}" id="btn-pending">Pending</a>
                    <a href="{{ route('reseller.transaksi', ['diproses' => 1]) }}"    class="btn btn-sm btn-outline-primary {{ request()->has('diproses') ? 'active' : ''}}" id="btn-diproses">Diproses</a>
                    <a href="{{ route('reseller.transaksi', ['dikirim' => 1])}}"      class="btn btn-sm btn-outline-primary {{ request()->has('dikirim') ? 'active' : ''}}" id="btn-dikirim">Dikirim</a>
                    <a href="{{ route('reseller.transaksi', ['selesai' => 1])}}"      class="btn btn-sm btn-outline-primary {{ request()->has('selesai') ? 'active' : ''}}" id="btn-selesai">Selesai</a>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <!-- TODO: buat menjadi lebih estetik -->
                    <table id="datatable" class="table table-bordered table-striped dataTable dtr-inline">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Status Transaksi</th>
                                <th>Metode Pembayaran</th>
                                <th>Status Pembayaran</th>
                                <th>Bukti Pembayaran</th>
                                <th>Konfirmasi</th>

                                <th>Info</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($transaksi as $item)
                            <tr>
                                <td> {{ $item->tanggal }}</td>


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
                                    @if ($item->metode_pembayaran === \App\Constants\MetodePembayaran::TRANSFER)

                                        @if (isset($item->bukti_pembayaran))

                                            <button type="button" class="btn btn-sm btn-outline-primary btn-lihat-bukti-pembayaran" data-id-transaksi="{{ $item->id }}" data-bukti-pembayaran="{{ $item->bukti_pembayaran }}" data-toggle="modal" data-target="#modal-lihat-bukti-pembayaran">
                                                <i class="fa fa-eye"></i> Lihat Bukti Pembayaran
                                            </button>

                                        @else

                                            <button type="button" class="btn btn-sm btn-outline-primary btn-kirim-bukti-pembayaran"
                                                data-id-transaksi="{{ $item->id }}" data-toggle="modal" data-target="#modal-kirim-bukti-pembayaran"
                                                {{ $item->status === \App\Constants\StatusTransaksi::SELESAI ? 'disabled' : '' }}>
                                            <i class="fa fa-paper-plane"></i> Kirim Bukti Pembayaran
                                            </button>

                                        @endif
                                    @endif
                                </td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-success btn-pesanan-selesai"
                                        data-id-transaksi="{{ $item->id }}" {{ $item->status === \App\Constants\StatusTransaksi::DITERIMA ? '' : 'disabled' }}>
                                        <i class="fas fa-check"></i>Pesanan selesai</button>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-detail-transaksi"
                                        data-id-transaksi="{{ $item->id }}" data-toggle="modal"
                                        data-target="#modal-detail-transaksi">
                                        <i class="fas fa-info"></i>Detail Pesanan</button>
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
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btn-ubah-bukti-pembayara" data-toggle="modal" data-target="#modal-kirim-bukti-pembayaran"> <i class="fas fa-send"></i> Ubah </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade show" id="modal-kirim-bukti-pembayaran" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Kirim Bukti Pembayaran</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-bukti-pembayaran">
            <div class="modal-body">

                <input type="hidden" id="id-transaksi" disabled>

                <div class="form-group">
                    <label for="bukti-pembayaran">Bukti Pembayaran</label>
                    <input type="file" class="form-control" name="bukti_pembayaran" id="bukti-pembayaran">
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary"> <i class="fas fa-send"></i> Kirim </button> </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<div class="modal fade show" id="modal-detail-transaksi" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detail Transaksi</h4>
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

@endsection

@section('custom-script')

<script>

    $(document).ready(() => {

        $('.btn-lihat-bukti-pembayaran').click(function() {

            const idTransaksi = $(this).data('id-transaksi');
            $('#id-transaksi').val(idTransaksi);

            const buktiPembayaran = $(this).data('bukti-pembayaran');
            const baseUrl = "{{ asset('storage') }}";
            $('#img-bukti-pembayaran').attr('src', `${baseUrl}/${buktiPembayaran}`);

        });

        $('.btn-kirim-bukti-pembayaran').click(function() {
            const idTransaksi = $(this).data('id-transaksi');

            $('#id-transaksi').val(idTransaksi);

        });

        $('#form-bukti-pembayaran').on('submit', function(e) {

            e.preventDefault();

            let idProduk = $('#id-transaksi').val();
            let formData = new FormData(this);

            $.ajax({
                url: `{{ route('transaksi.bukti-pembayaran', ':id') }}`.replace(':id', idProduk),
                method: 'POST',
                data: formData,
                processData: false,  // penting untuk FormData
                contentType: false,  // penting untuk FormData
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(resp) {
                    if(resp.success) {
                        showToast( resp.message);
                        $('#modal-kirim-bukti-pembayaran').modal('hide');
                        $('#modal-lihat-bukti-pembayaran').modal('hide');
                    }

                },
                error: function(xhr) {
                    console.log(xhr);
                }
            })


        });


        $('.btn-pesanan-selesai').click(function () {
            let idTransaksi = $(this).data('id-transaksi');

            $.ajax({
                url: `{{ route('transaksi.update', ':id')}}`.replace(':id', idTransaksi),
                method: 'PUT',
                data: {
                    status: '{{ \App\Constants\StatusTransaksi::DITERIMA }}'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    showToast(data.message);
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

                        console.log(data);

                        $("#table-detail-transaksi tbody").empty();
                        data.data.forEach((item, index) => {
                            console.log(item)
                            let newRow = `
                                <tr>
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

    });

</script>

@endsection
