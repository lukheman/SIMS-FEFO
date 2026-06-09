<x-nav-link href="{{ route('reseller.index') }}" icon="bi-speedometer2">
    Dashboard
</x-nav-link>

<li class="nav-header">BELANJA</li>

<x-nav-link href="{{ route('reseller.katalog') }}" icon="bi-boxes">
    Katalog Produk
</x-nav-link>

<x-nav-link href="{{ route('reseller.keranjang') }}" icon="bi-cart4">
    Keranjang
</x-nav-link>

<li class="nav-header">PESANAN</li>

<x-nav-link href="{{ route('reseller.transaksi') }}" icon="bi-clipboard-check">
    Transaksi Anda
</x-nav-link>
