<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;

class TenantMigrateCommand extends Command
{
    protected $signature = 'tenant:migrate {tenant?} {--fresh} {--seed}';
    protected $description = 'Run migrations for a tenant';

    public function handle()
    {
        $tenantDomain = $this->argument('tenant');
        
        if ($tenantDomain) {
            // Run for specific tenant
            $tenant = Tenant::where('domain', $tenantDomain)
                ->orWhere('slug', $tenantDomain)
                ->first();
            
            if (!$tenant) {
                $this->error("Tenant not found: {$tenantDomain}");
                return;
            }
            
            $this->runMigrationsForTenant($tenant);
        } else {
            // Run for all tenants
            $tenants = Tenant::all();
            
            foreach ($tenants as $tenant) {
                $this->info("Running migrations for tenant: {$tenant->name}");
                $this->runMigrationsForTenant($tenant);
            }
        }
    }
    
    private function runMigrationsForTenant($tenant)
    {
        // Set the tenant database
        config(['database.connections.tenant.database' => $tenant->database]);
        DB::purge('tenant');
        DB::reconnect('tenant');
        
        // Run migrations
        try {
            $this->info("Using database: {$tenant->database}");
            
            $options = [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
            ];
            
            if ($this->option('fresh')) {
                $options['--force'] = true;
                Artisan::call('migrate:fresh', $options);
            } else {
                Artisan::call('migrate', $options);
            }
            
            if ($this->option('seed')) {
                Artisan::call('db:seed', [
                    '--database' => 'tenant',
                    '--class' => 'TenantDatabaseSeeder',
                ]);
            }
            
            $this->info("Migrations completed for {$tenant->name}");
            
        } catch (\Exception $e) {
            $this->error("Error for {$tenant->name}: " . $e->getMessage());
        }
    }
}