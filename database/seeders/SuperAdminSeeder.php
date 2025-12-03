<?php

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
        DB::setDefaultConnection('mysql');

        $this->command->info('Creating Super Admin...');

        // Create Super Admin User
        $superAdmin = User::updateOrCreate(
            ['email' => 'ha8028377@gmail.com'],
            [
                'name' => 'HMZA',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'tenant_id' => null,
            ]
        );

        // Assign Super Admin Role
        $role = Role::where('name', 'super-admin')->where('guard_name', 'web')->first();
        
        if ($role && !$superAdmin->hasRole($role)) {
            $superAdmin->assignRole($role);
        }

        $this->command->info('✓ Super Admin created successfully!');
        $this->command->info('Email: ha8028377@gmail.com');
        $this->command->info('Password: password');

        // Create Demo Tenant (Optional)
        if ($this->command->confirm('Do you want to create a demo tenant?', true)) {
            $this->createDemoTenant();
        }
    }

    private function createDemoTenant()
    {
        DB::setDefaultConnection('mysql');
        
        $this->command->info('Creating demo tenant...');

        $tenant = Tenant::firstOrCreate(
            ['domain' => 'demo'],
            [
                'name' => 'Demo Company',
                'database' => 'smartims_tenant_demo',
                'slug' => 'demo',
                'email' => 'demo@example.com',
                'phone' => '1234567890',
                'status' => 'active',
            ]
        );

        // Only create database if TenantService exists
        if (class_exists(\App\Services\TenantService::class)) {
            try {
                $this->command->info("Creating database: {$tenant->database}");
                \App\Services\TenantService::createDatabase($tenant);

                $this->command->info("Running migrations for tenant: {$tenant->name}");
                \App\Services\TenantService::runMigrations($tenant);

                $this->command->info("Creating admin user for tenant: {$tenant->name}");
                \App\Services\TenantService::switchToTenant($tenant);

                $user = User::firstOrCreate(
                    ['email' => 'admin@demo.com'],
                    [
                        'name' => 'Demo Admin',
                        'password' => Hash::make('password'),
                        'email_verified_at' => now(),
                        'tenant_id' => $tenant->id,
                    ]
                );

                $role = Role::firstOrCreate(['name' => 'company-admin', 'guard_name' => 'web']);
                $user->assignRole($role);

                \App\Services\TenantService::switchToCentral();
                
                $this->command->info('✓ Demo tenant created successfully!');
                $this->command->info('Email: admin@demo.com');
                $this->command->info('Password: password');
            } catch (\Exception $e) {
                $this->command->error('Error creating demo tenant: ' . $e->getMessage());
            }
        }
    }
}