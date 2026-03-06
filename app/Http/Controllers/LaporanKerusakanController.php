<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanKerusakanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = LaporanKerusakan::with(['barang', 'user']);
            
            if (!Auth::user()->hasAnyRole(['Super Admin', 'Petugas Sarpras'])) {
                $query->where('user_id', Auth::id());
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nama_barang', function ($row) {
                    return $row->barang->nama_barang . ' ('.$row->barang->kode_barang.')';
                })
                ->addColumn('pelapor', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('status_badge', function ($row) {
                    $badges = [
                        'pending' => 'warning',
                        'diproses' => 'info',
                        'selesai' => 'success',
                        'ditolak' => 'danger'
                    ];
                    return '<span class="badge badge-'.$badges[$row->status].'">'.strtoupper($row->status).'</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (Auth::user()->hasAnyRole(['Super Admin', 'Petugas Sarpras'])) {
                        $btn .= '<button onclick="updateStatus('.$row->id.')" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Respon</button> ';
                    }
                    if ($row->status == 'pending' && $row->user_id == Auth::id()) {
                        $btn .= '<button onclick="deleteReport('.$row->id.')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                    }
                    return $btn;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }
        return view('laporan_kerusakan.index');
    }

    public function create(Request $request)
    {
        $barang = null;
        if ($request->has('kode_barang')) {
            $barang = Barang::where('kode_barang', $request->kode_barang)->first();
        }
        return view('laporan_kerusakan.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'deskripsi_kerusakan' => 'required',
            'foto_kerusakan' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        if ($request->hasFile('foto_kerusakan')) {
            $data['foto_kerusakan'] = $request->file('foto_kerusakan')->store('laporan_kerusakan', 'public');
        }

        LaporanKerusakan::create($data);

        return redirect()->route('laporan-kerusakan.index')->with('success', 'Laporan kerusakan berhasil dikirim. Tim Sarpras akan segera mengecek.');
    }

    public function updateStatus(Request $request, $id)
    {
        $report = LaporanKerusakan::findOrFail($id);
        $report->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan
        ]);

        return response()->json(['success' => true, 'message' => 'Status laporan diperbarui.']);
    }

    public function destroy($id)
    {
        $report = LaporanKerusakan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        if ($report->status !== 'pending') {
            return back()->with('error', 'Laporan yang sedang diproses tidak bisa dihapus.');
        }
        $report->delete();
        return redirect()->route('laporan-kerusakan.index')->with('success', 'Laporan dibatalkan.');
    }
}
