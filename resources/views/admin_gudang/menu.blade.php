<x-nav-link
    :href="route('admingudang.index')"
    icon="bi-speedometer2">
    Dashboard
</x-nav-link>

<li class="nav-header">DATA PRODUK</li>

<x-nav-link
    :href="route('admingudang.produk')"
    icon="bi-box-seam">
    Data Produk
</x-nav-link>

<x-nav-link
    :href="route('admingudang.penjualan')"
    icon="bi-receipt">
    Penjualan
</x-nav-link>

<li class="nav-header">RESTOK</li>

<x-nav-link
    :href="route('admingudang.barang-masuk')"
    icon="bi-box-arrow-in-down">
    Barang Masuk
</x-nav-link>
