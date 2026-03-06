<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Exports\BarangExport;
use Maatwebsite\Excel\Facades\Excel;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Barang::with('kategori')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_kategori', function ($row) {
                    return $row->kategori->nama_kategori;
                })
                ->addColumn('tipe_badge', function ($row) {
                    $color = $row->tipe == 'aset' ? 'primary' : 'info';
                    $label = $row->tipe == 'aset' ? 'ASET TETAP' : 'BHP';
                    return '<span class="badge badge-' . $color . '">' . $label . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('barang.show', $row->id) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a> ';
                    $btn .= '<a href="' . route('barang.edit', $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> ';
                    $btn .= '<button type="button" onclick="confirmDelete(' . $row->id . ', \'delete-form-' . $row->id . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                    $btn .= '<form id="delete-form-' . $row->id . '" action="' . route('barang.destroy', $row->id) . '" method="POST" style="display:none;">' . csrf_field() . method_field('DELETE') . '</form>';
                    return $btn;
                })
                ->rawColumns(['action', 'tipe_badge'])
                ->make(true);
        }
        return view('barang.index');
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('barang.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'tipe' => 'required|in:aset,bhp',
            'kode_barang' => 'required|unique:barang,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:50',
            'merk' => 'nullable|string|max:255',
            'spesifikasi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'kondisi' => 'required|in:baik,rusak,servis',
            'stok' => 'required|integer|min:0',
            'min_stok' => 'required|integer|min:0',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto_barang')) {
            $data['foto_barang'] = $request->file('foto_barang')->store('barang', 'public');
        }

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        $qrcode = QrCode::size(200)->generate($barang->kode_barang);
        return view('barang.show', compact('barang', 'qrcode'));
    }

    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'tipe' => 'required|in:aset,bhp',
            'kode_barang' => 'required|unique:barang,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:50',
            'merk' => 'nullable|string|max:255',
            'spesifikasi' => 'nullable|string',
            'lokasi' => 'required|string|max:255',
            'kondisi' => 'required|in:baik,rusak,servis',
            'stok' => 'required|integer|min:0',
            'min_stok' => 'required|integer|min:0',
            'foto_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto_barang')) {
            if ($barang->foto_barang) {
                Storage::disk('public')->delete($barang->foto_barang);
            }
            $data['foto_barang'] = $request->file('foto_barang')->store('barang', 'public');
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->foto_barang) {
            Storage::disk('public')->delete($barang->foto_barang);
        }
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new BarangExport, 'data-barang-' . date('Y-m-d') . '.xlsx');
    }

    public function printLabels(Request $request)
    {
        $ids = $request->ids ? explode(',', $request->ids) : [];
        
        if (empty($ids)) {
            $barangs = Barang::all();
        } else {
            $barangs = Barang::whereIn('id', $ids)->get();
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('barang.labels_pdf', compact('barangs'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('label-barang.pdf');
    }
}
