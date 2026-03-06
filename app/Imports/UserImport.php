<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Illuminate\Support\Facades\Hash;

class UserImport implements ToModel, WithHeadingRow, WithUpserts
{
    /**
     * Tentukan kolom mana yang menjadi kunci unik untuk pengecekan (Upsert)
     * Dalam hal ini, kita menggunakan 'username' (yang berisi NISN/NIP)
     */
    public function uniqueBy()
    {
        return 'username';
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip jika kolom nama atau email tidak ada
        if (!isset($row['nama']) || !isset($row['email'])) {
            return null;
        }

        $username = $row['username'] ?? $row['nisn'] ?? $row['nip'] ?? $row['email'];
        
        // Cari user yang sudah ada berdasarkan username
        $existingUser = User::where('username', $username)->first();

        $data = [
            'name'      => $row['nama'],
            'no_induk'  => $row['no_induk'] ?? $row['nisn'] ?? $row['nip'],
            'email'     => $row['email'],
            'jenis_user'=> strtolower($row['jenis'] ?? 'staf'),
            'kelas'     => $row['kelas'] ?? null,
        ];

        // Jika user baru atau password disediakan di Excel, maka update password
        if (!$existingUser || isset($row['password'])) {
            $data['password'] = Hash::make($row['password'] ?? 'password123');
        }

        if ($existingUser) {
            // Update data yang sudah ada
            $existingUser->update($data);
            $user = $existingUser;
        } else {
            // Buat user baru
            $data['username'] = $username;
            $user = User::create($data);
        }

        // Sinkronisasi Role berdasarkan jenis_user
        if (strtolower($row['jenis'] ?? '') == 'siswa') {
            $user->syncRoles(['User']);
        } elseif (strtolower($row['jenis'] ?? '') == 'guru' || strtolower($row['jenis'] ?? '') == 'staf') {
            // Admin biasanya tidak diimport massal, jadi default ke Petugas Sarpras
            if (!$user->hasRole('Super Admin')) {
                $user->syncRoles(['Petugas Sarpras']);
            }
        }

        return null; // Return null karena kita sudah handle create/update manual agar lebih terkontrol
    }
}
