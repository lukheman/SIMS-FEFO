<li class="nav-item">
    <a href="{{ route('reseller.dashboard') }}" class="nav-link {{ $page === 'Dashboard' ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p> Dashboard </p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('reseller.katalog')}}" class="nav-link {{ $page === 'Katalog' ? 'active' : '' }}">
        <i class="nav-icon fas fa-boxes"></i>
        <p> Katalog Produk </p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('reseller.keranjang')}}" class="nav-link {{ $page === 'Keranjang' ? 'active' : '' }}">
        <i class="nav-icon fas fa-shopping-cart"></i>
        <p> Keranjang </p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('reseller.transaksi')}}" class="nav-link {{ $page === 'Transaksi' ? 'active' : '' }}">
        <i class="nav-icon fas fa-clipboard-list"></i>
        <p>Transaksi Anda</p>
    </a>
</li>
