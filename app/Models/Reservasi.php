<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'ruangan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'keperluan',
        'status',
        'catatan_admin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
