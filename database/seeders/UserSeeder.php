<?php

namespace Database\Seeders;

use App\Models\Reseller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar role dengan nama berbeda untuk setiap role
        $roles = [
            'admin toko' => 'admintoko',
            'admin gudang' => 'admingudang',
            'pemilik toko' => 'pemiliktoko',
            'Kurir 1' => 'kurir',
            'Kurir 2' => 'kurir',
        ];

        // Menyisipkan data ke tabel 'users'
        foreach ($roles as $name => $role) {
            DB::table('users')->insert([
                /* 'username' => $role, */
                'email' => str_replace(' ', '', strtolower($name)).'@example.com',
                'password' => Hash::make('password123'), // Gunakan hashing untuk keamanan
                'role' => $role,
                'name' => $name, // Nama sesuai dengan role
                'phone' => '0820'.sprintf('%08d', mt_rand(0, 99999999)),
            ]);
        }

        Reseller::create([
            'name' => 'Reseller 1',
            'email' => 'reseller1@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'reseller',
        ]);

        Reseller::create([
            'name' => 'Reseller 2',
            'email' => 'reseller2@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'reseller',
        ]);

    }
}
