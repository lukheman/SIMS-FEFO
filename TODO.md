### To-Do List Revisi Aplikasi

1. Role Admin Gudang

[x] **Batasi akses input tanggal expired:** Kembalikan fitur seperti versi pertama. Pastikan hanya *role* Admin Gudang yang memiliki akses untuk menginput atau mengubah tanggal kedaluwarsa barang.

Keterangan:

Fitur untuk input barang expired ada pada Admin Gudang > Barang Masuk.
Tanggal expired harus di input ketika barang masuk karena ada kemungkinan barang yang sama mempunyai tanggal expired yang berbeda.

2. Fitur Notifikasi Stok (Admin & Kasir)

[x] **Buat notifikasi stok menipis:** Buat sistem alert/notifikasi otomatis yang muncul ketika stok barang mencapai batas minimum tertentu untuk role admin dan kasir.
[ ] Buatkan halaman untuk menampilkan notifikasi

3. Modul Laporan (Pimpinan, Admin/Kasir)

[x] **Laporan Penjualan General:** Buat fitur untuk mencetak/melihat laporan penjualan berdasarkan filter: Harian, Mingguan, Bulanan, dan Tahunan.
[x] **Laporan Penjualan Reseller:** Buat fitur laporan penjualan yang difilter spesifik untuk melihat riwayat/performa masing-masing reseller.

4. Modul Pengiriman & Kurir

[ ] **Update Status Pesanan:** Buat fitur untuk Kurir agar bisa melakukan konfirmasi/update status pesanan dengan alur: **Dikemas** ➔ **Dikirim** ➔ **Selesai**.
[x] **Tracking Pesanan:** Tampilkan keterangan status pengiriman tersebut di halaman akun/dashboard Reseller dan Kasir (mirip UX Shopee).

5. Pemesanan Reseller (UI/UX & Validasi)

[x] **Tampilkan Stok:** Munculkan informasi jumlah stok barang yang tersedia di halaman produk/katalog saat Reseller akan memesan.
[x] **Validasi Limit Stok:** Perbaiki penanganan *error* saat order. Jika Reseller memesan melebihi sisa stok (misal stok 5, pesan 10), pastikan sistem tidak *crash/error*, melainkan memunculkan notifikasi/peringatan otomatis (misal: "Stok tidak mencukupi") dan tombol tambah/pesan dinonaktifkan untuk jumlah tersebut.

6. Role Pimpinan

[x] **Batasi hak akses (Read-Only):** Atur *permission* untuk role Pimpinan. Pimpinan hanya boleh melihat (view) laporan penjualan (Harian/Mingguan/Bulanan/Tahunan) dan laporan penjualan tiap reseller. Pimpinan tidak boleh memiliki tombol/akses untuk mengedit atau menghapus data.
