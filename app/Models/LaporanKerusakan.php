<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanKerusakan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'laporan_kerusakan';
    
    protected $fillable = [
        'barang_id',
        'user_id',
        'deskripsi_kerusakan',
        'foto_kerusakan',
        'status',
        'catatan_admin'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
