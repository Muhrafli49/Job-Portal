<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $permission = [
            'manage categories', 
            'manage company',
            'manage jobs',
            'manage candidates',
            'apply job',
        ];

        foreach ($permission as $permission) {
            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }

        $employerRole = Role::firstOrCreate([
            'name' => 'employer'
        ]);

        $employerPermissions = [
            'manage company', 
            'manage jobs',
            'manage candidates'
        ];

        $employerRole->syncPermissions($employerPermissions);

        $employeeRole = Role::firstOrCreate([
            'name' => 'employee'
        ]);

        $employeePermissions = [
            'apply job'
        ];

        $employeeRole->syncPermissions($employeePermissions);

        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin'
        ]);

        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'super@admin.com',
            'occupation' => 'Superadmin',
            'experience' => 100,
            'avatar' => 'images/default-avatar.png',
            'password' => bcrypt('rahasia123'),
        ]);
        $user->assignRole($superAdminRole);
    }
}
