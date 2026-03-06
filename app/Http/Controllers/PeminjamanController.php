<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Peminjaman::with(['barang', 'user']);

            if (!auth()->user()->hasAnyRole(['Super Admin', 'Petugas Sarpras'])) {
                $query->where('user_id', auth()->id());
            }

            $data = $query->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_barang', function ($row) {
                    return $row->barang->nama_barang;
                })
                ->addColumn('nama_peminjam', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('peminjaman.show', $row->id) . '" class="btn btn-info btn-sm" title="Bukti Digital"><i class="fas fa-file-invoice"></i></a> ';
                    $btn .= '<a href="' . route('peminjaman.print-bast', $row->id) . '" target="_blank" class="btn btn-dark btn-sm" title="Cetak BAST PDF"><i class="fas fa-print"></i></a> ';
                    
                    if ($row->status == 'dipinjam' && auth()->user()->hasAnyRole(['Super Admin', 'Petugas Sarpras'])) {
                        $btn .= '<button type="button" onclick="kembalikanBarang(' . $row->id . ')" class="btn btn-success btn-sm"><i class="fas fa-undo"></i></button> ';
                    }

                    if (auth()->user()->hasAnyRole(['Super Admin', 'Petugas Sarpras'])) {
                        $btn .= '<button type="button" onclick="confirmDelete(' . $row->id . ', \'delete-form-' . $row->id . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                        $btn .= '<form id="delete-form-' . $row->id . '" action="' . route('peminjaman.destroy', $row->id) . '" method="POST" style="display:none;">' . csrf_field() . method_field('DELETE') . '</form>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('peminjaman.index');
    }

    public function show(Peminjaman $peminjaman)
    {
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function create()
    {
        $barangs = Barang::where('stok', '>', 0)->where('kondisi', 'baik')->get();
        $users = User::role('User')->get();
        if (auth()->user()->hasRole('User')) {
            $users = collect([auth()->user()]);
        }
        return view('peminjaman.create', compact('barangs', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'user_id' => 'required|exists:users,id',
            'tanggal_pinjam' => 'required|date',
            'catatan' => 'nullable|string',
            'tanda_tangan' => 'required|string', // Pastikan tanda tangan ada
        ]);

        $barang = Barang::findOrFail($request->barang_id);
        if ($barang->tipe !== 'aset') {
            return back()->with('error', 'Hanya barang bertipe Aset Tetap yang bisa dipinjam.');
        }

        if ($barang->stok <= 0) {
            return back()->with('error', 'Stok barang habis.');
        }

        Peminjaman::create([
            'barang_id' => $request->barang_id,
            'user_id' => $request->user_id,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'status' => 'dipinjam',
            'catatan' => $request->catatan,
            'tanda_tangan' => $request->tanda_tangan,
        ]);

        $barang->decrement('stok');

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function kembalikan(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status == 'dikembalikan') {
            return response()->json(['error' => 'Barang sudah dikembalikan.'], 400);
        }

        $peminjaman->update([
            'tanggal_kembali' => now(),
            'status' => 'dikembalikan',
            'kondisi_kembali' => $request->kondisi // 'baik' or 'rusak'
        ]);

        $barang = $peminjaman->barang;
        $barang->increment('stok');
        
        // Jika dikembalikan dalam kondisi rusak, ubah status barang
        if ($request->kondisi == 'rusak') {
            $barang->update(['kondisi' => 'rusak']);
        }

        return response()->json(['success' => 'Barang berhasil dikembalikan.']);
    }

    public function destroy(Peminjaman $peminjaman)
    {
        if ($peminjaman->status == 'dipinjam') {
            $peminjaman->barang->increment('stok');
        }
        $peminjaman->delete();
        return redirect()->route('peminjaman.index')->with('success', 'Riwayat peminjaman dihapus.');
    }

    public function printBast(Peminjaman $peminjaman)
    {
        $peminjaman->load(['barang', 'user']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('peminjaman.bast_pdf', compact('peminjaman'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('BAST-' . $peminjaman->id . '.pdf');
    }
}
