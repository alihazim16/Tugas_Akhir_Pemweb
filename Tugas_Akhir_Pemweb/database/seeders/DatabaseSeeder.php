<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Import model User
use App\Models\Project; // Import model Project (untuk factory)
use App\Models\Task;    // Import model Task (untuk factory)
use Spatie\Permission\Models\Role; // Import model Role dari Spatie
use Spatie\Permission\Models\Permission; // Import model Permission dari Spatie

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan cache role/permission dihapus agar perubahan langsung terasa saat seeding
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'project manager']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // 2. Buat Permissions (sesuai kebutuhan Project Management System)
        // Jika permission sudah ada, firstOrCreate tidak akan membuat duplikat
        Permission::firstOrCreate(['name' => 'manage users']); // Untuk admin
        Permission::firstOrCreate(['name' => 'create project']);
        Permission::firstOrCreate(['name' => 'update project']);
        Permission::firstOrCreate(['name' => 'delete project']); // Untuk admin
        Permission::firstOrCreate(['name' => 'assign tasks']);
        Permission::firstOrCreate(['name' => 'update tasks']);
        Permission::firstOrCreate(['name' => 'comment tasks']);
        Permission::firstOrCreate(['name' => 'view dashboard']);

        // 3. Tetapkan Permissions ke Roles

        // Role 'admin' memiliki semua permission yang ada
        $adminRole->givePermissionTo(Permission::all());

        // Role 'project manager' memiliki permission spesifik
        $managerRole->givePermissionTo([
            'create project',
            'update project',
            'assign tasks',
            'view dashboard',
            'comment tasks',
            'update tasks'
        ]);

        // Role 'user' (anggota tim) memiliki permission spesifik
        $userRole->givePermissionTo([
            'view dashboard',
            'comment tasks',
            'update tasks' // Dapat mengupdate tugas yang ditugaskan kepadanya
        ]);

        // 4. Buat User Default dan Tetapkan Role

        // User Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'], // Kriteria unik untuk mencari/membuat user
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'), // Password default: 'password'
                'email_verified_at' => now(), // Tandai sebagai terverifikasi
            ]
        );
        $admin->assignRole($adminRole); // Tetapkan role 'admin' ke user ini

        // User Project Manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Project Manager',
                'password' => Hash::make('password'), // Password default: 'password'
                'email_verified_at' => now(),
            ]
        );
        $manager->assignRole($managerRole);

        // User Biasa (Team Member)
        $regularUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'), // Password default: 'password'
                'email_verified_at' => now(),
            ]
        );
        $regularUser->assignRole($userRole);

        $this->command->info('Database Seeded Successfully!');
        $this->command->info('Default Admin: admin@example.com / password');
        $this->command->info('Default Manager: manager@example.com / password');
        $this->command->info('Default User: user@example.com / password');

        // Optional: Jika kamu ingin menambahkan data dummy proyek dan tugas
        // Pastikan ProjectFactory dan TaskFactory sudah dibuat
        // Example: Project::factory(10)->create();
        // Example: Task::factory(50)->create();
    }
}
