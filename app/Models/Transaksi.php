<?php

namespace App\Models;

use App\Constants\MetodePembayaran;
use App\Constants\StatusPembayaran;
use App\Constants\StatusTransaksi;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $guarded = [];

    protected $casts = [
        'metode_pembayaran' => MetodePembayaran::class,
        'status' => StatusTransaksi::class,
        'status_pembayaran' => StatusPembayaran::class,
        'tanggal' => 'datetime:Y-m-d',
    ];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_pesanan');
    }

    public function user()
    {
        return $this->belongsTo(Reseller::class, 'id_reseller');
    }

    public function kurir()
    {
        return $this->belongsTo(User::class, 'id_kurir');
    }

    public function getTotalHargaAttribute()
    {
        return Pesanan::where('id_transaksi', $this->id)->sum('total_harga');
    }
}
