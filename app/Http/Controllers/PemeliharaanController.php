<?php

namespace App\Http\Controllers;

use App\Models\Pemeliharaan;
use App\Models\Barang;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PemeliharaanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pemeliharaan::with('barang')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_barang', function ($row) {
                    return $row->barang->nama_barang;
                })
                ->addColumn('biaya_format', function ($row) {
                    return 'Rp ' . number_format($row->biaya, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('pemeliharaan.edit', $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> ';
                    $btn .= '<button type="button" onclick="confirmDelete(' . $row->id . ', \'delete-form-' . $row->id . '\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                    $btn .= '<form id="delete-form-' . $row->id . '" action="' . route('pemeliharaan.destroy', $row->id) . '" method="POST" style="display:none;">' . csrf_field() . method_field('DELETE') . '</form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pemeliharaan.index');
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('pemeliharaan.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'tanggal_servis' => 'required|date',
            'deskripsi' => 'required|string',
            'biaya' => 'required|numeric|min:0',
            'status' => 'required|string',
        ]);

        Pemeliharaan::create($request->all());

        return redirect()->route('pemeliharaan.index')->with('success', 'Data pemeliharaan ditambahkan.');
    }

    public function edit(Pemeliharaan $pemeliharaan)
    {
        $barangs = Barang::all();
        return view('pemeliharaan.edit', compact('pemeliharaan', 'barangs'));
    }

    public function update(Request $request, Pemeliharaan $pemeliharaan)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'tanggal_servis' => 'required|date',
            'deskripsi' => 'required|string',
            'biaya' => 'required|numeric|min:0',
            'status' => 'required|string',
        ]);

        $pemeliharaan->update($request->all());

        return redirect()->route('pemeliharaan.index')->with('success', 'Data pemeliharaan diperbarui.');
    }

    public function destroy(Pemeliharaan $pemeliharaan)
    {
        $pemeliharaan->delete();
        return redirect()->route('pemeliharaan.index')->with('success', 'Data pemeliharaan dihapus.');
    }

    public function analysis()
    {
        // 1. Tren Biaya Bulanan (6 bulan terakhir)
        $monthlyCosts = Pemeliharaan::selectRaw('SUM(biaya) as total, DATE_FORMAT(tanggal_servis, "%M %Y") as month, MONTH(tanggal_servis) as m')
            ->groupBy('month', 'm')
            ->orderBy('tanggal_servis', 'asc')
            ->take(6)
            ->get();

        // 2. Biaya Per Kategori
        $categoryCosts = Pemeliharaan::join('barang', 'pemeliharaan.barang_id', '=', 'barang.id')
            ->join('kategori', 'barang.kategori_id', '=', 'kategori.id')
            ->selectRaw('SUM(pemeliharaan.biaya) as total, kategori.nama_kategori')
            ->groupBy('kategori.nama_kategori')
            ->get();

        // 3. Top Aset Paling Sering/Mahal Servis (Identifying "Old" Assets)
        $topAssets = Pemeliharaan::with('barang')
            ->selectRaw('SUM(biaya) as total_biaya, COUNT(*) as jumlah_servis, barang_id')
            ->groupBy('barang_id')
            ->orderBy('total_biaya', 'desc')
            ->take(5)
            ->get();

        return view('pemeliharaan.analysis', compact('monthlyCosts', 'categoryCosts', 'topAssets'));
    }
}
