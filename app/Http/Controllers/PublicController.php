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
            'barang_tersedia' => Barang::where('kondisi', 'baik')->count()
        ];

        return view('welcome', compact('ruangans', 'reservations', 'stats', 'settings'));
    }
}
