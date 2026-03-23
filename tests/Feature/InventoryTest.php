<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Database\Seeders\RolePermissionSeeder;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_can_create_category()
    {
        $admin = User::whereHas('roles', function($q){ $q->where('name', 'Super Admin'); })->first();

        $response = $this->actingAs($admin)->post(route('kategori.store'), [
            'nama_kategori' => 'Elektronik'
        ]);

        $response->assertRedirect(route('kategori.index'));
        $this->assertDatabaseHas('kategori', ['nama_kategori' => 'Elektronik']);
    }

    public function test_admin_can_create_barang_with_maintenance()
    {
        $admin = User::whereHas('roles', function($q){ $q->where('name', 'Super Admin'); })->first();
        $kategori = Kategori::create(['nama_kategori' => 'Laptop']);

        $data = [
            'kategori_id' => $kategori->id,
            'tipe' => 'aset',
            'kode_barang' => 'LP-001',
            'nama_barang' => 'MacBook Pro',
            'satuan' => 'Pcs',
            'merk' => 'Apple',
            'lokasi' => 'Lab IT',
            'kondisi' => 'baik',
            'stok' => 10,
            'min_stok' => 2,
            'tgl_servis_berikutnya' => now()->addDays(5)->format('Y-m-d')
        ];

        $response = $this->actingAs($admin)->post(route('barang.store'), $data);

        $response->assertRedirect(route('barang.index'));
        $this->assertDatabaseHas('barang', [
            'nama_barang' => 'MacBook Pro',
            'tgl_servis_berikutnya' => $data['tgl_servis_berikutnya']
        ]);

        // Check Audit Log
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'activity' => 'Menambahkan barang baru: MacBook Pro'
        ]);
    }

    public function test_maintenance_reminder_appears_on_dashboard()
    {
        $admin = User::whereHas('roles', function($q){ $q->where('name', 'Super Admin'); })->first();
        $kategori = Kategori::create(['nama_kategori' => 'Aset']);
        
        // Create item with maintenance due in 3 days
        Barang::create([
            'kategori_id' => $kategori->id,
            'tipe' => 'aset',
            'kode_barang' => 'AS-001',
            'nama_barang' => 'Proyektor',
            'lokasi' => 'Kelas 10',
            'kondisi' => 'baik',
            'stok' => 1,
            'min_stok' => 0,
            'tgl_servis_berikutnya' => now()->addDays(3)->format('Y-m-d')
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Proyektor');
        $response->assertSee(now()->addDays(3)->format('d/m/Y'));
    }
}
