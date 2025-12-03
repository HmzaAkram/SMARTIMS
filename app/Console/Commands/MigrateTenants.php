<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\PermissionRegistrar;

class MigrateTenants extends Command
{
    protected $signature = 'tenant:migrate {--tenant=} {--fresh}';
    protected $description = 'Run tenant migrations (single or all)';

    public function handle()
    {
        // Clear Spatie permission cache
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $tenants = $this->option('tenant')
            ? Tenant::where('slug', $this->option('tenant'))->get()
            : Tenant::all();

        if ($tenants->isEmpty()) {
            $this->error('No tenants found!');
            return 1;
        }

        foreach ($tenants as $tenant) {
            $this->migrateTenant($tenant, $this->option('fresh'));
        }

        $this->info('All tenant migrations completed!');
        return 0;
    }

    protected function migrateTenant(Tenant $tenant, $fresh = false)
    {
        $databaseName = $tenant->database;

        // Create database if not exists
        $this->ensureDatabaseExists($databaseName);

        $this->info("Migrating tenant: {$tenant->name} (DB: {$databaseName})");

        Config::set('database.connections.tenant.database', $databaseName);
        DB::purge('tenant');
        DB::reconnect('tenant');

        try {
            $params = [
                '--database' => 'tenant',
                '--path'     => 'database/migrations/tenant',
                '--force'    => true,
            ];

            if ($fresh) {
                Artisan::call('migrate:fresh', $params);
                $this->info("Fresh migration completed.");
            } else {
                Artisan::call('migrate', $params);
                $this->info("Migration completed.");
            }

            $this->line(Artisan::output());
            $this->info("Successfully migrated {$tenant->name}");
        } catch (\Exception $e) {
            $this->error("Failed to migrate {$tenant->name}: " . $e->getMessage());
        }
    }

    protected function ensureDatabaseExists($name)
    {
        $central = config('database.connections.mysql'); // adjust if different
        try {
            $pdo = new \PDO(
                "mysql:host={$central['host']};port={$central['port']};charset=utf8mb4",
                $central['username'],
                $central['password']
            );
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info("Database ensured: `$name`");
        } catch (\Exception $e) {
            $this->error("Could not create database `$name`: " . $e->getMessage());
        }
    }
}