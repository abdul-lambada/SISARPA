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
        'tipe',
        'kode_barang',
        'nama_barang',
        'satuan',
        'merk',
        'spesifikasi',
        'lokasi',
        'kondisi',
        'stok',
        'min_stok',
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

    public function penggunaanBhps()
    {
        return $this->hasMany(PenggunaanBhp::class, 'barang_id');
    }
}
