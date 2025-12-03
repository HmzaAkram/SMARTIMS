<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Clear cache using artisan command instead of registrar
        try {
            Artisan::call('cache:clear');
            Artisan::call('permission:cache-reset');
        } catch (\Exception $e) {
            // Ignore cache errors during seeding
            $this->command->warn('Cache clear skipped: ' . $e->getMessage());
        }

        // Make sure we're on the right connection
        DB::setDefaultConnection('mysql');

        // Fix any existing rows that have guard_name = null
        DB::table('roles')->whereNull('guard_name')->update(['guard_name' => 'web']);
        DB::table('permissions')->whereNull('guard_name')->update(['guard_name' => 'web']);

        // Permissions — always specify guard_name
        $permissions = [
            'manage tenants',
            'manage inventory',
            'view reports',
            'manage users',
            'manage roles',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web'
            ]);
        }

        // Roles — always specify guard_name
        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web'
        ]);
        $superAdmin->syncPermissions(Permission::all());

        $companyAdmin = Role::firstOrCreate([
            'name' => 'company-admin',
            'guard_name' => 'web'
        ]);
        $companyAdmin->syncPermissions(['manage inventory', 'view reports', 'manage users']);

        $staff = Role::firstOrCreate([
            'name' => 'staff',
            'guard_name' => 'web'
        ]);
        $staff->givePermissionTo('view reports');

        $this->command->info('Roles and Permissions created successfully!');
    }
}