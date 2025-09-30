<?php

use App\Http\Controllers\AdminGudangController;
use App\Http\Controllers\AdminTokoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\PemilikTokoController;
use App\Http\Controllers\PersediaanController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\RestockController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\InputDataMutasiController;
use Illuminate\Support\Facades\Route;

Route::get('/input-data-mutasi', [InputDataMutasiController::class, 'index'])->name('input-data-mutasi');

// Public Routes (tanpa autentikasi)
Route::get('/', [AuthController::class, 'showLoginForm'])->name('home');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/registrasi', [AuthController::class, 'registrasi'])->name('registrasi');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');

// Authenticated Routes (umum untuk semua guard)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'index'])->name('dashboard');
    Route::resource('profile', ProfileController::class)->only(['index', 'update']);
});

// Routes untuk Reseller (guard: reseller)
Route::middleware(['auth:reseller', 'role:reseller,reseller'])->prefix('reseller')->name('reseller.')->group(function () {
    Route::controller(ResellerController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/katalog', 'katalog')->name('katalog');
        Route::get('/keranjang', 'keranjang')->name('keranjang');
        Route::get('/transaksi', 'transaksi')->name('transaksi');
        Route::get('/pengiriman', 'pengiriman')->name('pengiriman');
    });
});

// Routes untuk Admin Toko (guard: web)
Route::middleware(['auth:web', 'role:web,admintoko'])->prefix('admintoko')->name('admintoko.')->group(function () {
    Route::controller(AdminTokoController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/kasir', 'kasir')->name('kasir');
        Route::post('/transaksi', 'transaksi')->name('transaksi');
        Route::get('/pesanan', 'pesanan')->name('pesanan');
        Route::get('/persediaan', 'persediaan')->name('persediaan');
        Route::post('/nota', 'nota')->name('nota');
        Route::get('/laporan-penjualan', 'laporanPenjualan')->name('laporan-penjualan');
        Route::get('/laporan-pesanan', 'laporanPesanan')->name('laporan-pesanan');
    });
});

// Routes untuk Admin Gudang (guard: web)
Route::middleware(['auth:web', 'role:web,admingudang'])->prefix('admingudang')->name('admingudang.')->group(function () {
    Route::controller(AdminGudangController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/produk', 'produk')->name('produk');
        Route::get('/produk/persediaan', 'persediaan')->name('produk.persediaan');
        Route::get('/produk/biaya-pemesanan', 'biayaPemesanan')->name('produk.biaya-pemesanan');
        Route::get('/produk/biaya-penyimpanan', 'biayaPenyimpanan')->name('produk.biaya-penyimpanan');
        Route::get('/eoq', 'eoq')->name('eoq');
        Route::post('/hitung', 'hitung')->name('hitung');
        Route::get('/pesanan', 'pesanan')->name('pesanan');
        Route::get('/barang-masuk', 'barangMasuk')->name('barang-masuk');
        Route::get('/laporan-barang-masuk', 'laporanBarangMasuk')->name('laporan-barang-masuk');
        Route::get('/laporan-penjualan', 'laporanPenjualan')->name('laporan-penjualan');
        Route::get('/scan-barang-masuk', 'scanBarangMasuk')->name('scan-barang-masuk');
    });
});

// Routes untuk Pemilik Toko (guard: web)
Route::middleware(['auth:web', 'role:web,pemiliktoko'])->prefix('pemiliktoko')->name('pemiliktoko.')->group(function () {
    Route::controller(PemilikTokoController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/persediaan', 'persediaan')->name('persediaan');
        Route::get('/laporan-penjualan', 'laporanPenjualan')->name('laporan-penjualan');
        Route::get('/laporan-persediaan-produk', 'laporanPersediaanProduk')->name('laporan-persediaan-produk');
        Route::get('/laporan-barang-masuk', 'laporanBarangMasuk')->name('laporan-barang-masuk');
        Route::get('/laporan-eoq', 'laporanEOQ')->name('laporan-eoq');
        Route::get('/laporan-pesanan', 'laporanPesanan')->name('laporan-pesanan');
    });
});

// Routes untuk Kurir (guard: web)
Route::middleware(['auth:web', 'role:web,kurir'])->prefix('kurir')->name('kurir.')->group(function () {
    Route::controller(KurirController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/pesanan', 'pesanan')->name('pesanan');
        Route::get('/konfirmasi-pembayaran', 'konfirmasiPembayaranPage')->name('konfirmasi-pembayaran-page');
        Route::post('/konfirmasi-pembayaran/{id}', 'konfirmasiPembayaran')->name('konfirmasi-pembayaran');
    });
});

// Resource dan Custom Routes
Route::resource('produk', ProdukController::class)->only(['index', 'store', 'update', 'destroy', 'show'])->middleware('auth');
Route::get('produk/kode-produk/{barcode}', [ProdukController::class, 'kodeProduk'])->name('produk.kode-produk')->middleware('auth');

Route::resource('pesanan', PesananController::class)->only(['store', 'update', 'destroy', 'show'])->middleware('auth');
Route::post('pesanan/checkout', [PesananController::class, 'checkout'])->name('pesanan.checkout')->middleware('auth');
Route::post('pesanan/destroy-many', [PesananController::class, 'destroyMany'])->name('pesanan.destroy-many')->middleware('auth');

Route::resource('persediaan', PersediaanController::class)->only(['store', 'update', 'destroy', 'show'])->middleware('auth');

Route::resource('transaksi', TransaksiController::class)->only(['index', 'store', 'update', 'destroy', 'show'])->middleware('auth');
Route::post('transaksi/detail', [TransaksiController::class, 'detail'])->name('transaksi.detail')->middleware('auth');
Route::post('transaksi/bukti-pembayaran/{id}', [TransaksiController::class, 'buktiPembayaran'])->name('transaksi.bukti-pembayaran')->middleware('auth');
Route::post('transaksi/update-status-pembayaran/{id}', [TransaksiController::class, 'updateStatusPembayaran'])->name('transaksi.update-status-pembayaran')->middleware('auth');
Route::post('transaksi/update-kurir/{id}', [TransaksiController::class, 'updateKurir'])->name('transaksi.update-kurir')->middleware('auth');

Route::resource('mutasi', MutasiController::class)->only(['store', 'update', 'destroy', 'show'])->middleware('auth');

Route::resource('restock', RestockController::class)->only(['store', 'update', 'destroy'])->middleware('auth');
Route::get('restock/exist/{barcode}', [RestockController::class, 'exist'])->name('restock.exist')->middleware('auth');

Route::post('/laporan/laporan-penjualan', [LaporanController::class, 'laporanPenjualan'])->name('laporan.laporan-penjualan')->middleware('auth');
Route::post('/laporan/laporan-pesanan', [LaporanController::class, 'laporanPesanan'])->name('laporan.laporan-pesanan')->middleware('auth');
Route::post('/laporan/laporan-barang-masuk', [LaporanController::class, 'laporanBarangMasuk'])->name('laporan.laporan-barang-masuk')->middleware('auth');
Route::get('/laporan/laporan-persediaan-produk', [LaporanController::class, 'laporanPersediaanProduk'])->name('laporan.laporan-persediaan-produk')->middleware('auth');
Route::post('/laporan/laporan-eoq', [LaporanController::class, 'laporanEOQ'])->name('laporan.laporan-eoq')->middleware('auth');
