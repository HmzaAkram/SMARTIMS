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
        app()->forgetInstance('tenant');
        DB::setDefaultConnection('mysql');

        // Super admin ke liye tenant_id NULL hona chahiye
        $superAdmin = User::updateOrCreate(
            ['email' => 'ha8028377@gmail.com'],
            [
                'name' => 'HMZA',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'tenant_id' => null, // Super admin central DB mein hai
            ]
        );

        // Role assignment - directly insert with tenant_id = null
        $role = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        
        // Check if role already assigned
        if (!$superAdmin->hasRole($role)) {
            // Manually insert to handle tenant_id
            DB::table('model_has_roles')->insert([
                'role_id' => $role->id,
                'model_type' => get_class($superAdmin),
                'model_id' => $superAdmin->id,
                'tenant_id' => null, // Super admin ke liye null
            ]);
        }

        $this->command->info("Super Admin created successfully!");

        // Optional: Create demo tenant
        $this->createDemoTenant();
        
        // Switch back to central
        \App\Services\TenantService::switchToCentral();
    }

    private function createDemoTenant()
    {
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

        $this->command->info("Creating database: {$tenant->database}");
        \App\Services\TenantService::createDatabase($tenant);

        $this->command->info("Running migrations for tenant: {$tenant->name}");
        \App\Services\TenantService::runMigrations($tenant);

        $this->command->info("Creating admin user for tenant: {$tenant->name}");
        \App\Services\TenantService::switchToTenant($tenant);

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
        
        // Tenant user ke liye tenant_id chahiye
        if (!$user->hasRole($role)) {
            DB::table('model_has_roles')->insert([
                'role_id' => $role->id,
                'model_type' => get_class($user),
                'model_id' => $user->id,
                'tenant_id' => $tenant->id, // Tenant user ke liye tenant_id
            ]);
        }
        
        $this->command->info("Demo tenant created successfully!");
    }
}