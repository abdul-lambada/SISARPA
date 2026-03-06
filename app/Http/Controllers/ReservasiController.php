<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class ReservasiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Reservasi::with(['user', 'ruangan'])->latest();

            // Guru hanya bisa melihat data mereka sendiri kecuali admin
            if (!Auth::user()->hasAnyRole(['Super Admin', 'Petugas Sarpras'])) {
                $query->where('user_id', Auth::id());
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nama_user', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('nama_ruangan', function ($row) {
                    return $row->ruangan->nama_ruangan;
                })
                ->addColumn('waktu', function ($row) {
                    return $row->tanggal . ' (' . substr($row->jam_mulai, 0, 5) . ' - ' . substr($row->jam_selesai, 0, 5) . ')';
                })
                ->addColumn('status_badge', function ($row) {
                    $badges = [
                        'pending' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        'selesai' => 'primary'
                    ];
                    return '<span class="badge badge-' . $badges[$row->status] . '">' . strtoupper($row->status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (Auth::user()->hasAnyRole(['Super Admin', 'Petugas Sarpras']) && $row->status == 'pending') {
                        $btn .= '<button onclick="updateStatus(' . $row->id . ', \'disetujui\')" class="btn btn-success btn-sm"><i class="fas fa-check"></i></button> ';
                        $btn .= '<button onclick="updateStatus(' . $row->id . ', \'ditolak\')" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button> ';
                    }
                    if ($row->status == 'pending' && $row->user_id == Auth::id()) {
                        $btn .= '<button type="button" onclick="confirmDelete(' . $row->id . ', \'delete-form-' . $row->id . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                        $btn .= '<form id="delete-form-' . $row->id . '" action="' . route('reservasi.destroy', $row->id) . '" method="POST" style="display:none;">' . csrf_field() . method_field('DELETE') . '</form>';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'status_badge'])
                ->make(true);
        }
        return view('reservasi.index');
    }

    public function create()
    {
        $ruangans = Ruangan::where('status', 'tersedia')->get();
        return view('reservasi.create', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruangan_id' => 'required|exists:ruangans,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'keperluan' => 'required|string',
        ]);

        // CEK BENTROK (CONFLIC CHECK)
        $conflict = Reservasi::where('ruangan_id', $request->ruangan_id)
            ->where('tanggal', $request->tanggal)
            ->whereIn('status', ['pending', 'disetujui'])
            ->where(function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('jam_mulai', '>=', $request->jam_mulai)
                        ->where('jam_mulai', '<', $request->jam_selesai);
                })->orWhere(function ($q) use ($request) {
                    $q->where('jam_selesai', '>', $request->jam_mulai)
                        ->where('jam_selesai', '<=', $request->jam_selesai);
                })->orWhere(function ($q) use ($request) {
                    $q->where('jam_mulai', '<=', $request->jam_mulai)
                        ->where('jam_selesai', '>=', $request->jam_selesai);
                });
            })->first();

        if ($conflict) {
            return back()->with('error', 'Ruangan sudah dibooking pada jam tersebut oleh ' . $conflict->user->name . ' (Status: ' . $conflict->status . ')');
        }

        Reservasi::create([
            'user_id' => Auth::id(),
            'ruangan_id' => $request->ruangan_id,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keperluan' => $request->keperluan,
            'status' => 'pending'
        ]);

        return redirect()->route('reservasi.index')->with('success', 'Permintaan reservasi berhasil diajukan.');
    }

    public function updateStatus(Request $request, $id)
    {
        $reservasi = Reservasi::findOrFail($id);
        $reservasi->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan
        ]);

        return response()->json(['success' => true, 'message' => 'Status reservasi diperbarui.']);
    }

    public function destroy(Reservasi $reservasi)
    {
        if ($reservasi->status != 'pending') {
            return back()->with('error', 'Hanya reservasi pending yang bisa dibatalkan.');
        }
        $reservasi->delete();
        return redirect()->route('reservasi.index')->with('success', 'Reservasi dibatalkan.');
    }
}
