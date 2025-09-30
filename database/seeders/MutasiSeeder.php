<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MutasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        date_default_timezone_set('Asia/Jakarta');

        DB::table('mutasi')->insert([
            // tepung terigu
            [
                'id_produk' => 1,
                'tanggal' => '2025-04-01',
                'jumlah' => 60,
                'jenis' => 'masuk',
            ],

            [
                'id_produk' => 1,
                'tanggal' => '2025-04-01',
                'jumlah' => 50,
                'jenis' => 'keluar',
            ],

            [
                'id_produk' => 1,
                'tanggal' => '2025-05-01',
                'jumlah' => 50,
                'jenis' => 'masuk',
            ],

            [
                'id_produk' => 1,
                'tanggal' => '2025-05-10',
                'jumlah' => 20,
                'jenis' => 'masuk',
            ],

            [
                'id_produk' => 1,
                'tanggal' => '2025-05-05',
                'jumlah' => 25,
                'jenis' => 'keluar',
            ],

            [
                'id_produk' => 1,
                'tanggal' => '2025-05-15',
                'jumlah' => 30,
                'jenis' => 'keluar',
            ],

            /* [ */
            /*     'id_produk' => 2, */
            /*     'tanggal' => "2025-02-01", */
            /*     'jumlah' => 30, */
            /*     'jenis' => 'keluar' */
            /* ], */

            /* [ */
            /*     'id_produk' => 2, */
            /*     'tanggal' => "2025-02-01", */
            /*     'jumlah' => 30, */
            /*     'jenis' => 'keluar' */
            /* ], */
            /* [ */
            /*     'id_produk' => 2, */
            /*     'tanggal' => "2025-02-10", */
            /*     'jumlah' => 20, */
            /*     'jenis' => 'keluar' */
            /* ], */
            /* [ */
            /*     'id_produk' => 2, */
            /*     'tanggal' => "2025-02-20", */
            /*     'jumlah' => 35, */
            /*     'jenis' => 'keluar' */
            /* ], */
            /* [ */
            /*     'id_produk' => 2, */
            /*     'tanggal' => "2025-03-8", */
            /*     'jumlah' => 10, */
            /*     'jenis' => 'keluar' */
            /* ], */
            /* [ */
            /*     'id_produk' => 2, */
            /*     'tanggal' => "2025-03-1", */
            /*     'jumlah' => 20, */
            /*     'jenis' => 'keluar' */
            /* ], */
            /* [ */
            /*     'id_produk' => 2, */
            /*     'tanggal' => "2025-03-10", */
            /*     'jumlah' => 30, */
            /*     'jenis' => 'keluar' */
            /* ], */
            /* [ */
            /*     'id_produk' => 2, */
            /*     'tanggal' => "2025-04-1", */
            /*     'jumlah' => 10, */
            /*     'jenis' => 'keluar' */
            /* ], */
            /* [ */
            /*     'id_produk' => 2, */
            /*     'tanggal' => "2025-04-8", */
            /*     'jumlah' => 20, */
            /*     'jenis' => 'keluar' */
            /* ], */
            /* [ */
            /*     'id_produk' => 2, */
            /*     'tanggal' => "2025-04-16", */
            /*     'jumlah' => 30, */
            /*     'jenis' => 'keluar' */
            /* ], */

        ]);

        /* DB::table('mutasi')->insert([ */
        /*     [ */
        /*         'id_produk' => 1, */
        /*         'tanggal' => date("Y-m-d"), */
        /*         'jumlah' => 10, */
        /*         'jenis' => 'keluar' */
        /*     ], */
        /*     [ */
        /*         'id_produk' => 2, */
        /*         'tanggal' => date("Y-m-d"), */
        /*         'jumlah' => 20, */
        /*         'jenis' => 'keluar' */
        /*     ], */
        /*     [ */
        /*         'id_produk' => 3, */
        /*         'tanggal' => date("Y-m-d"), */
        /*         'jumlah' => 30, */
        /*         'jenis' => 'masuk' */
        /*     ], */
        /*     [ */
        /*         'id_produk' => 4, */
        /*         'tanggal' => date("Y-m-d"), */
        /*         'jumlah' => 5, */
        /*         'jenis' => 'masuk' */
        /*     ], */
        /*     [ */
        /*         'id_produk' => 6, */
        /*         'tanggal' => "2024-10-01", */
        /*         'jumlah' => 140, */
        /*         'jenis' => 'masuk' */
        /*     ], */
        /*     [ */
        /*         'id_produk' => 6, */
        /*         'tanggal' => "2024-10-01", */
        /*         'jumlah' => 120, */
        /*         'jenis' => 'keluar' */
        /*     ], */
        /*     [ */
        /*         'id_produk' => 6, */
        /*         'tanggal' => "2024-11-01", */
        /*         'jumlah' => 140, */
        /*         'jenis' => 'masuk' */
        /*     ], */
        /*     [ */
        /*         'id_produk' => 6, */
        /*         'tanggal' => "2024-11-01", */
        /*         'jumlah' => 130, */
        /*         'jenis' => 'keluar' */
        /*     ], */
        /* ]); */
    }
}
