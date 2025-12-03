<?php
// database/seeders/SuperAdminSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Ensure we're in central DB
        app()->forgetInstance('tenant');
        DB::setDefaultConnection('mysql');

        // Create Super Admin in central DB
        $superAdmin = User::updateOrCreate(
            ['email' => 'ha8028377@gmail.com'],
            [
                'name' => 'HMZA',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'tenant_id' => null,
            ]
        );

        // Assign role (ensure role exists)
        $role = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->assignRole($role);

        // Optional: Create a demo tenant for testing
        $this->createDemoTenant();
        
        // Switch back to central
        \App\Services\TenantService::switchToCentral();
    }

    private function createDemoTenant()
    {
        // Make sure we're on central database
        DB::setDefaultConnection('mysql');
        
        $tenant = Tenant::updateOrCreate(
            ['domain' => 'demo'],
            [
                'name' => 'Demo Company',
                'database' => 'smartims_tenant_demo',
                'slug' => 'demo',
                'email' => 'demo@example.com',
                'phone' => '1234567890',
            ]
        );

        // Step 1: Create the database first
        $this->command->info("Creating database: {$tenant->database}");
        \App\Services\TenantService::createDatabase($tenant);

        // Step 2: Run migrations on the new database
        $this->command->info("Running migrations for tenant: {$tenant->name}");
        \App\Services\TenantService::runMigrations($tenant);

        // Step 3: Now switch to tenant and create admin user
        $this->command->info("Creating admin user for tenant: {$tenant->name}");
        \App\Services\TenantService::switchToTenant($tenant);

        // Create company admin in tenant DB
        $user = User::updateOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Demo Company Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'tenant_id' => $tenant->id,
            ]
        );

        $role = Role::firstOrCreate(['name' => 'company-admin', 'guard_name' => 'web']);
        $user->assignRole($role);
        
        $this->command->info("Demo tenant created successfully!");
    }
}