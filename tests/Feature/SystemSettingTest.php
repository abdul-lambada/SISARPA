<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\RolePermissionSeeder;

class SystemSettingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_admin_can_update_school_identity()
    {
        $admin = User::whereHas('roles', function($q){ $q->where('name', 'Super Admin'); })->first();

        $response = $this->actingAs($admin)->post(route('settings.update'), [
            'school_name' => 'SMK NEGERI 1 TEST',
            'school_city' => 'KOTA TESTING',
            'school_npsn' => '888777',
            'school_principal_name' => 'John Doe, M.Pd',
        ]);

        $response->assertRedirect();
        
        $this->assertEquals('SMK NEGERI 1 TEST', Setting::get('school_name'));
        $this->assertEquals('KOTA TESTING', Setting::get('school_city'));
        $this->assertEquals('888777', Setting::get('school_npsn'));
    }

    public function test_activity_log_is_recorded_for_settings_change()
    {
        $admin = User::whereHas('roles', function($q){ $q->where('name', 'Super Admin'); })->first();

        $this->actingAs($admin)->post(route('settings.update'), [
            'school_name' => 'Log Test School'
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'activity' => 'Memperbarui pengaturan sistem/identitas sekolah'
        ]);
    }
}
