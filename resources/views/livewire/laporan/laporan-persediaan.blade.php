<div class="card">

    <div class="card-header">

<a href="{{ route('laporan.laporan-persediaan-produk')}}" class="btn btn-primary" id="btn-cetak-laporan-pesanan" >
    <i class="bi bi-printer"></i>
    Cetak Laporan Persediaan
</a>
    </div>

    <div class="card-body">
        <livewire:table.persediaan-table />
    </div>

</div>
