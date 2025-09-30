<x-nav-link
    :href="route('admintoko.index')"
    icon="fas fa-tachometer-alt"
    :active="$page === 'Dashboard'">
    Dashboard
</x-nav-link>

<x-nav-link
    :href="route('admintoko.kasir')"
    icon="fas fa-cash-register"
    :active="$page === 'Kasir'"
>
Kasir
</x-nav-link>

<li class="nav-header">PRODUK</li>

<x-nav-link
    :href="route('admintoko.persediaan')"
    icon="fas fa-boxes"
    :active="$page === 'Persediaan'">
    Persediaan Barang
</x-nav-link>

<x-nav-link
    :href="route('admintoko.pesanan')"
    icon="fas fa-clipboard"
    :active="$page === 'Pesanan'">
    Pesanan
</x-nav-link>

<li class="nav-header">LAPORAN</li>

<x-nav-link
    :href="route('admintoko.laporan-penjualan')"
    icon="fas fa-chart-bar"
    :active="$page === 'Laporan Penjualan'">
    Penjualan
</x-nav-link>

<x-nav-link
    :href="route('admintoko.laporan-pesanan')"
    icon="fas fa-receipt"
    :active="$page === 'Laporan Pesanan'">
    Pesanan
</x-nav-link>
