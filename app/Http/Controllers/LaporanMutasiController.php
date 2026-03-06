<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PenggunaanBhp;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanMutasiController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->year ?? date('Y');
        
        // Dapatkan data mutasi barang
        $barangs = Barang::with('kategori')->get()->map(function($barang) use ($year) {
            // Barang Masuk (berdasarkan created_at di tahun terpilih)
            $masuk = ($barang->created_at->year == $year) ? $barang->stok : 0; 
            
            // Barang Keluar (BHP)
            $keluar_bhp = PenggunaanBhp::where('barang_id', $barang->id)
                ->whereYear('tanggal', $year)
                ->sum('jumlah');
                
            // Barang Rusak (Estimasi dari riwayat peminjaman yang kembali rusak di tahun ini)
            $rusak = Peminjaman::where('barang_id', $barang->id)
                ->where('kondisi_kembali', 'rusak')
                ->whereYear('tanggal_kembali', $year)
                ->count();
                
            return [
                'kode' => $barang->kode_barang,
                'nama' => $barang->nama_barang,
                'kategori' => $barang->kategori->nama_kategori,
                'satuan' => $barang->satuan,
                'masuk' => $masuk,
                'keluar' => $keluar_bhp,
                'rusak' => $rusak,
                'stok_akhir' => $barang->stok
            ];
        });

        return view('laporan.mutasi', compact('barangs', 'year'));
    }

    public function print(Request $request)
    {
        $year = $request->year ?? date('Y');
        $barangs = Barang::with('kategori')->get()->map(function($barang) use ($year) {
            $masuk = ($barang->created_at->year == $year) ? $barang->stok : 0; 
            $keluar_bhp = PenggunaanBhp::where('barang_id', $barang->id)
                ->whereYear('tanggal', $year)
                ->sum('jumlah');
            $rusak = Peminjaman::where('barang_id', $barang->id)
                ->where('kondisi_kembali', 'rusak')
                ->whereYear('tanggal_kembali', $year)
                ->count();
                
            return (object)[
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
                'nama_kategori' => $barang->kategori->nama_kategori,
                'satuan' => $barang->satuan,
                'masuk' => $masuk,
                'keluar' => $keluar_bhp,
                'rusak' => $rusak,
                'stok' => $barang->stok
            ];
        });

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.mutasi_pdf', compact('barangs', 'year'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('Laporan-Mutasi-'.$year.'.pdf');
    }
}
