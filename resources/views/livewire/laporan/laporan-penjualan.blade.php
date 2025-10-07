<div class="card">

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
                <input type="hidden" name="ttd" value="Kasir">
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


    <div class="card-header">


        <button type="button" class="btn btn-sm btn-outline-danger" id="btn-cetak-laporan-penjualan" data-bs-toggle="modal"
            data-bs-target="#modal-cetak-laporan-penjualan">
            <i class="bi bi-printer"></i>
            Cetak Laporan Penjualan</button>

    </div>

    <div class="card-body">

        <livewire:table.penjualan-table />

    </div>

</div>
