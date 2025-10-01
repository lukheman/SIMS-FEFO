<x-nav-link :href="route('admintoko.index')" icon="bi-speedometer2">
    Dashboard
</x-nav-link>

<x-nav-link :href="route('admintoko.kasir')" icon="bi-cash-stack">
    Kasir
</x-nav-link>

<li class="nav-header">PRODUK</li>

<x-nav-link :href="route('admintoko.persediaan')" icon="bi-box-seam">
    Persediaan Barang
</x-nav-link>

<x-nav-link :href="route('admintoko.pesanan')" icon="bi-clipboard-check">
    Pesanan
</x-nav-link>

<li class="nav-header">LAPORAN</li>

<x-nav-link :href="route('admintoko.laporan-penjualan')" icon="bi-bar-chart">
    Penjualan
</x-nav-link>

<x-nav-link :href="route('admintoko.laporan-pesanan')" icon="bi-receipt">
    Pesanan
</x-nav-link>
