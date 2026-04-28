<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $anggotaRole = Role::firstOrCreate(['name' => 'anggota']);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@helpdesk.com'],
            [
                'name' => 'Admin IT',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole($adminRole);

        // Create anggota user
        $anggota = User::firstOrCreate(
            ['email' => 'pegawai@helpdesk.com'],
            [
                'name' => 'Pegawai',
                'password' => Hash::make('password'),
            ]
        );
        $anggota->assignRole($anggotaRole);
    }
}
