<x-nav-link
    :href="route('admingudang.dashboard')"
    icon="bi-speedometer2">
    Dashboard
</x-nav-link>

<li class="nav-header">DATA PRODUK</li>

<x-nav-link
    :href="route('admingudang.produk')"
    icon="bi-box-seam">
    Data Produk
</x-nav-link>

{{--

<x-nav-link
    :href="route('admingudang.produk.persediaan')"
    icon="bi-archive">
    Persediaan Produk
</x-nav-link>

--}}

<li class="nav-header">RESTOK</li>

<x-nav-link
    :href="route('admingudang.barang-masuk')"
    icon="bi-box-arrow-in-down">
    Barang Masuk
</x-nav-link>

<x-nav-link
    :href="route('admingudang.pesanan')"
    icon="bi-clipboard-check">
    Pesanan
</x-nav-link>


{{--

<li class="nav-header">EOQ</li>

<x-nav-link
    :href="route('admingudang.eoq')"
    icon="bi-calculator">
    Hitung EOQ
</x-nav-link>

<li class="nav-header">LAPORAN</li>

<x-nav-link
    :href="route('admingudang.laporan-barang-masuk')"
    icon="bi-box-arrow-in-down">
    Barang Masuk
</x-nav-link>

<x-nav-link
    :href="route('admingudang.laporan-penjualan')"
    icon="bi-bar-chart">
    Penjualan
</x-nav-link>
--}}

