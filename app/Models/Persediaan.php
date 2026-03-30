<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persediaan extends Model
{
    use HasFactory;
    protected $table = 'persediaan';

    protected $guarded = [];

    protected $casts = [
        'tanggal_exp' => 'date',
        'tanggal_masuk' => 'date',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function mutasi()
    {
        return $this->hasMany(Mutasi::class, 'id_persediaan');
    }

    // Scope: batch yang masih punya stok
    public function scopeAktif($query)
    {
        return $query->where('jumlah', '>', 0);
    }

    // Scope: urutan FEFO (expired terdekat duluan, NULL di belakang)
    public function scopeFefoOrder($query)
    {
        return $query->orderByRaw('tanggal_exp IS NULL, tanggal_exp ASC');
    }

    // Cek apakah batch sudah expired
    public function getIsExpiredAttribute(): bool
    {
        return $this->tanggal_exp && $this->tanggal_exp->isPast();
    }

    // Cek apakah batch hampir expired (< 30 hari)
    public function getIsHampirExpiredAttribute(): bool
    {
        return $this->tanggal_exp && !$this->tanggal_exp->isPast() && $this->tanggal_exp->diffInDays(now()) <= 30;
    }
}
