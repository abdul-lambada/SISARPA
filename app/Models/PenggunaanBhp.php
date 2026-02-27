<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenggunaanBhp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penggunaan_bhp';
    protected $fillable = [
        'barang_id',
        'user_id',
        'jumlah',
        'tanggal',
        'catatan'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
