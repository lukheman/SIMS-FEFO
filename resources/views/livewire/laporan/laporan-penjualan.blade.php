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
                        <label for="filter_type" class="form-label">Tipe Filter</label>
                        <select name="filter_type" class="form-select filter-type" required>
                            <option value="harian">Harian</option>
                            <option value="mingguan">Mingguan</option>
                            <option value="bulanan" selected>Bulanan</option>
                            <option value="tahunan">Tahunan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="periode" class="form-label">Periode</label>
                        <div class="input-container">
                            <input type="month" class="form-control input-periode" name="periode" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


    <div class="card-header">


        <button type="button" class="btn btn-primary" id="btn-cetak-laporan-penjualan" data-bs-toggle="modal"
            data-bs-target="#modal-cetak-laporan-penjualan">
            <i class="bi bi-printer"></i>
            Cetak Laporan Penjualan</button>

    </div>

    <div class="card-body">

        <livewire:table.penjualan-table />

    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterTypes = document.querySelectorAll('.filter-type');
    filterTypes.forEach(function(select) {
        select.addEventListener('change', function() {
            const container = this.closest('.modal-body').querySelector('.input-container');
            const val = this.value;
            let inputHtml = '';
            const today = new Date();
            const year = today.getFullYear();

            if (val === 'harian') {
                inputHtml = '<input type="date" class="form-control" name="periode" required>';
            } else if (val === 'mingguan') {
                inputHtml = '<input type="week" class="form-control" name="periode" required>';
            } else if (val === 'bulanan') {
                inputHtml = '<input type="month" class="form-control" name="periode" required>';
            } else if (val === 'tahunan') {
                inputHtml = '<input type="number" class="form-control" name="periode" min="2000" max="2100" step="1" value="'+year+'" required>';
            }
            container.innerHTML = inputHtml;
        });
    });
});
</script>
@endpush
