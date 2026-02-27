<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemeliharaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pemeliharaan';
    protected $fillable = [
        'barang_id',
        'tanggal_servis',
        'deskripsi',
        'biaya',
        'status'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
