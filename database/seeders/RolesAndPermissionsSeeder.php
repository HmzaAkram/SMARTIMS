<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Fix any existing rows that have guard_name = null (common after old seeds)
        Role::whereNull('guard_name')->update(['guard_name' => 'web']);
        Permission::whereNull('guard_name')->update(['guard_name' => 'web']);

        // Permissions — always specify guard_name
        $permissions = [
            'manage tenants',
            'manage inventory',
            'view reports',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web'   // <-- important
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
        $companyAdmin->syncPermissions(['manage inventory', 'view reports']);

        $staff = Role::firstOrCreate([
            'name' => 'staff',
            'guard_name' => 'web'
        ]);
        $staff->givePermissionTo('view reports');
    }
}   