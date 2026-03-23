<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\RolePermissionSeeder;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_user_can_request_borrowing()
    {
        $user = User::whereHas('roles', function($q){ $q->where('name', 'User'); })->first();
        $kategori = Kategori::create(['nama_kategori' => 'Alat Praktik']);
        $barang = Barang::create([
            'kategori_id' => $kategori->id,
            'tipe' => 'aset',
            'kode_barang' => 'AP-001',
            'nama_barang' => 'Multimeter',
            'lokasi' => 'Gudang Bengkel',
            'kondisi' => 'baik',
            'stok' => 5,
            'min_stok' => 1
        ]);

        $response = $this->actingAs($user)->post(route('peminjaman.store'), [
            'barang_id' => $barang->id,
            'user_id' => $user->id,
            'tanggal_pinjam' => now()->format('Y-m-d'),
            'catatan' => 'Praktik Elektronika',
            'tanda_tangan' => 'data:image/png;base64,fake-signature-data'
        ]);

        $response->assertRedirect(route('peminjaman.index'));
        $this->assertDatabaseHas('peminjaman', [
            'user_id' => $user->id,
            'barang_id' => $barang->id,
            'status' => 'dipinjam'
        ]);
        
        // Check stock decreased
        $this->assertEquals(4, $barang->fresh()->stok);
    }

    public function test_admin_can_generate_bast_pdf()
    {
        $admin = User::whereHas('roles', function($q){ $q->where('name', 'Super Admin'); })->first();
        $kategori = Kategori::create(['nama_kategori' => 'Aset']);
        $barang = Barang::create([
            'kategori_id' => $kategori->id,
            'tipe' => 'aset',
            'kode_barang' => 'AS-002',
            'nama_barang' => 'Kamera Canon',
            'lokasi' => 'Lab Multimedia',
            'kondisi' => 'baik',
            'stok' => 1,
            'min_stok' => 0
        ]);

        $peminjaman = Peminjaman::create([
            'user_id' => $admin->id,
            'barang_id' => $barang->id,
            'jumlah' => 1,
            'tanggal_pinjam' => now(),
            'status' => 'dipinjam',
            'keperluan' => 'Dokumentasi Acara',
            'tanda_tangan' => 'fake-sig'
        ]);

        $response = $this->actingAs($admin)->get(route('peminjaman.print-bast', $peminjaman->id));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_mutation_report_viewable()
    {
        $admin = User::whereHas('roles', function($q){ $q->where('name', 'Super Admin'); })->first();
        $kategori = Kategori::create(['nama_kategori' => 'BHP']);
        $barang = Barang::create([
            'kategori_id' => $kategori->id,
            'tipe' => 'bhp',
            'kode_barang' => 'BHP-001',
            'nama_barang' => 'Kertas A4',
            'lokasi' => 'Gudang',
            'kondisi' => 'baik',
            'stok' => 20,
            'min_stok' => 5
        ]);

        $response = $this->actingAs($admin)->get(route('laporan.mutasi', ['year' => date('Y')]));
        $response->assertStatus(200);
        $response->assertSee('Kertas A4');
    }
}
