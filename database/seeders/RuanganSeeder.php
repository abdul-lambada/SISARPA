<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruangan;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $ruangans = [
            [
                'kode_ruangan' => 'LAB-01',
                'nama_ruangan' => 'Laboratorium Komputer 1',
                'kapasitas' => 40,
                'fasilitas' => 'AC, 40 PC Client, 1 Server, Proyektor, Sound System',
                'status' => 'tersedia'
            ],
            [
                'kode_ruangan' => 'LAB-02',
                'nama_ruangan' => 'Laboratorium Bahasa',
                'kapasitas' => 30,
                'fasilitas' => 'Headset, AC, Proyektor',
                'status' => 'tersedia'
            ],
            [
                'kode_ruangan' => 'AULA-01',
                'nama_ruangan' => 'Aula Utama (Hall)',
                'kapasitas' => 500,
                'fasilitas' => 'Panggung, Sound System Besar, 500 Kursi, AC Central',
                'status' => 'tersedia'
            ],
            [
                'kode_ruangan' => 'RAPAT-01',
                'nama_ruangan' => 'Ruang Rapat Guru',
                'kapasitas' => 25,
                'fasilitas' => 'Meja Oval, AC, Smart TV, Whiteboard',
                'status' => 'tersedia'
            ],
        ];

        foreach ($ruangans as $r) {
            Ruangan::firstOrCreate(['kode_ruangan' => $r['kode_ruangan']], $r);
        }
    }
}
