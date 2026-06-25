<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Mutasi;
use App\Models\Persediaan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Reseller;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Enums\MetodePembayaran;
use App\Enums\StatusTransaksi;
use App\Enums\StatusPembayaran;

class RealisticSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // User::truncate();
        // Reseller::truncate();
        // Kategori::truncate();
        // Produk::truncate();
        // Persediaan::truncate();
        // Mutasi::truncate();
        // Transaksi::truncate();
        // Pesanan::truncate();
        // DB::table('keranjang')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Create Users
        $roles = [
            'pimpinan' => ['name' => 'Bapak Pimpinan', 'email' => 'pimpinan@gmail.com'],
            'admingudang' => ['name' => 'Agus Gudang', 'email' => 'admingudang@gmail.com'],
            'kasir' => ['name' => 'Budi Kasir', 'email' => 'kasir@gmail.com'],
            'kurir' => ['name' => 'Joko Kurir', 'email' => 'kurir@gmail.com'],
        ];

        foreach ($roles as $role => $data) {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password123'),
                'role' => $role,
                'phone' => '0812' . rand(10000000, 99999999),
            ]);
        }
        $kurirId = User::where('role', 'kurir')->first()->id;

        // 2. Create Resellers
        $resellers = [];
        for ($i = 1; $i <= 3; $i++) {
            $resellers[] = Reseller::create([
                'name' => 'Toko Reseller ' . $i,
                'email' => 'reseller' . $i . '@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'reseller',
                'phone' => '0853' . rand(10000000, 99999999),
                'alamat' => 'Jl. Mawar No. ' . $i . ', Kota Baru',
            ])->id;
        }

        // 3. Create Categories
        $kategories = [
            'Makanan Ringan' => Kategori::create(['nama_kategori' => 'Makanan Ringan'])->id,
            'Minuman' => Kategori::create(['nama_kategori' => 'Minuman'])->id,
            'Sembako' => Kategori::create(['nama_kategori' => 'Sembako'])->id,
            'Bumbu Dapur' => Kategori::create(['nama_kategori' => 'Bumbu Dapur'])->id,
        ];

        // 4. Create Products
        $productsData = [
            [
                'kode_produk' => 'PRD-001',
                'nama_produk' => 'Indomie Goreng',
                'id_kategori' => $kategories['Sembako'],
                'harga_beli' => 2500,
                'harga_jual' => 120000, // per dus
                'harga_jual_unit_kecil' => 3000,
                'batas_stok_minimum' => 5,
                'tingkat_konversi' => 40,
                'unit_kecil' => 'pcs',
                'unit_besar' => 'dos',
                'tanggal_exp' => Carbon::now()->addMonths(6),
            ],
            [
                'kode_produk' => 'PRD-002',
                'nama_produk' => 'Aqua 600ml',
                'id_kategori' => $kategories['Minuman'],
                'harga_beli' => 2000,
                'harga_jual' => 60000, // per dus
                'harga_jual_unit_kecil' => 3000,
                'batas_stok_minimum' => 10,
                'tingkat_konversi' => 24,
                'unit_kecil' => 'botol',
                'unit_besar' => 'dos',
                'tanggal_exp' => Carbon::now()->addMonths(12),
            ],
            [
                'kode_produk' => 'PRD-003',
                'nama_produk' => 'Beras Maknyuss 5kg',
                'id_kategori' => $kategories['Sembako'],
                'harga_beli' => 65000,
                'harga_jual' => 375000, 
                'harga_jual_unit_kecil' => 75000,
                'batas_stok_minimum' => 5,
                'tingkat_konversi' => 5,
                'unit_kecil' => 'kilogram',
                'unit_besar' => 'bal',
                'tanggal_exp' => Carbon::now()->addMonths(4),
            ],
            [
                'kode_produk' => 'PRD-004',
                'nama_produk' => 'Kecap Bango 520ml',
                'id_kategori' => $kategories['Bumbu Dapur'],
                'harga_beli' => 20000,
                'harga_jual' => 270000, 
                'harga_jual_unit_kecil' => 24000,
                'batas_stok_minimum' => 3,
                'tingkat_konversi' => 12,
                'unit_kecil' => 'pcs',
                'unit_besar' => 'dos',
                'tanggal_exp' => Carbon::now()->addMonths(8),
            ],
            [
                'kode_produk' => 'PRD-005',
                'nama_produk' => 'Chitato Sapi Panggang 68g',
                'id_kategori' => $kategories['Makanan Ringan'],
                'harga_beli' => 8000,
                'harga_jual' => 300000, 
                'harga_jual_unit_kecil' => 11000,
                'batas_stok_minimum' => 5,
                'tingkat_konversi' => 30,
                'unit_kecil' => 'pcs',
                'unit_besar' => 'dos',
                'tanggal_exp' => Carbon::now()->addMonths(5),
            ]
        ];

        $productIds = [];
        foreach ($productsData as $pd) {
            $productIds[] = Produk::create($pd)->id;
        }

        // 5. Create Initial Stock (Persediaan & Mutasi Masuk)
        $now = Carbon::now();
        foreach ($productIds as $pid) {
            $produk = Produk::find($pid);

            // Create batch 1 (older)
            $batch1 = Persediaan::create([
                'id_produk' => $pid,
                'jumlah' => 100 * $produk->tingkat_konversi, // 100 dus
                'tanggal_masuk' => $now->copy()->subDays(30),
            ]);

            Mutasi::create([
                'id_produk' => $pid,
                'id_persediaan' => $batch1->id,
                'jumlah' => 100 * $produk->tingkat_konversi,
                'tanggal' => $now->copy()->subDays(30),
                'jenis' => 'masuk',
                'keterangan' => 'Stok Awal',
            ]);

            // Create batch 2 (newer)
            $batch2 = Persediaan::create([
                'id_produk' => $pid,
                'jumlah' => 50 * $produk->tingkat_konversi, // 50 dus
                'tanggal_masuk' => $now->copy()->subDays(10),
            ]);

            Mutasi::create([
                'id_produk' => $pid,
                'id_persediaan' => $batch2->id,
                'jumlah' => 50 * $produk->tingkat_konversi,
                'tanggal' => $now->copy()->subDays(10),
                'jenis' => 'masuk',
                'keterangan' => 'Restock',
            ]);
        }

        // 6. Create Transactions and Sales (Pesanan & Mutasi Keluar)
        // Generate a few past transactions
        for ($i = 0; $i < 15; $i++) {
            $isReseller = (rand(1, 10) > 4); // 60% reseller, 40% direct
            $tanggal = $now->copy()->subDays(rand(1, 28))->subHours(rand(1, 24));

            $transaksi = Transaksi::create([
                'id_reseller' => $isReseller ? $resellers[array_rand($resellers)] : null,
                'metode_pembayaran' => rand(0, 1) ? MetodePembayaran::TUNAI : MetodePembayaran::TRANSFER,
                'status' => StatusTransaksi::SELESAI,
                'status_pembayaran' => StatusPembayaran::LUNAS,
                'tanggal' => $tanggal,
                'id_kurir' => $isReseller ? $kurirId : null,
            ]);

            $numItems = rand(1, 3);
            $selectedProducts = (array) array_rand($productIds, $numItems);
            if (!is_array($selectedProducts)) $selectedProducts = [$selectedProducts];

            foreach ($selectedProducts as $idx) {
                $pid = $productIds[$idx];
                $produk = Produk::find($pid);

                $satuan = rand(0, 1); // 0 = besar, 1 = kecil
                $qty = $satuan ? rand(5, 20) : rand(1, 3);

                // create pesanan
                Pesanan::create([
                    'id_produk' => $pid,
                    'id_transaksi' => $transaksi->id,
                    'jumlah' => $qty,
                    'satuan' => $satuan,
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);

                // deduct stock (FEFO logic simplification)
                $qtyPcs = $satuan ? $qty : ($qty * $produk->tingkat_konversi);
                $persediaan = Persediaan::where('id_produk', $pid)
                                ->where('jumlah', '>', 0)
                                ->orderBy('tanggal_masuk', 'asc')
                                ->first();

                if ($persediaan) {
                    $deduct = min($qtyPcs, $persediaan->jumlah);
                    $persediaan->jumlah -= $deduct;
                    $persediaan->save();

                    Mutasi::create([
                        'id_produk' => $pid,
                        'id_persediaan' => $persediaan->id,
                        'jumlah' => $deduct,
                        'tanggal' => $tanggal,
                        'jenis' => 'keluar',
                        'keterangan' => 'Penjualan TRX ' . $transaksi->id,
                        'satuan' => false // mutasi always recorded in unit terkecil internally (mostly)
                    ]);
                }
            }
        }
    }
}
