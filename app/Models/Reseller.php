<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Enums\Role;

class Reseller extends Authenticatable
{
    protected $table = 'reseller';

    protected $guarded = [];

    public function casts(): array {
        return [
            'role' => Role::class
        ];
    }

    public function keranjang()
    {
        return $this->hasOne(Keranjang::class, 'id_reseller');
    }

    public function transaksi() {
        return $this->hasMany(Transaksi::class, 'id_reseller');
    }

    protected static function booted() {

        static::created(function ($reseller) {
            $reseller->keranjang()->create();
        });

    }
}
