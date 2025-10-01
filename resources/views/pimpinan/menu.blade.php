<x-nav-link href="{{ route('pimpinan.dashboard') }}" icon="bi-speedometer2">
    Dashboard
</x-nav-link>

<li class="nav-header">LAPORAN</li>

<x-nav-link href="{{ route('pimpinan.laporan-penjualan') }}" icon="bi-clipboard-data">
    Penjualan
</x-nav-link>

<x-nav-link href="{{ route('pimpinan.laporan-persediaan-produk') }}" icon="bi-box-seam">
    Persediaan Produk
</x-nav-link>

<x-nav-link href="{{ route('pimpinan.laporan-barang-masuk') }}" icon="bi-box-arrow-in-down">
    Barang Masuk
</x-nav-link>

<x-nav-link href="{{ route('pimpinan.laporan-pesanan') }}" icon="bi-receipt">
    Pesanan
</x-nav-link>
