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

}