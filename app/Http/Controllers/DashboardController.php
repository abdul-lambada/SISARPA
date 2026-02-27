<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Kategori;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data['total_barang'] = Barang::count();
        $data['barang_rusak'] = Barang::where('kondisi', 'rusak')->count();
        $data['sedang_dipinjam'] = Peminjaman::where('status', 'dipinjam')->count();
        $data['stok_menipis'] = Barang::where('stok', '<', 5)->count();
        $data['total_kategori'] = Kategori::count();

        $data['peminjaman_terbaru'] = Peminjaman::with(['barang', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', $data);
    }
}
