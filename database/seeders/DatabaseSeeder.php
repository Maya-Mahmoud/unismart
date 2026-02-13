<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'book_halls',
            'lectures',
            'generate_qr',
            'advanced_scheduling',
            'Performance',
            'calendar',
            'admin panel',  // Added new permission
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $allPermissions = \Spatie\Permission\Models\Permission::all();
        $adminRole->syncPermissions($allPermissions);

        // Assign all permissions directly to admin users
        $adminUsers = \App\Models\User::where('role', 'admin')->get();
        foreach ($adminUsers as $adminUser) {
            $adminUser->syncPermissions($allPermissions);
        }

        // Ensure 'admin panel' permission is assigned to admin role
        $adminPanelPermission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'admin panel']);
        if (!$adminRole->hasPermissionTo($adminPanelPermission)) {
            $adminRole->givePermissionTo($adminPanelPermission);
        }
        

        $professorRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'professor']);
        $professorRole->syncPermissions([
            'book_halls',
            'lectures',
            'generate_qr',
            'advanced_scheduling',
            'Performance',
            'calendar',
        ]);

        $studentRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'student']);
        $studentRole->syncPermissions(['calendar']);

        // Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );
        $adminUser->assignRole($adminRole);
    }
}
