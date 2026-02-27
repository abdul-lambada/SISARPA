<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Pemeliharaan;
use App\Models\User;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kategoris
        $kategoris = [
            ['nama_kategori' => 'Elektronik'],
            ['nama_kategori' => 'Perabot Kantor'],
            ['nama_kategori' => 'Alat Peraga (Lab)'],
            ['nama_kategori' => 'Peralatan Olahraga'],
            ['nama_kategori' => 'Stationery'],
        ];

        foreach ($kategoris as $cat) {
            Kategori::firstOrCreate($cat);
        }

        $catElektronik = Kategori::where('nama_kategori', 'Elektronik')->first();
        $catPerabot = Kategori::where('nama_kategori', 'Perabot Kantor')->first();
        $catAlatPeraga = Kategori::where('nama_kategori', 'Alat Peraga (Lab)')->first();

        // 2. Barangs
        $barangs = [
            [
                'kategori_id' => $catElektronik->id,
                'kode_barang' => 'ELC-001',
                'nama_barang' => 'Laptop Dell Latitude 3420',
                'merk' => 'Dell',
                'spesifikasi' => 'Core i5 Gen 11, RAM 8GB, SSD 256GB',
                'lokasi' => 'Lab Komputer 1',
                'kondisi' => 'baik',
                'stok' => 10,
            ],
            [
                'kategori_id' => $catElektronik->id,
                'kode_barang' => 'ELC-002',
                'nama_barang' => 'Proyektor Epson EB-X400',
                'merk' => 'Epson',
                'spesifikasi' => '3300 Lumens, XGA Resolution',
                'lokasi' => 'Gudang Sarpras',
                'kondisi' => 'baik',
                'stok' => 5,
            ],
            [
                'kategori_id' => $catPerabot->id,
                'kode_barang' => 'FRN-001',
                'nama_barang' => 'Meja Guru Jati',
                'merk' => 'Custom',
                'spesifikasi' => 'Kayu Jati, Ukuran 120x60cm',
                'lokasi' => 'Ruang Guru',
                'kondisi' => 'baik',
                'stok' => 20,
            ],
            [
                'kategori_id' => $catAlatPeraga->id,
                'kode_barang' => 'LAB-001',
                'nama_barang' => 'Mikroskop Binokuler',
                'merk' => 'Olympus',
                'spesifikasi' => 'Pembesaran 1000x',
                'lokasi' => 'Lab Biologi',
                'kondisi' => 'rusak',
                'stok' => 2,
            ],
            [
                'kategori_id' => $catElektronik->id,
                'kode_barang' => 'ELC-003',
                'nama_barang' => 'Speaker Wireless Portable',
                'merk' => 'JBL',
                'spesifikasi' => 'Battery life 12h, Bluetooth 5.1',
                'lokasi' => 'Gudang Sarpras',
                'kondisi' => 'servis',
                'stok' => 1,
            ],
        ];

        foreach ($barangs as $b) {
            Barang::firstOrCreate(['kode_barang' => $b['kode_barang']], $b);
        }

        // 3. Admin & User References
        $admin = User::where('email', 'admin@sisarpa.com')->first();
        $siswa = User::where('email', 'siswa@sisarpa.com')->first();
        $laptop = Barang::where('kode_barang', 'ELC-001')->first();
        $proyektor = Barang::where('kode_barang', 'ELC-002')->first();

        // 4. Peminjamans
        if ($siswa && $laptop && $proyektor) {
            Peminjaman::firstOrCreate(
                ['catatan' => 'Project akhir semester'],
                [
                    'barang_id' => $laptop->id,
                    'user_id' => $siswa->id,
                    'tanggal_pinjam' => Carbon::now()->subDays(5),
                    'tanggal_kembali' => Carbon::now()->subDays(2),
                    'status' => 'dikembalikan',
                ]
            );

            Peminjaman::firstOrCreate(
                ['catatan' => 'Presentasi di Aula'],
                [
                    'barang_id' => $proyektor->id,
                    'user_id' => $siswa->id,
                    'tanggal_pinjam' => Carbon::now()->subDay(),
                    'status' => 'dipinjam',
                ]
            );
        }

        // 5. Pemeliharaans
        $mikroskop = Barang::where('kode_barang', 'LAB-001')->first();
        $speaker = Barang::where('kode_barang', 'ELC-003')->first();

        if ($mikroskop) {
            Pemeliharaan::create([
                'barang_id' => $mikroskop->id,
                'tanggal_servis' => Carbon::now()->subMonth(),
                'deskripsi' => 'Penggantian lensa objektif',
                'biaya' => 500000,
                'status' => 'Selesai',
            ]);
        }

        if ($speaker) {
            Pemeliharaan::create([
                'barang_id' => $speaker->id,
                'tanggal_servis' => Carbon::now()->subDays(2),
                'deskripsi' => 'Perbaikan modul Bluetooth',
                'biaya' => 150000,
                'status' => 'Proses',
            ]);
        }
    }
}
