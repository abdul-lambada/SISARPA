<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kategori::latest()->get(); // This line should ideally be Kategori::withTrashed()->latest()->get(); if you intend to show trashed items in the main index.
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->trashed()) {
                        $btn = '<button onclick="restoreData(' . $row->id . ')" class="btn btn-success btn-sm"><i class="fas fa-undo"></i> Restore</button> ';
                        $btn .= '<button onclick="forceDelete(' . $row->id . ')" class="btn btn-danger btn-sm"><i class="fas fa-times-circle"></i> Permanent</button>';
                        return $btn;
                    }
                    $btn = '<a href="' . route('kategori.edit', $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> ';
                    $btn .= '<button type="button" onclick="confirmDelete(' . $row->id . ', \'delete-form-' . $row->id . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                    $btn .= '<form id="delete-form-' . $row->id . '" action="' . route('kategori.destroy', $row->id) . '" method="POST" style="display:none;">' . csrf_field() . method_field('DELETE') . '</form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('kategori.index');
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        Kategori::create($request->all());

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $kategori->update($request->all());

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dipindah ke tempat sampah.');
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $data = Kategori::onlyTrashed()->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button onclick="restoreData(' . $row->id . ')" class="btn btn-success btn-sm"><i class="fas fa-undo"></i> Restore</button> ';
                    $btn .= '<button onclick="forceDelete(' . $row->id . ')" class="btn btn-danger btn-sm"><i class="fas fa-times-circle"></i> Permanent</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('kategori.trash');
    }

    public function restore($id)
    {
        $kategori = Kategori::withTrashed()->findOrFail($id);
        $kategori->restore();
        return response()->json(['success' => 'Kategori berhasil dipulihkan.']);
    }

    public function forceDelete($id)
    {
        $kategori = Kategori::withTrashed()->findOrFail($id);
        $kategori->forceDelete();
        return response()->json(['success' => 'Kategori dihapus secara permanen.']);
    }
}
