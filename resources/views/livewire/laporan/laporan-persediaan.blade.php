<div class="card">

    <div class="card-header">

<a href="{{ route('laporan.laporan-persediaan-produk')}}" class="btn btn-sm btn-outline-danger" id="btn-cetak-laporan-pesanan" >
    <i class="bi bi-printer"></i>
    Cetak Laporan Persediaan Produk
</a>
    </div>

    <div class="card-body">
        <livewire:table.persediaan-table />
    </div>

</div>
