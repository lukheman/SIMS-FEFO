<div class="card">
    <div class="card-header">
        <button class="btn btn-outline-danger" id="btn-cetak-laporan-penjualan" data-bs-toggle="modal"
            data-bs-target="#modal-cetak-laporan-penjualan">
            <i class="fas fa-print"></i>
            Cetak Laporan Penjualan</button>
    </div>
    <div class="card-body">
        <table id="datatable" class="table table-bordered table-striped">
            <thead>
                <tr class="text-center">
                    <th>Tanggal </th>
                    <th>Nama Produk </th>
                    <th>Jumlah Terjual</th>
                    <th>Harga Produk</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penjualan as $item)
                <tr>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->produk->nama_produk }}</td>
                    <td class="text-end">{{ $item->label_jumlah_unit_terjual }}</td>
                    <td class="text-end">{{ $item->label_harga_jual }}</td>
                    <td class="text-end">{{ $item->label_total_harga_jual }}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-danger btn-delete-penjualan"
                            data-id-penjualan="{{ $item->id }}">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Cetak Laporan Penjualan -->
<div class="modal fade" id="modal-cetak-laporan-penjualan" tabindex="-1" aria-labelledby="modalCetakLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCetakLabel">Periode Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form action="{{ route('laporan.laporan-penjualan') }}" method="post">
                @csrf
                <input type="hidden" name="ttd" value="{{ $ttd }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="periode" class="form-label">Periode</label>
                        <input type="month" class="form-control" name="periode" id="periode">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(() => {
    $(document).on('click', '.btn-delete-penjualan', function () {
        let idPenjualan = $(this).data('id-penjualan');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Log penjualan akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('mutasi.destroy', ':id') }}".replace(':id', idPenjualan),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        showToast(data.message);
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Log penjualan gagal dihapus',
                            icon: "error",
                        }).then(() => window.location.reload());
                    }
                });
            }
        });
    });
});
</script>
@endpush
