<x-nav-link :href="route('kasir.index')" icon="bi-speedometer2">
    Dashboard
</x-nav-link>

<li class="nav-header">TRANSAKSI</li>

<x-nav-link :href="route('kasir.kasir')" icon="bi-cash-stack">
    Kasir
</x-nav-link>

<x-nav-link :href="route('kasir.pesanan')" icon="bi-clipboard-check">
    Pesanan
</x-nav-link>

<li class="nav-header">INVENTORI</li>

<x-nav-link :href="route('kasir.persediaan')" icon="bi-box-seam">
    Persediaan Barang
</x-nav-link>
