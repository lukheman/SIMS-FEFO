<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjang_belanja';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(Reseller::class, 'id', 'id_reseller');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_pesanan');
    }
}
