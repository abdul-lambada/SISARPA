<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Ruangan::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status_badge', function ($row) {
                    $badges = [
                        'tersedia' => 'success',
                        'perbaikan' => 'warning',
                        'tidak_tersedia' => 'danger'
                    ];
                    $labels = [
                        'tersedia' => 'TERSEDIA',
                        'perbaikan' => 'PERBAIKAN',
                        'tidak_tersedia' => 'NON-AKTIF'
                    ];
                    return '<span class="badge badge-' . $badges[$row->status] . '">' . $labels[$row->status] . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('ruangan.edit', $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> ';
                    $btn .= '<button type="button" onclick="confirmDelete(' . $row->id . ', \'delete-form-' . $row->id . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                    $btn .= '<form id="delete-form-' . $row->id . '" action="' . route('ruangan.destroy', $row->id) . '" method="POST" style="display:none;">' . csrf_field() . method_field('DELETE') . '</form>';
                    return $btn;
                })
                ->rawColumns(['action', 'status_badge'])
                ->make(true);
        }
        return view('ruangan.index');
    }

    public function create()
    {
        return view('ruangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_ruangan' => 'required|unique:ruangans,kode_ruangan',
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,perbaikan,tidak_tersedia',
            'foto_ruangan' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        if ($request->hasFile('foto_ruangan')) {
            $data['foto_ruangan'] = $request->file('foto_ruangan')->store('ruangan', 'public');
        }

        Ruangan::create($data);
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit(Ruangan $ruangan)
    {
        return view('ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $request->validate([
            'kode_ruangan' => 'required|unique:ruangans,kode_ruangan,' . $ruangan->id,
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,perbaikan,tidak_tersedia',
            'foto_ruangan' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();
        if ($request->hasFile('foto_ruangan')) {
            if ($ruangan->foto_ruangan) {
                Storage::disk('public')->delete($ruangan->foto_ruangan);
            }
            $data['foto_ruangan'] = $request->file('foto_ruangan')->store('ruangan', 'public');
        }

        $ruangan->update($data);
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan)
    {
        $ruangan->delete();
        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus.');
    }
}
