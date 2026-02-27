<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruangan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'kapasitas',
        'fasilitas',
        'foto_ruangan',
        'status'
    ];

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }
}
