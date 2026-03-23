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
        
        // Dapatkan data mutasi barang yang lebih akurat (Deep Audit Fix)
        $barangs = Barang::with('kategori')->get()->map(function($barang) use ($year) {
            // Barang Masuk (dianggap stok saat ini jika baru ditambahkan tahun ini)
            $masuk = ($barang->created_at->year == $year) ? $barang->stok : 0; 
            
            // Barang Keluar (BHP) - Terintegrasi dengan PenggunaanBhp
            $keluar_bhp = \App\Models\PenggunaanBhp::where('barang_id', $barang->id)
                ->whereYear('tanggal', $year)
                ->sum('jumlah');
                
            // Barang Rusak (INTEGRASI: Peminjaman Rusak + Laporan Kerusakan yang Terkonfirmasi)
            $rusak_peminjaman = \App\Models\Peminjaman::where('barang_id', $barang->id)
                ->where('kondisi_kembali', 'rusak')
                ->whereYear('tanggal_kembali', $year)
                ->count();

            $rusak_laporan = \App\Models\LaporanKerusakan::where('barang_id', $barang->id)
                ->where('status', 'selesai')
                ->where('deskripsi_kerusakan', 'like', '%total%') // Opsional: atau cek kondisi akhir
                ->whereYear('updated_at', $year)
                ->count();
                
            $total_rusak = $rusak_peminjaman + $rusak_laporan;
                
            return [
                'kode' => $barang->kode_barang,
                'nama' => $barang->nama_barang,
                'kategori' => $barang->kategori->nama_kategori,
                'satuan' => $barang->satuan,
                'masuk' => $masuk,
                'keluar' => $keluar_bhp,
                'rusak' => ($barang->stok == 0 && $barang->kondisi == 'rusak') ? $total_rusak + 1 : $total_rusak, // Pendekatan mendalam
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
