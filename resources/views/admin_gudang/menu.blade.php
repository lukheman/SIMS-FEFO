
<x-nav-link
    :href="route('admingudang.dashboard')"
    icon="fas fa-tachometer-alt"
    :active="$page === 'Dashboard'" >
    Dashboard
</x-nav-link>

<li class="nav-header">DATA PRODUK</li>

<x-nav-link
    :href="route('admingudang.produk')"
    icon="fas fa-cubes"
    :active="$page === 'Produk'">
    Data Produk
</x-nav-link>

<x-nav-link
    :href="route('admingudang.produk.persediaan')"
    icon="fas fa-boxes"
    :active="$page === 'Persediaan Produk'">
    Persediaan Produk
</x-nav-link>

<x-nav-link
    :href="route('admingudang.produk.biaya-pemesanan')"
    icon="fas fa-shopping-cart"
    :active="$page === 'Biaya Pemesanan Produk'">
    Biaya Pemesanan
</x-nav-link>

<x-nav-link
    :href="route('admingudang.produk.biaya-penyimpanan')"
    icon="fas fa-dolly-flatbed"
    :active="$page === 'Biaya Penyimpanan Produk'">
    Biaya Penyimpanan
</x-nav-link>

<li class="nav-header">RESTOK</li>

<x-nav-link
    :href="route('admingudang.barang-masuk')"
    icon="fas fa-box-open"
    :active="$page === 'Barang Masuk'">
    Barang Masuk
</x-nav-link>

<x-nav-link
    :href="route('admingudang.pesanan')"
    icon="fas fa-clipboard-list"
    :active="$page === 'Pesanan'">
    Pesanan
</x-nav-link>

<li class="nav-header">EOQ</li>

<x-nav-link
    :href="route('admingudang.eoq')"
    icon="fas fa-calculator"
    :active="$page === 'EOQ'">
    Hitung EOQ
</x-nav-link>

<li class="nav-header">LAPORAN</li>

<x-nav-link
    :href="route('admingudang.laporan-barang-masuk')"
    icon="fas fa-box-open"
    :active="$page === 'Laporan Barang Masuk'">
    Barang Masuk
</x-nav-link>

<x-nav-link
    :href="route('admingudang.laporan-penjualan')"
    icon="far fa-chart-bar"
    :active="$page === 'Laporan Penjualan'">
    Penjualan
</x-nav-link>
