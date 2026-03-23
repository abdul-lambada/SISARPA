<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Reservasi;
use App\Models\Barang;
use App\Models\Setting;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::all();
        $today = now()->format('Y-m-d');
        
        // Get reservations for today to determine status
        $reservations = Reservasi::where('tanggal', $today)
            ->where('status', 'approved')
            ->pluck('ruangan_id')
            ->toArray();

        $settings = Setting::pluck('value', 'key');

        $stats = [
            'total_barang' => Barang::count(),
            'total_ruangan' => $ruangans->count(),
            'barang_tersedia' => Barang::where('kondisi', 'baik')->count(),
            'kategori_count' => \App\Models\Kategori::count(),
            'ruangan_booking' => count($reservations)
        ];

        // Contoh: Ambil 4 aset utama untuk dipajang di landing page
        $featured_assets = Barang::where('kondisi', 'baik')
            ->where('tipe', 'aset')
            ->latest()
            ->take(4)
            ->get();

        return view('welcome', compact('ruangans', 'reservations', 'stats', 'settings', 'featured_assets'));
    }
}
