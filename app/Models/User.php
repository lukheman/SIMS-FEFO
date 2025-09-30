<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    public $timestamps = false;

    public $guarded = [];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_user');
    }

    public function transaksiKurir()
    {
        return $this->hasMany(Transaksi::class, 'id_kurir');
    }
}
