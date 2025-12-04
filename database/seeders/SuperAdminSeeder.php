<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use App\Services\TenantService;

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

        // Check if tenant already exists
        $existingTenant = Tenant::where('domain', 'demo')->first();
        if ($existingTenant) {
            $this->command->info('Demo tenant already exists. Skipping...');
            return;
        }

        try {
            // Create tenant using TenantService (correct way)
            $tenantService = new TenantService();
            
            $this->command->info('Creating demo tenant using TenantService...');
            
            $tenant = $tenantService->create(
                'Demo Company',
                'demo',
                'demo@example.com',
                '1234567890'
            );
            
            $this->command->info('✓ Demo tenant created successfully!');
            
            // Now create admin user for this tenant
            $this->createDemoTenantAdmin($tenant);
            
        } catch (\Exception $e) {
            $this->command->error('Error creating demo tenant: ' . $e->getMessage());
            $this->command->error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    private function createDemoTenantAdmin(Tenant $tenant)
    {
        $this->command->info("Creating admin user for tenant: {$tenant->name}");
        
        try {
            // Switch to tenant database
            TenantService::switchToTenant($tenant);
            
            // Check if users table exists
            if (!Schema::hasTable('users')) {
                $this->command->error('Users table does not exist in tenant database!');
                TenantService::switchToCentral();
                return;
            }
            
            // Create admin user
            $user = User::firstOrCreate(
                ['email' => 'admin@demo.com'],
                [
                    'name' => 'Demo Admin',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'tenant_id' => $tenant->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            
            // Assign role (check if roles table exists)
            if (Schema::hasTable('roles')) {
                $role = Role::firstOrCreate(
                    ['name' => 'company-admin', 'guard_name' => 'web']
                );
                
                if (!$user->hasRole($role)) {
                    $user->assignRole($role);
                }
            }
            
            // Switch back to central
            TenantService::switchToCentral();
            
            $this->command->info('✓ Demo tenant admin created successfully!');
            $this->command->info('Email: admin@demo.com');
            $this->command->info('Password: password');
            $this->command->info('Login URL: http://demo.smartims.test:8000/login');
            
        } catch (\Exception $e) {
            $this->command->error('Error creating demo admin: ' . $e->getMessage());
            TenantService::switchToCentral(); // Always switch back
        }
    }
}