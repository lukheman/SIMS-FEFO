<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#modal-cetak-laporan-reseller">
                <i class="bi bi-printer"></i>
                Cetak Laporan Penjualan Reseller
            </button>
        </div>
        <div class="w-25">
            <select wire:model.live="selectedReseller" class="form-select form-select-sm">
                <option value="">-- Semua Reseller --</option>
                @foreach($this->resellerList as $reseller)
                    <option value="{{ $reseller->id }}">{{ $reseller->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped" role="table">
            <thead>
                <tr>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Reseller</th>
                    <th scope="col">Total Harga</th>
                    <th scope="col">Status Pembayaran</th>
                    <th scope="col">Status Pesanan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($this->transaksiList as $transaksi)
                <tr>
                    <td>{{ $transaksi->tanggal ? $transaksi->tanggal->format('d/m/Y') : '-' }}</td>
                    <td>{{ $transaksi->user->name ?? '-' }}</td>
                    <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $transaksi->status_pembayaran->value == 'lunas' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($transaksi->status_pembayaran->value) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $transaksi->status->value == 'selesai' ? 'bg-success' : 'bg-primary' }}">
                            {{ ucfirst($transaksi->status->value) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data penjualan reseller.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($this->transaksiList->hasPages())
    <div class="card-footer">
        {{ $this->transaksiList->links() }}
    </div>
    @endif

    <!-- Modal Cetak Laporan Penjualan Reseller -->
    <div class="modal fade" id="modal-cetak-laporan-reseller" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cetak Laporan Penjualan Reseller</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <form action="{{ route('laporan.laporan-penjualan-reseller') }}" method="post">
                    @csrf
                    <input type="hidden" name="ttd" value="Pemilik Toko">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="id_reseller" class="form-label">Pilih Reseller</label>
                            <select name="id_reseller" class="form-select" required>
                                <option value="semua">Semua Reseller</option>
                                @foreach($this->resellerList as $reseller)
                                    <option value="{{ $reseller->id }}">{{ $reseller->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="filter_type_reseller" class="form-label">Tipe Filter</label>
                            <select name="filter_type" class="form-select filter-type-reseller" required>
                                <option value="harian">Harian</option>
                                <option value="mingguan">Mingguan</option>
                                <option value="bulanan" selected>Bulanan</option>
                                <option value="tahunan">Tahunan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="periode" class="form-label">Periode</label>
                            <div class="input-container-reseller">
                                <input type="month" class="form-control" name="periode" required>
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
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterTypesReseller = document.querySelectorAll('.filter-type-reseller');
    filterTypesReseller.forEach(function(select) {
        select.addEventListener('change', function() {
            const container = this.closest('.modal-body').querySelector('.input-container-reseller');
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
