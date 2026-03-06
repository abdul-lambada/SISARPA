<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\Barang;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StockOpname::with('user')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('stock-opname.show', $row->id) . '" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</a> ';
                    if ($row->status == 'draft') {
                        $btn .= '<a href="' . route('stock-opname.scan', $row->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-qrcode"></i> Lanjut Scan</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('stock_opname.index');
    }

    public function create()
    {
        // Ambil daftar lokasi unik dari tabel barang
        $ruangans = Barang::distinct()->pluck('lokasi');
        return view('stock_opname.create', compact('ruangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'ruangan' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $opname = StockOpname::create([
            'tanggal' => $request->tanggal,
            'ruangan' => $request->ruangan,
            'keterangan' => $request->keterangan,
            'status' => 'draft',
            'user_id' => Auth::id(),
        ]);

        // Masukkan semua barang yang ada di ruangan tersebut ke dalam detail draft
        $barangs = Barang::where('lokasi', $request->ruangan)->get();
        foreach ($barangs as $barang) {
            StockOpnameDetail::create([
                'stock_opname_id' => $opname->id,
                'barang_id' => $barang->id,
                'jumlah_sistem' => $barang->stok,
                'jumlah_fisik' => 0,
                'selisih' => 0 - $barang->stok,
            ]);
        }

        return redirect()->route('stock-opname.scan', $opname->id)->with('success', 'Sesi opname dimulai. Silahkan scan barang.');
    }

    public function scan($id)
    {
        $opname = StockOpname::with('details.barang')->findOrFail($id);
        if ($opname->status == 'selesai') {
            return redirect()->route('stock-opname.show', $id);
        }
        return view('stock_opname.scan', compact('opname'));
    }

    public function updateScan(Request $request, $id)
    {
        $request->validate([
            'kode_barang' => 'required|exists:barang,kode_barang',
            'jumlah' => 'nullable|integer|min:1'
        ]);

        $opname = StockOpname::findOrFail($id);
        $barang = Barang::where('kode_barang', $request->kode_barang)->first();

        // Cek apakah barang ini ada dalam daftar opname ruangan ini
        $detail = StockOpnameDetail::where('stock_opname_id', $id)
            ->where('barang_id', $barang->id)
            ->first();

        if (!$detail) {
            return response()->json(['success' => false, 'message' => 'Barang ini bukan dari ruangan ' . $opname->ruangan]);
        }

        $increment = $request->jumlah ?? 1;
        $detail->increment('jumlah_fisik', $increment);
        $detail->selisih = $detail->jumlah_fisik - $detail->jumlah_sistem;
        $detail->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil scan (' . $increment . ' unit): ' . $barang->nama_barang,
            'data' => [
                'nama' => $barang->nama_barang,
                'fisik' => $detail->jumlah_fisik,
                'selisih' => $detail->selisih
            ]
        ]);
    }

    public function show($id)
    {
        $opname = StockOpname::with(['details.barang', 'user'])->findOrFail($id);
        return view('stock_opname.show', compact('opname'));
    }

    public function finalize($id)
    {
        $opname = StockOpname::findOrFail($id);
        $opname->status = 'selesai';
        $opname->save();

        return redirect()->route('stock-opname.show', $id)->with('success', 'Stock Opname telah difinalisasi.');
    }
}
