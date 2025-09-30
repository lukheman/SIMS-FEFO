<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persediaan extends Model
{
    protected $table = 'persediaan';

    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
