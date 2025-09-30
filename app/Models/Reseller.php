<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Reseller extends Authenticatable
{
    protected $table = 'reseller';

    protected $guarded = [];

    public function keranjang()
    {
        return $this->hasOne(Keranjang::class);
    }
}
