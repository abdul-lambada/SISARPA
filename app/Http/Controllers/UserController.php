<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\UserImport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('roles', function($row){
                    return $row->getRoleNames()->map(function($role){
                        return '<span class="badge badge-info">'.$role.'</span>';
                    })->implode(' ');
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('users.edit', $row->id).'" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> ';
                    if ($row->id != auth()->id()) {
                        $btn .= '<button type="button" onclick="confirmDelete('.$row->id.', \'delete-form-'.$row->id.'\')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>';
                        $btn .= '<form id="delete-form-'.$row->id.'" action="'.route('users.destroy', $row->id).'" method="POST" style="display:none;">'.csrf_field().method_field('DELETE').'</form>';
                    }
                    return $btn;
                })
                ->rawColumns(['roles', 'action'])
                ->make(true);
        }
        return view('users.index');
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'no_induk' => $request->no_induk,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jenis_user' => $request->jenis_user,
            'kelas' => $request->kelas,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,'.$user->id,
            'email' => 'required|string|email|unique:users,email,'.$user->id,
            'role' => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'no_induk' => $request->no_induk,
            'email' => $request->email,
            'jenis_user' => $request->jenis_user,
            'kelas' => $request->kelas,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus diri sendiri.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new UserImport, $request->file('file'));
            return redirect()->route('users.index')->with('success', 'Data user berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}
