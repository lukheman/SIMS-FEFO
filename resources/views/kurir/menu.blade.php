<li class="nav-item">
    <a href="{{ route('kurir.dashboard') }}" class="nav-link {{ $page === 'Dashboard' ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p> Dashboard </p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('kurir.pesanan') }}" class="nav-link {{ $page === 'Pesanan' ? 'active' : '' }}">
        <i class="nav-icon fas fa-clipboard-list"></i>
       <p>Pesanan</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('kurir.konfirmasi-pembayaran-page') }}"
        class="nav-link {{ $page === 'Konfirmasi Pembayaran' ? 'active' : '' }}">
        <i class="nav-icon fas fa-qrcode"></i>
       <p>Konfirmasi Pembayaran</p>
    </a>
</li>
