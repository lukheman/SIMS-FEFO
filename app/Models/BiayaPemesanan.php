<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiayaPemesanan extends Model
{
    protected $table = 'biaya_pemesanan_produk';

    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
