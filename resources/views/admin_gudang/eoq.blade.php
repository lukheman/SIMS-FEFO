@extends('layouts.main')

@section('title', 'Admin Gudang')

@section('sidebar-menu')

@include('admin_gudang.menu')

@endsection

@section('content')

<div class="card card-outline card-warning">
    <div class="card-header">
        <h3 class="card-title">Perhatian</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
        <!-- /.card-tools -->
    </div>
    <div class="card-body">
        Data dari dua bulan lalu akan dipakai buat hitung kebutuhan barang (EOQ), stok pengaman (Safety Stock), dan kapan harus restok (Reorder Point).
    </div>
</div>

<div class="card">
    <div class="card-header">
    </div>
    <div class="card-body">
        <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4">
            <div class="row">
                <div class="col-sm-12">
                    <table id="datatable" class="table table-bordered table-striped dataTable dtr-inline"
                        aria-describedby="datatable_info">
                        <thead>
                            <tr class="text-center">
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Safety Stock</th>
                                <th>ROP</th>
                                <th>Persediaan Saat Ini</th>
                                <th>Jumlah yang harus dipesan (EOQ)</th>
                                <th>Frekuensi Pemesanan</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($produk as $item)
                            <tr>
                                <td class="text-center">{{ $item->kode_produk }}</td>
                                <td>{{ $item->nama_produk }}</td>
                                <td class="text-center">{{ $item->safety_stock }}</td>
                                <td class="text-center">{{ $item->reorder_point }}</td>
                                <td class="text-center">{{ $item->label_persediaan }}</td>
                                <td class="text-center">{{ $item->economic_order_quantity }}</td>
                                <td class="text-center">{{ $item->frekuensi_pemesanan}}</td>
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

<!-- modal-hitung-eoq - modal untuk menampilkan form tambah data produk -->
<div class="modal fade show" id="modal-hitung-eoq" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Persediaan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="form-hitungeoq" method="post" action={{ route('admingudang.hitung') }}>
                @csrf
                <div class="modal-body">

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="periode-awal">Periode Awal</label>
                                <input type="month" name="periode_awal" id="periode-awal" class="form-control">
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <label for="periode-akhir">Periode Akhir</label>
                                <input type="month" name="periode_akhir" id="periode-akhir" class="form-control"
                                    readonly>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calculator"></i>
                        Hitung</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal-hitung-eoq -->

@endsection

@section('custom-script')

<script>

    function tambahSatuBulan(periode) {
        let awal = new Date(periode + "-01");
        awal.setMonth(awal.getMonth() + 1);

        let tahun = awal.getFullYear();
        let bulan = String(awal.getMonth() + 1).padStart(2, '0');

        return `${tahun}-${bulan}`;
    }

    $(document).ready(function () {

        $('#btn-hitung-eoq').click(() => {

            $.ajax({
                url: '{{ route('produk.index') }}',
                method: 'GET',
                success: function (data) {
                    data.data.forEach((item) => {
                        $('#nama-produk').append(new Option(`${item.kode_produk} | ${item.nama_produk}`, item.id))
                    });
                },
                error: function (error) {
                    console.log(error)
                },
            })

        });

        let prevPeriodeAwal = $('#periode-awal').val();
        $('#periode-awal').change(() => {
            let periodeAkhir = tambahSatuBulan($('#periode-awal').val());

            // cek apakah periode akhir sama dengan bulan ini
            let today = new Date();
            let currentMonth = String(today.getMonth() + 1).padStart(2, '0');
            let currentYear = today.getFullYear();
            let currentPeriod = `${currentYear}-${currentMonth}`;

            if (periodeAkhir === currentPeriod) {
                alert('Periode awal harus 2 bulan sebelum bulan ini');
                $('#periode-awal').val(prevPeriodeAwal);
                return; // Hentikan eksekusi jika validasi gagal
            }

            $('#periode-akhir').attr('min', periodeAkhir).val(periodeAkhir);
            prevPeriodeAwal = $('#periode-awal').val();

        });

        let today = new Date();
        today.setMonth(today.getMonth() - 1);
        let month = String(today.getMonth() + 1).padStart(2, '0'); // Menambahkan nol di depan jika perlu
        let year = today.getFullYear();
        let maxDate = `${year}-${month}`;

        $('#periode-awal').attr('max', maxDate);


    });



</script>
@endsection
