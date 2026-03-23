<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SyncBukuIndukUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:buku-induk-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users from Buku Induk database to SISARPA';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Menghubungkan ke database Buku Induk...');

        try {
            DB::connection('buku_induk')->getPdo();
        } catch (\Exception $e) {
            $this->error('❌ Gagal terhubung ke database Buku Induk: ' . $e->getMessage());
            return;
        }

        // 1. Sinkronisasi Staff/Admin dari tabel `pengguna`
        $this->info('📁 Sinkronisasi data Pegawai/Admin...');
        $pengguna = DB::connection('buku_induk')->table('pengguna')->get();
        $staffCount = 0;
        
        foreach ($pengguna as $p) {
            $user = User::updateOrCreate(
                ['username' => $p->nama_pengguna],
                [
                    'name' => $p->nama_lengkap,
                    'email' => $p->nama_pengguna . '@sisarpa.com', // Email default jika tidak ada
                    'password' => $p->kata_sandi, // Hasing kompatibel (password_hash)
                    'jenis_user' => 'staf',
                    'no_induk' => $p->nip ?: '-',
                ]
            );

            // Assign Role
            if ($p->peran == 'admin') {
                $user->assignRole('Super Admin');
            } else {
                $user->assignRole('Petugas Sarpras');
            }
            $staffCount++;
        }
        $this->info("✅ Berhasil sinkronisasi $staffCount Pegawai.");

        // 2. Sinkronisasi Siswa dari tabel `siswa`
        $this->info('📁 Sinkronisasi data Siswa...');
        $siswa = DB::connection('buku_induk')->table('siswa')->get();
        $siswaCount = 0;

        foreach ($siswa as $s) {
            $username = $s->nisn ?: $s->nis;
            if (!$username) continue;

            $user = User::firstOrNew(['username' => $username]);
            $user->fill([
                'name' => $s->nama_lengkap,
                'email' => $s->email ?: $username . '@siswa.sisarpa.com',
                'jenis_user' => 'siswa',
                'no_induk' => $s->nisn ?: $s->nis,
                'kelas' => $s->kelas,
            ]);

            // Set default password hanya jika user baru dibuat (atau password masih kosong)
            if (!$user->password) {
                $user->password = Hash::make($username);
            }

            $user->save();
            $user->assignRole('User');
            $siswaCount++;
        }
        $this->info("✅ Berhasil sinkronisasi $siswaCount Siswa.");

        $this->info('🎉 Sinkronisasi Ekosistem Selesai!');
    }
}
