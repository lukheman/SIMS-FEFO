<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    public $timestamps = false;

    public $guarded = [];

    public function casts(): array {
        return [
            'role' => Role::class
        ];
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiMode::class, 'id_user');
    }

    public function transaksiKurir()
    {
        return $this->hasMany(TransaksiMode::class, 'id_kurir');
    }
}
