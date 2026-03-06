<?php

namespace App\Http\Controllers;

use App\Models\PenggunaanBhp;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PenggunaanBhpController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PenggunaanBhp::with(['barang', 'user'])->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_barang', function ($row) {
                    return $row->barang->nama_barang;
                })
                ->addColumn('nama_penerima', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" onclick="confirmDelete(' . $row->id . ', \'delete-form-' . $row->id . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                    $btn .= '<form id="delete-form-' . $row->id . '" action="' . route('penggunaan-bhp.destroy', $row->id) . '" method="POST" style="display:none;">' . csrf_field() . method_field('DELETE') . '</form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('penggunaan_bhp.index');
    }

    public function create()
    {
        $barangs = Barang::where('tipe', 'bhp')->where('stok', '>', 0)->get();
        $users = User::all();
        return view('penggunaan_bhp.create', compact('barangs', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'user_id' => 'required|exists:users,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        if ($barang->tipe !== 'bhp') {
            return back()->with('error', 'Hanya barang bertipe BHP yang bisa didistribusikan di sini.');
        }

        if ($barang->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi. Sisa stok: ' . $barang->stok);
        }

        PenggunaanBhp::create($request->all());

        // Kurangi stok barang
        $barang->decrement('stok', $request->jumlah);

        return redirect()->route('penggunaan-bhp.index')->with('success', 'Distribusi BHP berhasil dicatat.');
    }

    public function destroy(PenggunaanBhp $penggunaanBhp)
    {
        // Kembalikan stok saat riwayat dihapus (opsional, tergantung kebijakan)
        $penggunaanBhp->barang->increment('stok', $penggunaanBhp->jumlah);
        $penggunaanBhp->delete();

        return redirect()->route('penggunaan-bhp.index')->with('success', 'Riwayat distribusi dihapus.');
    }
}
