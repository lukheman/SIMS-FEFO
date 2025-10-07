<div class="row">

  <!-- Jumlah Produk -->
  <div class="col-lg-6 col-12">
    <div class="small-box text-bg-primary shadow">
      <div class="inner">
        <h3>{{ $jumlahProduk }}</h3>
        <p>Jumlah Produk</p>
      </div>
      <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path
          d="M3 3.75A.75.75 0 013.75 3h16.5a.75.75 0 01.75.75v12a.75.75 0 01-.75.75H3.75A.75.75 0 013 15.75v-12zM4.5 4.5v10.5h15V4.5h-15zM3 18.75a.75.75 0 000 1.5h18a.75.75 0 000-1.5H3z">
        </path>
      </svg>
    </div>
  </div>

  <!-- Jumlah Penjualan -->
  <div class="col-lg-6 col-12">
    <div class="small-box text-bg-success shadow">
      <div class="inner">
<h3>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
        <p>Jumlah Penjualan Hari Ini</p>
      </div>
      <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path
          d="M3.75 4.5a.75.75 0 000 1.5h16.5a.75.75 0 000-1.5H3.75zM3 9.75a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 9.75zm.75 3a.75.75 0 000 1.5h16.5a.75.75 0 000-1.5H3.75zm0 3.75a.75.75 0 000 1.5h10.5a.75.75 0 000-1.5H3.75z">
        </path>
      </svg>
    </div>
  </div>
</div>
