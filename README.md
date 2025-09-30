# username dan password masing-masing role

| username                 | password    |
|--------------------------|-------------|
| admingudang@example.com  | password123 |
| admintoko@example.com    | password123 |
| pemiliktoko@example.com  | password123 |
| reseller1@example.com    | password123 |
| reseller2@example.com    | password123 |
| kurir1@example.com       | password123 |
| kurir2@example.com       | password123 |

# Kebutuhan instalasi

- Laravel Framework 11.42.1
- php 8.2
- composer 2.8.6 (package manager php)

# Menjalankan aplikasi

```
php artisan serve
```

# TODO

## Revisi pertama

- [x] tambahkan rata-rata penjualan harian di laporan penjualan
- [x] mekanisme tambah barang masuk (scanning bar code produk) jika produk belum ada maka tambahkan
- [x] rata-rata penjualan dibuat otomatis
- [x] lead time dibuat otomatis
- [x] tambah metode pembayaran di reseller dan mengirim bukti pembayaran ke admin toko
- [x] reseller dapat memilih pesanan di keranjang untuk dicheckout atau dihapus
- [x] view: table data produk dan barang masuk tambahkan harga barang
- [x] pisahkan table biaya penyimpanan, pemesanan, dan produk
- [x] perbaiki alur pengurangan persediaan barang ketika terjadi pembelian
- [x] EOQ dibuat perbulan

## penjelasan halaman transaksi di role admin toko
- pengrungan barang akan terjadi ketika transaksi diterima

## penjelasan halaman transaksi di role reseller

- secara default halaman akan menampilkan sub halaman belum bayar
- sub halaman Belum Bayar akan menampilkan transaksi dengan metode pembayaran transfer dengan status pembayaran belum bayar
- sub halaman Pending akan menampilkan transaksi dengan status transaksi `pending` (semua metode pembayaran)
- sub halaman Diproses akan menampilkan transaksi dengan status transaksi `diproses` (semua metode pembayaran)
- sub halaman Dikirim akan menampilkan transaksi dengan status transaksi `dikirim` (semua metode pembayaran)
- sub halaman Selesai akan menampilkan transaksi dengan status transaksi `selesai` (semua metode pembayaran)


## penjelasana halaman konfimasi pembayaran pada role kurir
- jika kurir mengonfirmasi pesanan dengan scan-qr maka status transaksi akan berubah menjadi `diterima` dan status pembayaran akan menjadi `lunas` karena kurir telah menerima uang

## Revisi

- [x] tambah kop di laporan
- [x] tambah pencarian produk di katalog
- [x] halaman registrasi reseller tambahkan tombol keluar
- [x] halaman profile user
- [x] tambah tanggal expater di admin gudang dan reseller
- [x] tambahakn alamat,no hp, nama reseller, di bagian atas tabel
- [x] admin gudang eoq: tambahkan stok saat ini berapa dan harga
- [x] tambahkan pencarian di bagian admin gudang eoq
- [x] tambahkan tombol tambah pesanan di keranjang reseller

## Revisi

- tambahkan penjualan langsung pada admintoko (kasir) 50
- tambahkan laporan eoq tiap bulan di admintoko (f)

# Revisi

- ganti perhitungan
