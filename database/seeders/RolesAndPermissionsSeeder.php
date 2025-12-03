<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Make sure we're on central database
        DB::setDefaultConnection('mysql');

        // Check if tables exist before proceeding
        if (!Schema::hasTable('permissions') || !Schema::hasTable('roles')) {
            $this->command->error('Permission tables do not exist! Run migrations first.');
            return;
        }

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('Creating permissions...');

        // Create Permissions
        $permissions = [
            'manage tenants',
            'manage inventory',
            'view reports',
            'manage users',
            'manage roles',
            'manage categories',
            'manage items',
            'manage orders',
            'manage warehouses',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        $this->command->info('Creating roles...');

        // Create Super Admin Role
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web']
        );
        $superAdmin->syncPermissions(Permission::all());

        // Create Company Admin Role
        $companyAdmin = Role::firstOrCreate(
            ['name' => 'company-admin', 'guard_name' => 'web']
        );
        $companyAdmin->syncPermissions([
            'manage inventory',
            'view reports',
            'manage users',
            'manage categories',
            'manage items',
            'manage orders',
            'manage warehouses',
        ]);

        // Create Staff Role
        $staff = Role::firstOrCreate(
            ['name' => 'staff', 'guard_name' => 'web']
        );
        $staff->syncPermissions(['view reports', 'manage items']);

        $this->command->info('✓ Roles and Permissions created successfully!');
    }
}