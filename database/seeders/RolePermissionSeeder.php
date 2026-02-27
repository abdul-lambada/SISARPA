<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'manage categories',
            'manage assets',
            'manage loans',
            'manage maintenance',
            'manage users',
            'view reports',
            'view assets',
            'view own loans'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'Super Admin']);
        $role1->givePermissionTo(Permission::all());

        $role2 = Role::create(['name' => 'Petugas Sarpras']);
        $role2->givePermissionTo([
            'manage categories',
            'manage assets',
            'manage loans',
            'manage maintenance',
            'view reports'
        ]);

        $role3 = Role::create(['name' => 'User']);
        $role3->givePermissionTo([
            'view assets',
            'view own loans'
        ]);

        // Create initial users
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@sisarpa.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole($role1);

        $petugas = User::create([
            'name' => 'Petugas Sarpras',
            'email' => 'petugas@sisarpa.com',
            'password' => Hash::make('password'),
        ]);
        $petugas->assignRole($role2);

        $user = User::create([
            'name' => 'Siswa Example',
            'email' => 'siswa@sisarpa.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($role3);
    }
}
