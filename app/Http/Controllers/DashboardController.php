<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Kategori;
use App\Models\Reservasi;
use App\Models\LaporanKerusakan;
use Illuminate\Http\Request;

use App\Models\Pemeliharaan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data['total_barang'] = Barang::count();
        $data['barang_rusak'] = Barang::where('kondisi', 'rusak')->count();
        $data['sedang_dipinjam'] = Peminjaman::where('status', 'dipinjam')->count();
        $data['stok_menipis'] = Barang::where('stok', '<', 5)->count();
        $data['total_kategori'] = Kategori::count();

        // Biaya Pemeliharaan 6 Bulan Terakhir (Grafik)
        $data['maintenance_monthly'] = Pemeliharaan::select(
            DB::raw('SUM(biaya) as total'),
            DB::raw("DATE_FORMAT(tanggal_servis, '%M') as month"),
            DB::raw("MONTH(tanggal_servis) as m_num")
        )
        ->where('tanggal_servis', '>=', now()->subMonths(6))
        ->groupBy('month', 'm_num')
        ->orderBy('m_num', 'asc')
        ->get();

        // Rincian Biaya per Ruangan (Bulan ini)
        $data['maintenance_by_location'] = Pemeliharaan::join('barang', 'pemeliharaan.barang_id', '=', 'barang.id')
            ->select(
                'barang.lokasi',
                DB::raw('SUM(pemeliharaan.biaya) as total_biaya'),
                DB::raw('COUNT(pemeliharaan.id) as total_perbaikan')
            )
            ->whereMonth('pemeliharaan.tanggal_servis', now()->month)
            ->groupBy('barang.lokasi')
            ->orderBy('total_biaya', 'desc')
            ->get();
        
        $data['total_biaya_bulan_ini'] = $data['maintenance_by_location']->sum('total_biaya');

        // Reservasi yang butuh persetujuan (khusus Admin)
        $data['reservasi_pending'] = Reservasi::with(['user', 'ruangan'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Peminjaman terbaru
        $data['peminjaman_terbaru'] = Peminjaman::with(['barang', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Peminjaman jatuh tempo (misal > 3 hari belum dikembalikan)
        $data['jatuh_tempo'] = Peminjaman::with(['barang', 'user'])
            ->where('status', 'dipinjam')
            ->where('tanggal_pinjam', '<', now()->subDays(3))
            ->get();

        // Stok BHP Kritis
        $data['bhp_kritis'] = Barang::where('tipe', 'bhp')
            ->whereColumn('stok', '<=', 'min_stok')
            ->get();

        // Laporan Kerusakan Pending
        $data['laporan_pending'] = LaporanKerusakan::with(['barang', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Jadwal Servis Mendatang (7 hari ke depan + yang sudah lewat)
        $data['maintenance_reminders'] = Barang::where('tipe', 'aset')
            ->whereNotNull('tgl_servis_berikutnya')
            ->where('tgl_servis_berikutnya', '<=', today()->addDays(7))
            ->orderBy('tgl_servis_berikutnya', 'asc')
            ->get();

        return view('dashboard', $data);
    }
}
