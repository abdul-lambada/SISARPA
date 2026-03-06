<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ActivityLog::with('user')->latest();
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user_name', function ($row) {
                    return $row->user ? $row->user->name : 'System';
                })
                ->addColumn('time', function ($row) {
                    return $row->created_at->format('d/m/Y H:i:s');
                })
                ->addColumn('details', function ($row) {
                    if ($row->properties) {
                        return '<button class="btn btn-xs btn-info" onclick="viewDetails('.$row->id.')">Detail</button>';
                    }
                    return '-';
                })
                ->rawColumns(['details'])
                ->make(true);
        }
        return view('activity_log.index');
    }

    public function show($id)
    {
        $log = ActivityLog::findOrFail($id);
        return response()->json($log->properties);
    }
}
