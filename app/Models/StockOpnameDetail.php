<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    use HasFactory;

    protected $fillable = ['stock_opname_id', 'barang_id', 'jumlah_sistem', 'jumlah_fisik', 'selisih', 'catatan'];

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
