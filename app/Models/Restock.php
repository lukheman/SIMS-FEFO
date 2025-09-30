<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restock extends Model
{
    protected $table = 'restock';

    protected $guarded = [];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
