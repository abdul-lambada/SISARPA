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
        $user = auth()->user();
        
        // Data Dasar (Global untuk Admin, Filtered untuk User)
        if ($user->hasAnyRole(['Super Admin', 'Petugas Sarpras', 'Kepala Sekolah'])) {
            $data['total_barang'] = Barang::count();
            $data['sedang_dipinjam'] = Peminjaman::where('status', 'dipinjam')->count();
        } else {
            // Untuk User Biasa (Siswa/Guru): Total barang tersedia & yang MEREKA pinjam
            $data['total_barang'] = Barang::where('stok', '>', 0)->count();
            $data['sedang_dipinjam'] = Peminjaman::where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->count();
        }

        $data['barang_rusak'] = Barang::where('kondisi', 'rusak')->count();
        $data['stok_menipis'] = Barang::where('stok', '<', 5)->count();
        $data['total_kategori'] = Kategori::count();

        // Biaya Pemeliharaan 6 Bulan Terakhir (Khusus Ka.Sek & Admin)
        $data['maintenance_monthly'] = collect();
        $data['maintenance_by_location'] = collect();
        $data['total_biaya_bulan_ini'] = 0;

        if ($user->hasAnyRole(['Super Admin', 'Kepala Sekolah'])) {
            $data['maintenance_monthly'] = Pemeliharaan::select(
                DB::raw('SUM(biaya) as total'),
                DB::raw("DATE_FORMAT(tanggal_servis, '%M') as month"),
                DB::raw("MONTH(tanggal_servis) as m_num")
            )
            ->where('tanggal_servis', '>=', now()->subMonths(6))
            ->groupBy('month', 'm_num')
            ->orderBy('m_num', 'asc')
            ->get();

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
        }
        
        // Data untuk Admin/Petugas
        $data['reservasi_pending'] = collect();
        $data['jatuh_tempo'] = collect();
        $data['bhp_kritis'] = collect();
        $data['laporan_pending'] = collect();
        $data['maintenance_reminders'] = collect();

        if ($user->hasAnyRole(['Super Admin', 'Petugas Sarpras'])) {
            $data['reservasi_pending'] = Reservasi::with(['user', 'ruangan'])
                ->where('status', 'pending')
                ->latest()
                ->get();

            $data['jatuh_tempo'] = Peminjaman::with(['barang', 'user'])
                ->where('status', 'dipinjam')
                ->where('tanggal_pinjam', '<', now()->subDays(3))
                ->get();

            $data['bhp_kritis'] = Barang::where('tipe', 'bhp')
                ->whereColumn('stok', '<=', 'min_stok')
                ->get();

            $data['laporan_pending'] = LaporanKerusakan::with(['barang', 'user'])
                ->where('status', 'pending')
                ->latest()
                ->get();

            $data['maintenance_reminders'] = Barang::where('tipe', 'aset')
                ->whereNotNull('tgl_servis_berikutnya')
                ->where('tgl_servis_berikutnya', '<=', today()->addDays(7))
                ->orderBy('tgl_servis_berikutnya', 'asc')
                ->get();
        }

        // Peminjaman terbaru (Filtered untuk user biasa)
        $query_pjm = Peminjaman::with(['barang', 'user']);
        if (!$user->hasAnyRole(['Super Admin', 'Petugas Sarpras', 'Kepala Sekolah'])) {
            $query_pjm->where('user_id', $user->id);
        }

        $data['peminjaman_terbaru'] = $query_pjm->latest()->take(5)->get();

        return view('dashboard', $data);
    }
}
