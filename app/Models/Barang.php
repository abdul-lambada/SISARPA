<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barang';
    protected $fillable = [
        'kategori_id',
        'kode_barang',
        'nama_barang',
        'merk',
        'spesifikasi',
        'lokasi',
        'kondisi',
        'stok',
        'foto_barang'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'barang_id');
    }

    public function pemeliharaans()
    {
        return $this->hasMany(Pemeliharaan::class, 'barang_id');
    }
}
