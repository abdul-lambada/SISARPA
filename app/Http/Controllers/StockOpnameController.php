<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\Barang;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LogHelper;

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
            'jumlah' => 'nullable|integer|min:1',
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
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
        
        if ($request->hasFile('foto_bukti')) {
            $path = $request->file('foto_bukti')->store('stock_opname', 'public');
            $detail->foto_bukti = $path;
        }

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
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $opname = StockOpname::with('details.barang')->findOrFail($id);
            
            if ($opname->status == 'selesai') {
                return redirect()->route('stock-opname.show', $id)->with('error', 'Opname sudah pernah difinalisasi.');
            }

            // Sync ke stok barang sebenarnya
            foreach ($opname->details as $detail) {
                $barang = $detail->barang;
                if ($barang) {
                    $barang->update([
                        'stok' => $detail->jumlah_fisik
                    ]);
                }
            }

            $opname->status = 'selesai';
            $opname->save();

            \Illuminate\Support\Facades\DB::commit();
            
            \App\Helpers\LogHelper::log('Finalisasi Stock Opname & Sinkronisasi Stok Ruangan ' . $opname->ruangan, $opname);

            return redirect()->route('stock-opname.show', $id)->with('success', 'Stock Opname telah difinalisasi. Stok sistem telah diperbarui sesuai hasil fisik.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Gagal finalisasi: ' . $e->getMessage());
        }
    }
}
